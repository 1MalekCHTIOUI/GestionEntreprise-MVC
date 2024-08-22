<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use Illuminate\Http\Request;

class HistoriquesController extends Controller
{
    function getHistoriquesPaginate()
    {
        $historiques = Historique::orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        return response()->json($historiques, 200);
    }


    public function search(Request $request)
    {
        $historiques = Historique::where('table', 'like', '%' . $request->search_string . '%')
            ->orWhere('action', 'like', '%' . $request->search_string . '%')
            ->orWhere('id_record', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination.perPage'));

        if ($historiques->count() >= 1) {
            return response()->json(
                $historiques,
                200
            );
        } else {
            return response()->json(
                'Nothing found',
                404
            );
        }
    }


    public function sort(Request $request)
    {
        $sortDirection = $request->get('sort', 'asc');
        $historiques = Historique::orderBy('created_at', $sortDirection)->paginate(config('global.pagination.perPage'));
        return response()->json(
            $historiques,
            200
        );
    }
}
