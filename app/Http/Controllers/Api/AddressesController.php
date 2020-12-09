<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
    public function getCities()
    {
        return response()->json(City::all());
    }

    public function getStreets(City $city)
    {
        return response()->json($city->streets);
    }
}
