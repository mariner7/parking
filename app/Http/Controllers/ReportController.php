<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function report() {

        $report_name = "report".date('YmdHis').".csv";
        $report_txt = str_pad("Num. placa", 25);
        $report_txt .= str_pad("Tiempo estacionado (min)", 25);
        $report_txt .= "Cantidad a pagar\n";

        $resident_fee = 0.05;

        $residents = DB::table('vehicles')
            ->select('license_plate', DB::raw('SUM(cumulative_time) AS aggregated') )
            ->where('type', 2)
            ->groupBy('license_plate')
            ->get();

        foreach($residents as $resident) {

            $total_fee = $resident->aggregated * $resident_fee;

            echo str_pad($resident->license_plate, 25) . str_pad($resident->aggregated, 25) . $total_fee . "\n";

            // Report contents creation
            $report_txt .= str_pad($resident->license_plate, 25) . str_pad($resident->aggregated, 25) . $total_fee . "\n";
        }

        Storage::disk('public')->put($report_name, $report_txt);
    }
}