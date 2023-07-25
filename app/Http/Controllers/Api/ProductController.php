<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
        $product->quantity = $request->input('quantity');
        $product->link = $request->input('link');
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). '.' .$extension;
            $path = $file->store('');
            $product->image = $path;
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

    public function addtocart(Request $request){
        $product = Product::find($request->input('id'));

        if($product->quantity < $request->input('quantity')) {
            return response()->json([
                'status' => 404,
                'message' => 'Not Enough Stock'
            ], 500);
        }

        $cart = Cart::create([
            'pid' => $product->id,
            'name' => $product->productname,
            'image' => $product->image,
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $request->input('quantity'),
            'total' => $product->price * $request->input('quantity')
        ]);

        
        $product->quantity -= $request->input('quantity');
        $product->save();


        if($cart){

            return response()->json([
                'status' => 200,
                'message' => "Added To Cart"
            ],200);
        }else{
            return response()->json([
                'status' => 500,
                'message' => "Something Went Wrong!"
            ],500);
        }
    }

    public function getcart()
    {
        $cartItems = Cart::all();
        return response()->json($cartItems);
    }

    public function stockin(Request $request, $id){
        $request->validate([
            'quantity' => 'required'
        ]);

        $product = Product::findOrfail($id);
        $addStock = $product->quantity + $request->quantity;
        $product->update(['quantity' => $addStock]);

        return response()->json([
            'status' => 200,
            'message' => 'Stock Added',
            'data' => $product,
        ],200);
     }


       public function stockout(Request $request, $id){
        $request->validate([
            'quantity' => 'required'
        ]);

        $product = Product::findOrfail($id);
        $minusStock = $product->quantity - $request->quantity;
        $product->update(['quantity' => $minusStock]);

        return response()->json([
            'status' => 200,
            'message' => 'Stock Out',
            'data' => $product,
        ],200);
     }

    public function getimage($filename){
        $path = storage_path('app/public/'. $filename);

        if(!Storage::exists($path)){
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response::make($file, 200)->header("Content-Type", $type);
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
            'price' => 'required|numeric',
            'quantity' => 'required|numeric'
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
                    'price' => $request->price,
                    'quantity' => $request->quantity
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
