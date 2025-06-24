// app/Http/Requests/UpdatePaymentRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('payment'));
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|required|in:paid,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
        ];
    }
}
