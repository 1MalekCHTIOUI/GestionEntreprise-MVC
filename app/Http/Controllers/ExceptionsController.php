<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExceptionsController extends Controller
{
    public function getExceptionsPaginate()
    {
        $exceptions = DB::table('logs')->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        return response()->json($exceptions, 200);
    }




    public function search(Request $request)
    {
        $exceptions = DB::table('logs')->where('message', 'like', '%' . $request->search_string . '%')
            ->orWhere('level', 'like', '%' . $request->search_string . '%')
            ->orWhere('context', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination.perPage'));

        if ($exceptions->total() >= 1) {
            return response()->json(
                $exceptions,
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
        $sortDirection = $request->get('sort', 'desc');
        $exceptions = DB::table('logs')->orderBy('created_at', $sortDirection)->paginate(config('global.pagination.perPage'));
        return response()->json($exceptions, 200);
    }
}
