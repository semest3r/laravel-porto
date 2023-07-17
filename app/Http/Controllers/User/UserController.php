<?php

namespace App\Http\Controllers\User;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Auditrails;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $user = User::where('uuid', $id)->first();
        if (!$user) return response()->json(['message' => 'Data Not Found'], 404);
        $checkMe = $request->user();
        Auditrails::create([
            'name_user' => $checkMe->name,
            'user_id' => $checkMe->id,
            'activity' => 'Update Status UserId ' . $user->id
        ]);
        $user->is_active = $user->is_active ? false : true;
        $user->save();
        return response()->json(['message' => 'Success Update'], 200);
    }

    public function csv()
    {
        return Excel::download(new UsersExport, 'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function pdf()
    {
        $data = User::all();
        $pdf = Pdf::loadView('usersPdf', ['users' => $data]);
        return $pdf->download();
    }
}
