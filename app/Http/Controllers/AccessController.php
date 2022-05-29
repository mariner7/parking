<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingAccess;
use App\Models\Vehicles;
use Illuminate\Support\Carbon;

class AccessController extends Controller
{
    public function checkIn(Request $request) {

        $request->validate([
            'license_plate' => 'required|string|max:7'
        ]);

        return ParkingAccess::create([
            'license_plate' => $request->license_plate,
            'check_in' => Carbon::now()
        ]);
        
    }

    public function checkOut($id) {
        $parking = ParkingAccess::find($id);
        $parking->update([
            'check_out' => Carbon::now()
        ]);

        //NUMBER OF MINUTES OF THE STAY
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $parking->check_in);
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $parking->check_out);

        $minutes = $start_date->diffInMinutes($end_date);

        /** 
         * Use Vehicles model to check if vehicle should be charged or not
         *  @param str license_plate from ParkingAccess 
         */

        $vehicle = Vehicles::where('license_plate', $parking->license_plate)->first();

        if($vehicle) {
            return redirect()->route('toResidentsUpdate',[$vehicle->id, $minutes]);
        } else{
            // NON-RESIDENTS CALCULATE FEE TO PAY 
            // TOTAL FEE
            $total = number_format($minutes * 0.5, 2, '.', ',');

            return ([
                "ENTRADA: " . $parking->check_in,
                "SALIDA: "  . $parking->check_out,
                "TOTAL A PAGAR: $" . $total
            ]);
        }

        // switch($vehicle->type) {
        //     case 1: // OFFICIAL VEHICLES. NO FEE, ONLY CUMULATIVE TIME
        //     case 2: // RESIDENT VEHICLES WILL ACUMMULATE TIME AND WILL WILL BE CHARGED AT THE END OF PERIOD.
        //         $total_time = $minutes + $vehicle->cumulative_time;

        //         $vehicle->update([
        //             'cumulative_time' => $total_time
        //         ]);

        //         break;
        //     default: // NON-RESIDENTS CALCULATE FEE TO PAY 
        //     // TOTAL FEE
        //     $total = number_format($minutes * 0.5, 2, '.', ',');

        //     return ([
        //         "ENTRADA: " . $parking->check_in,
        //         "SALIDA: "  . $parking->check_out,
        //         "TOTAL A PAGAR: $" . $total
        //     ]);

        //     break;
        // }
    }
}
