

// app/Http/Controllers/BookingController.php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Terrain;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index()
    {
        $bookings = Booking::with(['terrain', 'renter', 'payments'])
            ->where('renter_id', auth()->id())
            ->orWhereHas('terrain', function ($query) {
                $query->where('owner_id', auth()->id());
            })
            ->paginate(15);

        return response()->json($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $validated['renter_id'] = auth()->id();

        $terrain = Terrain::findOrFail($validated['terrain_id']);
        
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;
        
        $validated['total_price'] = $terrain->price_per_day * $days;

        $booking = Booking::create($validated);

        return response()->json($booking->load(['terrain', 'renter']), 201);
    }

    public function show(Booking $booking)
    {
        return response()->json($booking->load(['terrain', 'renter', 'payments']));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();

        if (isset($validated['start_date']) || isset($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'] ?? $booking->start_date);
            $endDate = Carbon::parse($validated['end_date'] ?? $booking->end_date);
            $days = $startDate->diffInDays($endDate) + 1;
            
            $validated['total_price'] = $booking->terrain->price_per_day * $days;
        }

        $booking->update($validated);

        return response()->json($booking->load(['terrain', 'renter', 'payments']));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}

