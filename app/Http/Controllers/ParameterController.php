<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParameterController extends Controller
{
    public function index()
    {
        $parameters = Parameter::all();
        return response()->json($parameters);
    }



    public function show($id)
    {
        $parameter = Parameter::findOrFail($id);
        return response()->json($parameter);
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'timbre_fiscale' => 'nullable|string',
            'tva' => 'nullable|string',
            'cachet' => 'nullable|file',
            'logo' => 'nullable|file',
            'titre' => 'nullable|string',
            'tel' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
            'numero_fiscal' => 'nullable|string',
        ]);


        if ($request->hasFile('cachet')) {
            $fileName = $request->file('cachet')->getClientOriginalName();

            $request->file('cachet')->storeAs('assets/images/parameters', $fileName, 'public');
            $data['cachet'] = $fileName;
        }

        if ($request->hasFile('logo')) {
            $fileName = $request->file('logo')->getClientOriginalName();

            $request->file('logo')->storeAs('assets/images/parameters', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        $parameter = Parameter::create($data);
        return response()->json($parameter, 201);
    }

    public function update(Request $request, $id)
    {
        $parameter = Parameter::findOrFail($id);
        $data = $request->validate([

            'timbre_fiscale' => 'nullable|string',
            'tva' => 'nullable|string',
            'fodec' => 'nullable|string',
            'cachet' => 'nullable|file',
            'logo' => 'nullable|file',
            'titre' => 'nullable|string',
            'tel' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
            'numero_fiscal' => 'nullable|string',
        ]);



        if ($request->hasFile('cachet')) {
            // if ($parameter->cachet) {
            //     Storage::disk('public')->delete($parameter->cachet);
            // }
            $fileName = $request->file('cachet')->getClientOriginalName();

            $request->file('cachet')->storeAs('assets/images/parameters', $fileName, 'public');
            $data['cachet'] = $fileName;
        }

        if ($request->hasFile('logo')) {
            // if ($parameter->logo) {
            //     Storage::disk('public')->delete($parameter->logo);
            // }
            $fileName = $request->file('logo')->getClientOriginalName();

            $request->file('logo')->storeAs('assets/images/parameters', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        $parameter->update($data);
        return response()->json($parameter);
    }

    public function destroy($id)
    {
        $parameter = Parameter::findOrFail($id);
        $parameter->delete();
        return response()->json(null, 204);
    }
}
