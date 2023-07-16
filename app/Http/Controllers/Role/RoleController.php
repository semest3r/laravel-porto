<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class RoleController extends Controller
{
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function getRole($id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Data Not Found'], 404);
        return response()->json($role, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name_role' => ['required', 'unique:App\Models\Role,name_role'],
            'code_role' => ['required', 'size:4', 'unique:App\Models\Role,code_role']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'uuid' => Uuid::uuid4(),
            'name_role' => $request->input('name_role'),
            'code_role' => $request->input('code_role'),
        ];
        Role::create($input);
        return response()->json(['message' => 'Create Role Success'], 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Data Not Found'], 404);

        $validator = Validator::make($request->input(), [
            'name_role' => ['required', Rule::unique('roles')->ignore($role->id)],
            'code_role' => ['required', 'size:4', Rule::unique('roles')->ignore($role->id)]
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'name_role' => $request->input('name_role'),
            'code_role' => $request->input('code_role'),
        ];
        $role->fill($input);
        $role->save();
        return response()->json(['message' => 'Update Role Success'], 200);
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Data Not Found'], 404);

        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            if ($Err->getCode() == 23000) return response()->json(['message' => 'Integrity constraint violation: 1451 Cannot delete or update a parent row']);
            return response()->json(['message' => 'Please Contact Administration'], 500);
        }
        return response()->json(['message' => 'Delete Role Success'], 200);
    }
}
