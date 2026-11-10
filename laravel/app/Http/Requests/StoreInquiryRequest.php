<?php

namespace App\Http\Requests;

use App\Enums\BuyerType;
use App\Enums\InquiryCategory;
use App\Enums\Timeline;
use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Public quote form on /contact. Rules mirror the browser-side checks in resources/js/modules/forms.js. */
class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $trim = fn ($v) => is_string($v) ? trim(preg_replace('/\s+/u', ' ', $v)) : $v;

        $this->merge([
            'name' => $trim($this->input('name')),
            'company' => $trim($this->input('company')),
            'email' => strtolower((string) $trim($this->input('email'))),
            'phone' => $trim($this->input('phone')),
            'country' => $trim($this->input('country')),
            'city' => $trim($this->input('city')),
            'product' => $trim($this->input('product')),
            'quantity' => $trim($this->input('quantity')),
            'message' => is_string($this->input('message')) ? trim($this->input('message')) : $this->input('message'),
            'timeline' => $this->input('timeline') ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^[0-9\s\+\-\(\)]{7,20}$/'],
            'country' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:80'],
            'buyer_type' => ['required', Rule::enum(BuyerType::class)],
            'category' => ['required', Rule::enum(InquiryCategory::class)],
            'product' => ['nullable', 'string', 'max:120'],
            'quantity' => ['required', 'string', 'max:60'],
            'timeline' => ['nullable', Rule::enum(Timeline::class)],
            'customisation' => ['nullable', 'array', 'max:'.count(Inquiry::CUSTOMISATION_OPTIONS)],
            'customisation.*' => ['string', Rule::in(Inquiry::CUSTOMISATION_OPTIONS)],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string', 'max:255'], // honeypot — checked in the controller
        ];
    }

    /** Same wording as the browser-side messages. */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'company.required' => 'Please enter your company or brand name.',
            'email.*' => 'Please enter a valid email address, e.g. name@company.com.',
            'phone.*' => 'Please enter a valid phone or WhatsApp number.',
            'buyer_type.*' => 'Please choose your business type.',
            'category.*' => 'Please choose a product category.',
            'quantity.required' => 'Please give an estimated quantity, e.g. 500 pieces per style.',
            'message.required' => 'Please describe your requirement (at least 20 characters).',
            'message.min' => 'Please describe your requirement (at least 20 characters).',
            'consent.accepted' => 'Please agree so we can reply to your inquiry.',
        ];
    }
}
