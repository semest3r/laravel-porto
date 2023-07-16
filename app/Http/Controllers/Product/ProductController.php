<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Auditrails;
use App\Models\Product;
use App\Models\productImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    public function getProduct($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json([], 404);
        return response()->json($product, 200);
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_product' => ['required', 'unique:App\Models\Product,name_product'],
            'code_product' => ['required', 'unique:App\Models\Product,code_product'],
            'category' => ['required'],
            'img_uploads' => ['required', 'max:2048'],
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);
        if (count($request->file('img_uploads')) != 3) {
            return response()->json([
                'errors' => [
                    'img_uploads' => "Uploaded image must be equal to 3"
                ]
            ]);
        }
        try {
            DB::beginTransaction();
            $user = $request->user();
            Auditrails::create([
                'name_user' => $user->name,
                'user_id' => $user->id,
                'activity' => 'Create Product'
            ]);

            $inputProduct = [
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'name_product' => $request->input('name_product'),
                'code_product' => $request->input('code_product'),
                'created_by' => $user->id,
                'category_id' => $request->input('category')
            ];
            $createdProduct = Product::create($inputProduct);

            $allowedfileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
            $inputProductImg = [];
            foreach ($request->file('img_uploads') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $check = in_array($ext, $allowedfileExtension);
                $filename = Uuid::uuid4();
                if ($check) {
                    array_push($inputProductImg, [
                        'uuid' => $filename,
                        'product_id' => $createdProduct->id,
                        'filename' => $filename . '.' . $ext,
                        'path' => 'public/' . $ext . '/' . $filename . '.' . $ext,
                        'file_type' => $ext,
                        'created_at' => now()->toDateTimeString(),
                        'updated_at' => now()->toDateTimeString()
                    ]);
                } else {
                    return response()->json([
                        'errors' => [
                            'img_uploads' => 'File Only PDF/JPG/JPEG/PNG Allowed'
                        ]
                    ], 422);
                }
            }
            productImg::insert($inputProductImg);

            foreach ($request->file('img_uploads') as $i => $img) {
                Storage::putFileAs('public/' . $inputProductImg[$i]['file_type'], $img, $inputProductImg[$i]['filename']);
            }
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            return response()->json(['message' => $Err->getMessage()], 422);
        }
        return response()->json(['message' => 'Create Product Success'], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Data Not Found'], 404);

        $validator = Validator::make($request->all(), [
            'name_product' => ['required'],
            'code_product' => ['required'],
            'category' => ['required'],
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $input = [
            'name_product' => $request->input('name_product'),
            'code_product' => $request->input('code_product'),
            'category_id' => $request->input('category'),
        ];

        $user = $request->user();
        Auditrails::create([
            'name_user' => $user->name,
            'user_id' => $user->id,
            'activity' => 'Edit ProductId' . $product->id
        ]);
        $product->fill($input);
        $product->save();

        return response()->json(['message' => 'Update Product Success'], 200);
    }

    public function delete(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Data Not Found'], 404);

        try {
            DB::beginTransaction();

            $user = $request->user();
            Auditrails::create([
                'name_user' => $user->name,
                'user_id' => $user->id,
                'activity' => 'Delete ProductId' .  $product->id
            ]);

            $file_delete = [];
            foreach ($product->productImg as $v) {
                $img_exist = Storage::exists($v->path);
                if ($img_exist) {
                    array_push($file_delete, $v->path);
                }
                $v->delete();
            }
            $product->delete();
            Storage::delete($file_delete);
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            if ($Err->getCode() == 23000) return response()->json(['message' => 'Integrity constraint violation: 1451 Cannot delete or update a parent row']);
            return response()->json(['message' => 'Please Contact Administration'], 500);
        }
        return response()->json(['message' => 'Delete Product Success']);
    }

    public function addImg(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Data Not Found'], 404);

        if (count($product->productImg) >= 3) return response()->json(['message' => "Uploaded image can't more than 3"], 400);

        $validator = Validator::make($request->all(), [
            'img_upload' => ['required', 'max:2048']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $filename = Uuid::uuid4();
        $allowedfileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
        $imgUpload = $request->file('img_upload');
        $ext = strtolower($imgUpload->getClientOriginalExtension());
        $check = in_array($ext, $allowedfileExtension);
        if (!$check) return response()->json(['errors' => ['img_upload' => 'File Only PDF/JPEG/PNG Allowed']]);
        try {
            DB::beginTransaction();
            $user = $request->user();
            Auditrails::create([
                'name_user' => $user->name,
                'user_id' => $user->id,
                'activity' => 'AddImg On ProductId' . $product->id
            ]);
            $input = [
                'uuid' => $filename,
                'product_id' => $product->id,
                'filename' => $filename . '.' . $ext,
                'path' => 'public/' . $ext . '/' . $filename . '.' . $ext,
                'file_type' => $ext,
            ];
            $create = ProductImg::create($input);
            Storage::putFileAs('public/' . $input['file_type'], $imgUpload, $input['filename']);
            DB::commit();
        } catch (\Exception $Err) {
            DB::rollBack();
            return response()->json(['message' => $Err->getMessage(), 422]);
        }

        return response()->json(['message' => 'Add Image Success'], 201);
    }

    public function updateImg(Request $request, $id)
    {
        $productImg = productImg::find($id);
        if (!$productImg) return response()->json(['message' => 'Data Not Found'], 404);
        $validator = Validator::make($request->all(), [
            'img_upload' => ['required', 'max:2048']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $old_file = $productImg->path;
        $old_file_exist = Storage::exists($old_file);
        $filename = Uuid::uuid4();
        $allowedfileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
        $imgUpload = $request->file('img_upload');
        $ext = strtolower($imgUpload->getClientOriginalExtension());

        $check = in_array($ext, $allowedfileExtension);
        if (!$check) return response()->json(['errors' => ['img_upload' => 'File Only PDF/JPEG/PNG Allowed']]);

        $input = [
            'filename' => $filename . '.' . $ext,
            'path' => 'public/' . $ext . '/' . $filename . '.' . $ext,
            'file_type' => $ext,
        ];
        try {
            DB::beginTransaction();
            $user = $request->user();
            Auditrails::create([
                'name_user' => $user->name,
                'user_id' => $user->id,
                'activity' => 'AddImg On ProductImgId' . $productImg->id
            ]);

            $productImg->fill($input);
            $productImg->save();
            Storage::putFileAs('public/' . $input['file_type'], $request->file('img_upload'), $input['filename']);
            if ($old_file_exist) Storage::delete($old_file);
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json(['message' => $err->getMessage()], 422);
        }
        return response()->json(['message' => 'Update Success'], 200);
    }
}
