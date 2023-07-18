<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\GroupCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class GroupCategoryController extends Controller
{
    public function getGroupCategories(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 10;
        $groupCategories = GroupCategory::query()->when($request->input('search'), function ($query, $search) {
            $query->where('name_group_category', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($groupCategories, 200);
    }

    public function getGroupCategory($id)
    {
        $groupCategory = GroupCategory::find($id);
        if (!$groupCategory) return response()->json(['message' => 'Data Not Found'], 404);
        return response()->json($groupCategory, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name_group_category' =>  ['required', 'unique:App\Models\GroupCategory,name_group_category'],
            'code_group_category' => ['required', 'unique:App\Models\GroupCategory,code_group_category'],
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'uuid' => Uuid::uuid4(),
            'name_group_category' => $request->input('name_group_category'),
            'code_group_category' => $request->input('code_group_category'),
        ];
        GroupCategory::create($input);
        return response()->json(['message' => 'Create Group Category Success'], 201);
    }

    public function update(Request $request, $id)
    {
        $groupCategory = GroupCategory::find($id);
        if (!$groupCategory) return response()->json(['message' => 'Data Not Found'], 404);

        $validator = Validator::make($request->input(), [
            'name_group_category' =>  ['required', Rule::unique('group_categories')->ignore($groupCategory->id)],
            'code_group_category' => ['required', Rule::unique('group_categories')->ignore($groupCategory->id)],
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'name_group_category' => $request->input('name_group_category'),
            'code_group_category' => $request->input('code_group_category'),
        ];
        $groupCategory->fill($input);
        $groupCategory->save();
        return response()->json(['message' => 'Update Category Success'], 200);
    }

    public function delete($id)
    {
        $groupCategory = GroupCategory::find($id);
        if (!$groupCategory) return response()->json(['message' => 'Data Not Found'], 404);
        try {
            DB::beginTransaction();
            $groupCategory->delete();
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            if ($Err->getCode() == 23000) return response()->json(['message' => 'Integrity constraint violation: 1451 Cannot delete or update a parent row'], 400);
            return response()->json(['message' => 'Please Contact Administration'], 500);
        }
        return response()->json(['message' => 'Delete Category Success'], 200);
    }
}
