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
     
            $product = new Product();
            $product->productname = $request->input('productname');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
  
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time(). '.' .$extension;
                $file->move('uploads/product/', $filename);
                $product->image = $filename;
            } else {
                $product->image = '';
            }
              
            if ($product->save()) { 
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Created Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ], 500);
            }
        
    }

    public function show($id) {
        $product = Product::find($id);

        if($product){

            return response()->json([
                'status' => 200,
                'product' => $product
            ],200);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'No Product Found'
            ],404);
        }
    }

    public function edit($id) {
        $product = Product::find($id);
        if($product) {

            return response()->json([
                'status' => 200,
                'product' => $product
            ],200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'No Product Found'
            ],400);
        }
    }

    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
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
            
            $product = Product::find($id);

            if($product){

                $product->update([
                    'image' => $request->image,
                    'productname' => $request->prodcutname,
                    'description' => $request->description,
                    'price' => $request->price
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Product Updated Successfully'
                ],200);
            }else{

                return response()->json([
                    'status' => 404,
                    'message' => 'No Product Found'
                ],404);
            }
        }
    }

    public function destroy($id){
        $product = Product::find($id);
        if($product){

            $product->delete();

            return response()->json([
                'status' => 200,
                'message' => "Product Deleted Successfully"
            ],200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'No Product Found'
            ],404);
        }
    }
}
