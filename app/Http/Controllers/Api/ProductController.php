<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $product = Product::all();

        if($product->count() > 0) {
            return response()->json([
                'status' => 200,
                'product' => $product
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'product' => 'No Product Found'
            ],404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required',
            'productname' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if($validator->fails()){


            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ],402);
        }else{

            $product = Product::create([
                'image' => $request->image,
                'productname' => $request->productname,
                'description' => $request->description,
                'price' => $request->price
            ]);

            if($product){
               return response()->json([
                'status' => 200,
                'message' => 'Product Created Successfully'
               ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ],500);
            }
        }
    }
}