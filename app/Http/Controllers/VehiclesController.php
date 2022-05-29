<?php

namespace App\Http\Controllers;

use App\Models\Vehicles as ModelsVehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    /**
     * Lists all vehicles
     * 
     */
    public function index() {
        $vehicles = ModelsVehicles::get()->toJson(JSON_PRETTY_PRINT);
        return response($vehicles, 200);
    }

    /**
     * Adds a new vehicle
     * @param Request $request
     * @param string $type 
     * 
     */
    public function store(Request $request, $typeName) {

        $typeName = preg_replace('/[^A-Za-z\-]/', '', $typeName);

        $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:7'
        ]);

        switch($typeName){
            case 'official':
                $type = '1';
                break;
            case 'resident':
                $type = '2';
                break;
        }

        // Add the type of vehicle to request and create it
        return ModelsVehicles::create( array_merge($request->all(), ['type' => $type] ) );

    }

    /**
     * Retrieves one record
     * 
     */
    public function show($id) {
        return ModelsVehicles::find($id);
    }

    /**
     * Updates vehicle record
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id) {
        $vehicle = ModelsVehicles::find($id);
        $vehicle->update($request->all());

        return $vehicle;
    }

    /**
     * Resets a vehicle's cumulative time
     * 
     */
    public function resetCounter() {
        ModelsVehicles::where('cumulative_time','<>', 0)
        ->update(['cumulative_time' => 0]);

        return $this->index();
    }

    /**
     * Deletes one record
     * @param int $id
     */
    public function destroy($id) {

        return ModelsVehicles::destroy($id);
    }

    /**
     * Search for a license plate
     * @param str $license_plate
     */

    public function search($license_plate) {
        
        return ModelsVehicles::where('license_plate', 'like', '%'.$license_plate.'%')->get()->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Update residents cumulative time on parking exit
     * @param int $id
     * @param int $time
     */

    public function addTime($id, $time) {
        
        $vehicle = ModelsVehicles::find($id);
        $vehicle->update([
            'cumulative_time' => $vehicle->cumulative_time + $time
        ]);

        return $vehicle;
    }
}
