<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index(){
        $cart = Cart::all();

        if($cart->count() >0) {
            return response()->json([
                'status' => 200,
                'cart' => $cart
            ], 200);
        }
    }

    public function store($id, $type, Request $request){
        $product = null;
        if ($request->productId){
            $product = Product::findOrFail($request->productId);
        } elseif ($request->serviceId) {
            $product = Service::findOrFail($request->serviceId);    
        }
    
        if ($product == null) {
            return response()->json([
                'status' => 500,
                'message' => 'Not Found'
            ]);
        }
        $isExist = Cart::where('pid', $product->id)
                        ->where('user_id', auth()->id())
                        ->first();
    
        $carts = Cart::where('user_id', auth()->id())->get();
    
        $itemQuantity = 0;
        foreach ($carts as $cart){
            if ($cart->name == $product->name){
                $itemQuantity = $cart->quantity;
                break;
            }
        }
    
        if($product->quantity < $itemQuantity || $product->quantity < 1){
            return response()->json([
                'status' => 400,
                'message' => "Out of Stock"
            ]);
        }
    
        if($isExist){
            return response()->json([
                'status' => 400,
                'message' => 'Item is already add to cart'
            ]);
        } else {
            $quantity = $request->input('quantity', 1);
            $total = $product->price * $quantity;
            $carts = Cart::updateOrCreate([
                'pid' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'total' => $total,
                'user_id' => auth()->id(),
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => 'Success'
            ]);
        }    
    }

    public function update(Request $request, Cart $cart){
        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }

    public function destroy() {

        $cart->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Item successfully cancel'
        ]);
    }
}
