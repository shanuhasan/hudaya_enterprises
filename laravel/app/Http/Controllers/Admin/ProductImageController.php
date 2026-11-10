<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Additional photos per product: upload, describe, reorder, promote to featured, delete. */
class ProductImageController extends Controller
{
    private const IMAGE_RULE = ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=6000,max_height=6000'];

    public function __construct(private readonly ImageService $images) {}

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'images' => fn ($q) => $q->limit(50)])
            ->withCount('images')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('category_id')->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        return view('admin.product-images.index', compact('products'));
    }

    public function show(Product $product): View
    {
        return view('admin.product-images.show', ['product' => $product->load('images', 'category')]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate(
            ['images' => ['required', 'array', 'min:1', 'max:12'], 'images.*' => self::IMAGE_RULE],
            ['images.required' => 'Choose at least one image to upload.', 'images.*.dimensions' => 'Images must be between 400 and 6000 pixels on each side.'],
            ['images.*' => 'image'],
        );

        $next = (int) $product->images()->max('sort_order');
        foreach ($request->file('images') as $file) {
            $product->images()->create([
                'path' => $this->images->storePhoto($file, 'products'),
                'alt' => $product->name,
                'sort_order' => ++$next,
            ]);
        }

        $count = count($request->file('images'));

        return back()->with('status', $count === 1 ? 'Image uploaded.' : "$count images uploaded.");
    }

    public function update(Request $request, ProductImage $image): RedirectResponse
    {
        $data = $request->validate([
            'alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $image->update($data);

        return back()->with('status', 'Image details saved.');
    }

    /** Makes this image the product's featured image; the previous featured image joins the extra images. */
    public function feature(ProductImage $image): RedirectResponse
    {
        DB::transaction(function () use ($image) {
            $product = $image->product;

            if ($product->image) {
                $product->images()->create([
                    'path' => $product->image,
                    'alt' => $product->image_alt ?: $product->name,
                    'sort_order' => $image->sort_order,
                ]);
            }

            $product->update(['image' => $image->path, 'image_alt' => $image->alt ?: $product->name]);
            $image->delete(); // only the row: the file is now the featured image
        });

        return back()->with('status', 'Featured image updated.');
    }

    public function destroy(ProductImage $image): RedirectResponse
    {
        // Keep the file if the same path is still the featured image of the product.
        if ($image->product->image !== $image->path) {
            $this->images->delete($image->path);
        }
        $image->delete();

        return back()->with('status', 'Image deleted.');
    }
}
