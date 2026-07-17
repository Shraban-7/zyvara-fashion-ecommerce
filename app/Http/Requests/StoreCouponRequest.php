<?php

namespace App\Http\Requests;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueCode = Rule::unique('coupons', 'code');

        return [
            'code' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_-]+$/', $uniqueCode],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::in(CouponType::values())],
            'value' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'applicable_categories' => ['nullable', 'array'],
            'applicable_categories.*' => ['integer', 'exists:categories,id'],
            'applicable_products' => ['nullable', 'array'],
            'applicable_products.*' => ['integer', 'exists:products,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Coupon code may only contain letters, numbers, hyphens and underscores.',
            'code.unique' => 'A coupon with this code already exists.',
            'expires_at.after_or_equal' => 'The expiry date must be after or equal to the start date.',
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('code')) {
            $this->merge(['code' => strtoupper(trim($this->code))]);
        }
    }
}
