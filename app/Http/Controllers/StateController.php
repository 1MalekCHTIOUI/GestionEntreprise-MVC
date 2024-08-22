<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::all();
        return response()->json($states);
    }

    public function show($id)
    {
        $state = State::findOrFail($id);
        return response()->json($state);
    }

    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)->get();
        return response()->json($states);
    }
}
