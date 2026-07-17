<?php

namespace App\Http\Requests;

use App\Enums\ReturnStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'status' => ['required', 'in:' . implode(',', ReturnStatus::values())],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'refund_amount' => ['nullable', 'numeric', 'min:0'],
            'refund_method' => ['nullable', 'string', 'max:50'],
        ];

        // Reject requires a note; refund amount required when processing a refund
        if ($this->input('status') === ReturnStatus::REJECTED->value) {
            $rules['admin_note'] = ['required', 'string', 'max:1000'];
        }

        if ($this->input('status') === ReturnStatus::REFUNDED->value) {
            $rules['refund_amount'] = ['required', 'numeric', 'min:0'];
            $rules['refund_method'] = ['required', 'string', 'max:50'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'admin_note.required' => 'A reason note is required when rejecting a request.',
            'refund_amount.required' => 'Please enter the refund amount.',
            'refund_method.required' => 'Please enter the refund method.',
        ];
    }
}
