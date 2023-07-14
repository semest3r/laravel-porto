<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 15;
        $users = User::query()->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($users, 200);
    }

    public function editUserStatus(Request $request, $id)
    {
        $user = User::where('uuid', $id)->firstOrFail();
        if ($user->is_active) {
            $user->is_active = false;
            $user->save();
        } else {
            $user->is_active = true;
            $user->save();
        }
        return response()->json(['message' => 'Success Update'], 200);
    }
}
