<?php

namespace App\Http\Controllers\UserRole;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class UserRoleController extends Controller
{
    public function getUserRole($id)
    {
        $user_role = UserRole::find($id);
        if (!$user_role) return response()->json(['message' => 'Data Not Found'], 404);
        return response()->json($user_role, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'user_id' => ['required'],
            'role_id' => ['required']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'uuid' => Uuid::uuid4(),
            'user_id' => $request->input('user_id'),
            'role_id' => $request->input('role_id')
        ];

        UserRole::create($input);
        return response()->json(['message' => 'Create UserRole Success'], 201);
    }
    public function update(Request $request, $id)
    {
        $user_role = UserRole::find($id);
        if (!$user_role) return response()->json(['message' => 'Data Not Found'], 404);

        $validator = Validator::make($request->input(), [
            'user_id' => ['required'],
            'role_id' => ['required']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'user_id' => $request->input('user_id'),
            'role_id' => $request->input('role_id')
        ];

        $user_role->fill($input);
        $user_role->save();
        return response()->json(['message' => 'Update UserRole Success'], 200);
    }

    public function delete($id)
    {
        $user_role = UserRole::find($id);
        if (!$user_role) return response()->json(['message' => 'Data Not Found'], 404);
        try {
            DB::beginTransaction();
            $user_role->delete();
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            if ($Err->getCode() == 23000) return response()->json(['message' => 'Integrity constraint violation: 1451 Cannot delete or update a parent row']);
            return response()->json(['message' => 'Please Contact Administration'], 500);
        }
        return response()->json(['message' => 'Delete UserRole Success'], 200);
    }
}
