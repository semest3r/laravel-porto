<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $users = User::query()->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate();
        return response()->json($users, 200);
    }
}
