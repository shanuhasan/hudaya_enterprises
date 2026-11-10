<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    private const IMAGE = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=8000,max_height=8000'];

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $lines = fn (string $field) => collect(preg_split('/\r\n|\n|\r/', (string) $this->input($field)))
            ->map(fn ($l) => trim($l))->filter()->values();

        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
            'highlights' => $lines('highlights_text')->all(),
            // Spec rows arrive as specs[i][label] / specs[i][value]; empty rows are dropped.
            'specs' => collect($this->input('specs', []))
                ->map(fn ($row) => ['label' => trim($row['label'] ?? ''), 'value' => trim($row['value'] ?? '')])
                ->filter(fn ($row) => $row['label'] !== '' || $row['value'] !== '')
                ->values()->all(),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($this->route('category'))],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['boolean'],

            'title' => ['required', 'string', 'max:255'],
            'title_em' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'highlights' => ['array', 'max:8'],
            'highlights.*' => ['string', 'max:60'],
            'card_image_file' => self::IMAGE,
            'card_image_alt' => ['nullable', 'string', 'max:255'],

            'hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_title_em' => ['nullable', 'string', 'max:255'],
            'hero_lead' => ['nullable', 'string', 'max:500'],
            'hero_image_file' => self::IMAGE,

            'intro_title' => ['required', 'string', 'max:255'],
            'intro_title_em' => ['nullable', 'string', 'max:255'],
            'intro_text' => ['nullable', 'string', 'max:2000'],
            'intro_image_file' => self::IMAGE,
            'intro_image_alt' => ['nullable', 'string', 'max:255'],
            'intro_image_position' => ['required', Rule::in(['left', 'right'])],

            'types_title' => ['required', 'string', 'max:255'],
            'types_title_em' => ['nullable', 'string', 'max:255'],

            'specs' => ['array', 'max:12'],
            'specs.*.label' => ['required', 'string', 'max:60'],
            'specs.*.value' => ['required', 'string', 'max:160'],

            'cta_eyebrow' => ['nullable', 'string', 'max:255'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_title_em' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:500'],

            'meta_title' => ['required', 'string', 'max:70'],
            'meta_description' => ['required', 'string', 'max:160'],
        ];
    }

    public function attributes(): array
    {
        return [
            'card_image_file' => 'card image',
            'hero_image_file' => 'hero image',
            'intro_image_file' => 'intro image',
            'specs.*.label' => 'detail name',
            'specs.*.value' => 'detail value',
            'highlights.*' => 'highlight',
            'meta_title' => 'SEO title',
            'meta_description' => 'SEO description',
        ];
    }

    public function messages(): array
    {
        return ['*.dimensions' => 'Images must be at least 400 × 400 pixels.'];
    }
}
