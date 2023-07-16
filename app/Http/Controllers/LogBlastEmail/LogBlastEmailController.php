<?php

namespace App\Http\Controllers\LogBlastEmail;

use App\Http\Controllers\Controller;
use App\Models\LogBlastEmail;
use Illuminate\Http\Request;

class LogBlastEmailController extends Controller
{
    public function getLogBlastEmail(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 15;
        $logBlastEmail = LogBlastEmail::query()->when($request->input('search'), function ($query, $search) {
            $query->where('email', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($logBlastEmail, 200);
    }
}
