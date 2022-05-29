<?php

namespace App\Http\Controllers;

use App\Models\UserTypes as Types;
use Illuminate\Http\Request;

class TypesController extends Controller
{
    public function index() {
        $types = Types::get()->toJson(JSON_PRETTY_PRINT);
        return response($types, 200);
    }

    public function store(Request $request) {

        $request->validate([
            'type_name' => 'required|string|unique:user_types,type_name'
        ]);

        return Types::create($request->all());
        
    }

    /**
     * Search for a user type
     * @param str $type_name
     */

    public function search($type_name) {
        
        return Types::where('type_name', $type_name)->get()->toJson(JSON_PRETTY_PRINT);
    }

    public function update(Request $request, $type_name) {
        $type = Types::where('type_name', $type_name)->get();
        $type->update($request->all());

        return $type;
    }

    public function destroy($type_name) {
        $type = Types::where('type_name', $type_name)->get();
        return Types::destroy($id);
    }
}
