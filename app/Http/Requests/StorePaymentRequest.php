// app/Http/Requests/StorePaymentRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|min:0.01',
            'transaction_id' => 'nullable|string|max:255',
        ];
    }
}