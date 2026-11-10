<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GalleryGroup;
use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(private readonly ImageService $images) {}

    public function index(Request $request): View
    {
        $group = GalleryGroup::tryFrom((string) $request->input('group'));

        return view('admin.gallery.index', [
            'items' => GalleryItem::query()
                ->when($group, fn ($q) => $q->where('group', $group))
                ->when($request->filled('q'), fn ($q) => $q->where('caption', 'like', '%'.$request->string('q').'%'))
                ->ordered()
                ->paginate(20)
                ->withQueryString(),
            'group' => $group,
            'counts' => GalleryItem::query()->select('group')->selectRaw('count(*) as total')->groupBy('group')->pluck('total', 'group'),
            'total' => GalleryItem::query()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['item' => new GalleryItem([
            'is_active' => true,
            'aspect' => '4/5',
            'sort_order' => (int) GalleryItem::query()->max('sort_order') + 1,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = new GalleryItem;
        $this->fill($item, $request);
        $item->save();

        return redirect()->route('admin.gallery.index')->with('status', "“{$item->caption}” has been added to the gallery.");
    }

    public function edit(GalleryItem $item): View
    {
        return view('admin.gallery.form', compact('item'));
    }

    public function update(Request $request, GalleryItem $item): RedirectResponse
    {
        $this->fill($item, $request);
        $item->save();

        return redirect()->route('admin.gallery.edit', $item)->with('status', 'Changes saved.');
    }

    public function destroy(GalleryItem $item): RedirectResponse
    {
        $this->images->delete($item->image);
        $item->delete();

        return redirect()->route('admin.gallery.index')->with('status', "“{$item->caption}” has been removed from the gallery.");
    }

    private function fill(GalleryItem $item, Request $request): void
    {
        $data = $request->validate([
            'caption' => ['required', 'string', 'max:255'],
            'short_caption' => ['nullable', 'string', 'max:60'],
            'group' => ['required', Rule::enum(GalleryGroup::class)],
            'image_file' => [$item->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=6000,max_height=6000'],
            'image_alt' => ['required', 'string', 'max:255'],
            'aspect' => ['required', Rule::in(GalleryItem::ASPECTS)],
            'home_position' => ['nullable', 'integer', 'between:1,5', Rule::unique('gallery_items', 'home_position')->ignore($item)],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ], [
            'image_file.required' => 'Choose an image to upload.',
            'image_file.dimensions' => 'Images must be between 400 and 6000 pixels on each side.',
            'home_position.unique' => 'Another image already uses this Home mosaic slot.',
        ], ['image_file' => 'image', 'image_alt' => 'image description', 'home_position' => 'Home mosaic slot']);

        unset($data['image_file']);
        $item->fill($data);

        if ($request->hasFile('image_file')) {
            $this->images->delete($item->image);
            $item->image = $this->images->storePhoto($request->file('image_file'), 'gallery');
        }
    }
}
