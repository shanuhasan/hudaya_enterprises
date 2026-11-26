<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ImageService $images) {}

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->withCount('images')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('slug', 'like', '%'.$request->string('q').'%')
                ->orWhere('material', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'featured', fn ($q) => $q->whereNotNull('featured_position'))
            ->orderBy('category_id')->orderBy('sort_order')->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::query()->ordered()->pluck('name', 'id'),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Product(['is_active' => true]));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $product = new Product;
            $this->fill($product, $request);
            $product->save();
            $product->types()->sync($request->input('types', []));
            $this->storeGallery($product, $request);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('status', "“{$product->name}” has been created.");
    }

    public function edit(Product $product): View
    {
        return $this->form($product->load('types', 'images'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $this->fill($product, $request);
            $product->save();
            $product->types()->sync($request->input('types', []));
            $this->storeGallery($product, $request);
        });

        return redirect()->route('admin.products.edit', $product)->with('status', 'Changes saved.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        $this->images->delete($product->image);
        $product->images->each(fn ($image) => $this->images->delete($image->path));
        $product->forceDelete(); // product images + type links cascade

        return redirect()->route('admin.products.index')->with('status', "“{$name}” has been deleted.");
    }

    private function form(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->ordered()->with('types')->get(),
        ]);
    }

    private function fill(Product $product, ProductRequest $request): void
    {
        $data = $request->safe()->only([
            'name', 'slug', 'category_id', 'type_label', 'description', 'material', 'sizes', 'moq', 'image_alt', 'is_active',
        ]);
        $data['sort_order'] = $request->integer('sort_order');

        if ($request->boolean('is_featured')) {
            $data['featured_position'] = $request->input('featured_position')
                ?: ($product->featured_position ?? ((int) Product::query()->max('featured_position') + 1));
        } else {
            $data['featured_position'] = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($product->image);
            $data['image'] = $this->images->storePhoto($request->file('image'), 'products');
        }

        $product->fill($data);
    }

    private function storeGallery(Product $product, ProductRequest $request): void
    {
        $next = (int) $product->images()->max('sort_order');

        foreach ($request->file('gallery', []) as $file) {
            $product->images()->create([
                'path' => $this->images->storePhoto($file, 'products'),
                'alt' => $product->name,
                'sort_order' => ++$next,
            ]);
        }
    }
}
