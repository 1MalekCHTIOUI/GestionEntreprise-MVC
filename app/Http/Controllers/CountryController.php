<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return response()->json($countries);
    }

    public function show($id)
    {
        $country = Country::with('states')->findOrFail($id);
        return response()->json($country);
    }
}
