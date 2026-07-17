<?php

namespace App\Http\Requests;

use App\Enums\ReturnReason;
use App\Enums\ReturnType;
use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'order_item_id' => ['required', 'exists:order_items,id'],
            'type' => ['required', 'in:' . implode(',', ReturnType::values())],
            'reason' => ['required', 'in:' . implode(',', ReturnReason::values())],
            'reason_note' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->input('type') === ReturnType::EXCHANGE->value) {
            $rules['requested_variant_id'] = ['required', 'exists:product_variants,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'order_item_id.required' => 'Please select an order item.',
            'type.required' => 'Please choose Return or Exchange.',
            'reason.required' => 'Please select a reason.',
            'requested_variant_id.required' => 'Please select a new size/color for the exchange.',
        ];
    }
}
