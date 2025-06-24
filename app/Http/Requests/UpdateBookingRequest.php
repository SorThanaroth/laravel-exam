// app/Http/Requests/UpdateBookingRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('booking'));
    }

    public function rules(): array
    {
        return [
            'start_date' => 'sometimes|required|date|after_or_equal:today',
            'end_date' => 'sometimes|required|date|after:start_date',
            'status' => 'sometimes|required|in:pending,approved,rejected,cancelled,completed',
        ];
    }
}