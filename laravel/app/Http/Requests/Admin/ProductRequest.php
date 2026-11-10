<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug(str_replace(["'", '’'], '', $this->input('slug') ?: $this->input('name', ''))),
            // "S, M, L,  XL" → ["S", "M", "L", "XL"]
            'sizes' => collect(preg_split('/[,\n]+/', (string) $this->input('sizes_text')))
                ->map(fn ($s) => trim($s))->filter()->unique()->values()->all(),
        ]);
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', 'alpha_dash', Rule::unique('products', 'slug')->ignore($product)],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'type_label' => ['nullable', 'string', 'max:80'],
            'types' => ['nullable', 'array'],
            'types.*' => ['integer', Rule::exists('product_types', 'id')->where('category_id', $this->integer('category_id'))],
            'description' => ['nullable', 'string', 'max:5000'],
            'material' => ['nullable', 'string', 'max:160'],
            'sizes' => ['array', 'max:20'],
            'sizes.*' => ['string', 'max:20'],
            'moq' => ['nullable', 'string', 'max:80'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=6000,max_height=6000'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array', 'max:12'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=6000,max_height=6000'],
            'is_featured' => ['boolean'],
            'featured_position' => ['nullable', 'integer', 'min:1', 'max:999'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // A product shown on the site needs a photo.
                $hasImage = $this->hasFile('image') || $this->route('product')?->image;
                if ($this->boolean('is_active') && ! $hasImage) {
                    $validator->errors()->add('image', 'Add a featured image before making the product active.');
                }
            },
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'types.*' => 'product type',
            'gallery.*' => 'gallery image',
            'image_alt' => 'image description',
            'moq' => 'minimum order quantity',
            'sizes.*' => 'size',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Another product already uses this URL slug.',
            'types.*.exists' => 'Choose types that belong to the selected category.',
            '*.dimensions' => 'Images must be between 400 and 6000 pixels on each side.',
            'gallery.*.dimensions' => 'Gallery images must be between 400 and 6000 pixels on each side.',
        ];
    }
}
