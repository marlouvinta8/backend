<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservations;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReservationsController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');//para makita ang mga naka reserve dipende sa date na ilalagay
        $reservations = Reservations::whereDate('reserve_date', $date)->get();
        
        if ($reservations->count() > 0) {
            return response()->json([
                'status' => 200,
                'reservations' => $reservations
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Reservation'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'reserve_date' => 'required|date',
            'service' => 'required'
        ]);

         //request ng member
       // $name = $request->name;
        $reserve_date = Carbon::parse($request->reserve_date)->format('Y-m-d');

         //para macheck kung may available slot sa araw na napili ng member
        $availableSlots = $this->getAvailableSlots($reserve_date);

        if ($availableSlots <= 0) {
            return response()->json([
                'status' => 404,
                'message' => 'This day is fully booked already'
            ], 400);
        } else {
             //hindi ito madadagdag sa database hanggat hindi inaapprove ng admin
            $reservations = Reservations::create([
                'name' => $request->name,
                'reserve_date' => $request->reserve_date,
                // 'reserved' => false
            ]);

            if ($reservations) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Reservation submitted for admin approval'
                ], 200);
            }
        }
    }

    private function getAvailableSlots($reserve_date)
    {
        //check muna kung ilang reservation na ang mayroon base sa araw na napili ng customer
        $reservationCount = Reservations::where('reserve_date', $reserve_date)
            // ->where('reserved', true)
            ->count();

             //kung ilang reservation lang kayang iacommodate sa isang araw
        $maxSlots = 5;
        $availableSlots = $maxSlots - $reservationCount;

        if ($availableSlots <= 0) {
            return 0;
        }

        return $availableSlots;
    }

    // public function approval($id)
    // {
    //     $reservation = Reservations::findOrFail($id);
    //     $reservation->update(['reserved' => true]);

    //     if ($reservation) {
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Reservation approved!'
    //         ], 200);
    //     }
    // }
}
