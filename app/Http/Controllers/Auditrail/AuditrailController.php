<?php

namespace App\Http\Controllers\Auditrail;

use App\Http\Controllers\Controller;
use App\Models\Auditrails;
use Illuminate\Http\Request;

class AuditrailController extends Controller
{
    public function getAuditrails(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 15;
        $subscribers = Auditrails::query()->when($request->input('search'), function ($query, $search) {
            $query->where('name_user', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($subscribers, 200);
    }
}
