<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /** form field => column */
    private const IMAGES = [
        'card_image_file' => 'card_image',
        'hero_image_file' => 'hero_image',
        'intro_image_file' => 'intro_image',
    ];

    public function __construct(private readonly ImageService $images) {}

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->ordered()->withCount(['products', 'types'])->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category([
                'is_active' => true,
                'intro_image_position' => 'right',
                'sort_order' => (int) Category::query()->max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = new Category;
        $this->fill($category, $request);
        $category->save();

        return redirect()->route('admin.categories.edit', $category)
            ->with('status', "“{$category->name}” has been created. Add its product types below.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', ['category' => $category->load('types')->loadCount('products')]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->fill($category, $request);
        $category->save();

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Changes saved.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->withTrashed()->exists()) {
            return back()->with('error', "“{$category->name}” still has products. Move or delete them first, or set the category to inactive.");
        }

        foreach (self::IMAGES as $column) {
            $this->images->delete($category->{$column});
        }
        $category->types->each(fn ($type) => $this->images->delete($type->image));
        $category->delete(); // types cascade

        return redirect()->route('admin.categories.index')->with('status', "“{$category->name}” has been deleted.");
    }

    private function fill(Category $category, CategoryRequest $request): void
    {
        $category->fill($request->safe()->except(array_keys(self::IMAGES)));

        foreach (self::IMAGES as $field => $column) {
            if ($request->hasFile($field)) {
                $this->images->delete($category->{$column});
                $category->{$column} = $this->images->storePhoto($request->file($field), 'categories');
            }
        }
    }
}
