<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductType;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Types inside a category (type cards + filter tabs). Shallow nested: /admin/categories/{category}/types/create, /admin/types/{type}/edit. */
class ProductTypeController extends Controller
{
    public function __construct(private readonly ImageService $images) {}

    public function create(Category $category): View
    {
        return view('admin.types.form', [
            'category' => $category,
            'type' => new ProductType(['is_active' => true, 'sort_order' => (int) $category->types()->max('sort_order') + 1]),
        ]);
    }

    public function store(Request $request, Category $category): RedirectResponse
    {
        $type = $category->types()->make();
        $this->fill($type, $this->validated($request, $category), $request);
        $type->save();

        return redirect()->route('admin.categories.edit', $category)->with('status', "Type “{$type->name}” added.");
    }

    public function edit(ProductType $type): View
    {
        return view('admin.types.form', ['category' => $type->category, 'type' => $type]);
    }

    public function update(Request $request, ProductType $type): RedirectResponse
    {
        $this->fill($type, $this->validated($request, $type->category, $type), $request);
        $type->save();

        return redirect()->route('admin.categories.edit', $type->category)->with('status', "Type “{$type->name}” saved.");
    }

    public function destroy(ProductType $type): RedirectResponse
    {
        $category = $type->category;
        $this->images->delete($type->image);
        $type->delete(); // product links cascade; products themselves stay

        return redirect()->route('admin.categories.edit', $category)->with('status', "Type “{$type->name}” deleted.");
    }

    private function validated(Request $request, Category $category, ?ProductType $type = null): array
    {
        $request->merge(['slug' => Str::slug(str_replace("'", '', $request->input('slug') ?: $request->input('short_name', '')))]);

        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'short_name' => ['required', 'string', 'max:40'],
            'slug' => ['required', 'alpha_dash', 'max:40', Rule::unique('product_types', 'slug')->where('category_id', $category->id)->ignore($type)],
            'description' => ['nullable', 'string', 'max:300'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=8000,max_height=8000'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['boolean'],
        ], [
            'slug.unique' => 'This category already has a type with this filter key.',
            'image_file.dimensions' => 'Images must be at least 400 × 400 pixels.',
        ], ['short_name' => 'filter tab label', 'slug' => 'filter key', 'image_file' => 'image']);
    }

    private function fill(ProductType $type, array $data, Request $request): void
    {
        unset($data['image_file']);
        $type->fill($data);

        if ($request->hasFile('image_file')) {
            $this->images->delete($type->image);
            $type->image = $this->images->storePhoto($request->file('image_file'), 'types');
        }
    }
}
