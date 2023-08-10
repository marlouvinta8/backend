<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservations;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReservationsController extends Controller
{
    public function index(){
        $reservations = Reservations::all();

        if($reservations->count() > 0) {
            return response()->json([
                'status' => 200,
                'reservations' => $reservations
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'reservations' => 'No Reservation Found'
            ],404);
        }
    }
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|digits:11',
        'address' => 'required',
        'reserve_date' => 'required|date',
        'service' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 402,
            'errors' => $validator->messages()
        ], 402);
    } else {
        $reserve_date = Carbon::parse($request->reserve_date);
        $availableSlots = $this->getAvailableSlots($reserve_date);

        if ($availableSlots <= 0) {
            return response()->json([
                'status' => 404,
                'message' => 'This day is fully booked already'
            ], 400);
        } else {

            // Create the reservation
            $reservations = Reservations::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'reserve_date' => $request->reserve_date,
                'service' => $request->service
            ]);

            if ($reservations) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Reservation submitted successfully'
                ], 200);
            }
        }
    }
}
    private function getAvailableSlots($reserve_date)
    {
        //check muna kung ilang reservation na ang mayroon base sa araw na napili ng customer
        $reservationCount = Reservations::where('reserve_date', $reserve_date)->count();

             //kung ilang reservation lang kayang iacommodate sa isang araw
        $maxSlots = 5;
        $availableSlots = $maxSlots - $reservationCount;

        if ($availableSlots <= 0) {
            return 0;
        }

        return $availableSlots;
    }
}
