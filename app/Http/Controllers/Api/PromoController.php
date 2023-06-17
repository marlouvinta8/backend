<?php

namespace App\Http\Controllers\Api;

use App\Models\Promo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    public function index(){
        $promo = Promo::all();
        
        if($promo->count() > 0) {
            return response()->json([
                'status' => 200,
                'promo' => $promo
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'promo' => 'No Promo as of Today'
            ],404);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'promoname' => 'required|string|max:191',
            'description' => 'required|string|max:191',
            'price' => 'required|numeric',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ], 402);
        }else{

            $promo = Promo::create([
                'image' => $request->image,
                'promoname' => $request->promoname,
                'description' => $request->description,
                'price' => $request->price
            ]);

            if($promo){

                return response()->json([
                    'status' => 200,
                    'message' => 'Promo Created Successfully'
                ],200);
            }else{

                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong!'
                ], 500);
            }
        }
    }

    public function show($id){
        $promo = Promo::find($id);

        if($promo){
            
            return response()->json([
                'status' => 200,
                'promo' => $promo
            ],200);
        }else{

            return response()->json([
                'status' => 404,
                'message' => "No such Promo Found"
            ], 404);
        }
    }
}
