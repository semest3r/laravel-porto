<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 10;
        $categories = Category::query()->with('groupCategory')->when($request->input('search'), function ($query, $search) {
            $query->where('name_category', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($categories, 200);
    }

    public function getCategory($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Data Not Found'], 404);
        return response()->json($category, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name_category' =>  ['required', 'unique:App\Models\Category,name_category'],
            'code_category' => ['required', 'unique:App\Models\Category,code_category'],
            'group_category' => ['required']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'uuid' => Uuid::uuid4(),
            'name_category' => $request->input('name_category'),
            'code_category' => $request->input('code_category'),
            'group_category_id' => $request->input('group_category')['id'],
        ];
        Category::create($input);
        return response()->json(['message' => 'Create Category Success'], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Data Not Found'], 404);

        $validator = Validator::make($request->input(), [
            'name_category' =>  ['required', Rule::unique('categories')->ignore($category->id)],
            'code_category' => ['required', Rule::unique('categories')->ignore($category->id)],
            'group_category' => ['required']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'name_category' => $request->input('name_category'),
            'code_category' => $request->input('code_category'),
            'group_category_id' => $request->input('group_category')['id'],
        ];
        $category->fill($input);
        $category->save();
        return response()->json(['message' => 'Update Category Success'], 200);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Data Not Found'], 404);

        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            if ($Err->getCode() == 23000) return response()->json(['message' => 'Integrity constraint violation: 1451 Cannot delete or update a parent row'], 400);
            return response()->json(['message' => 'Please Contact Administration'], 500);
        }

        return response()->json(['message' => 'Delete Category Success'], 200);
    }
}
