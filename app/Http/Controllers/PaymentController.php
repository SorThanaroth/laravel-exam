// app/Http/Controllers/PaymentController.php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index()
    {
        $payments = Payment::with(['booking.terrain', 'booking.renter'])
            ->whereHas('booking', function ($query) {
                $query->where('renter_id', auth()->id())
                    ->orWhereHas('terrain', function ($q) {
                        $q->where('owner_id', auth()->id());
                    });
            })
            ->paginate(15);

        return response()->json($payments);
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $payment = Payment::create($validated);

        return response()->json($payment->load('booking'), 201);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment->load(['booking.terrain', 'booking.renter']));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());
        return response()->json($payment->load('booking'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}