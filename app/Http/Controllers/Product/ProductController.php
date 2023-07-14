<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getProduct($id){
        $product = Product::find($id);
        if(!$product) return response()->json([], 404);
        return response()->json($product, 200);
    }

    public function getProducts(){
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function create(Request $request){
        $validator = Validator::make($request->input(), [
            ''
        ]);

        if($validator->fails()) return response()->json($validator->errors(), 422);
        
        
        
    }
}
