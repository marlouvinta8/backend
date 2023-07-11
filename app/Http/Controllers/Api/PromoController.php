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
        'image' => 'required|image|max:2048', // Paggamit ng validation rules
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }
    
    // I-save ang larawan
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('public/images'); // Mag-upload ng larawan sa storage
        
        $promo = Promo::create([
            'image' => $imagePath,
        ]);
        
        return response()->json([
            'status' => 200,
            'message' => 'Promo Created Successfully',
            'image_path' => $imagePath,
        ], 200);
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

    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'promoname' => 'required|string|max:191',
            'description' => 'required|string|max:191',
            'price' => 'required|numeric',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 404,
                'message' => $validator->messages()
            ],404);
        }else{
            $promo = Promo::find($id);

            if($promo){

                $promo->update([
                    'image' => $request->image,
                    'productname' => $request->productname,
                    'description' => $request->description,
                    'price' => $request->price
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Promo Updated Successfully'
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'No Member Found'
                ],404);
            }
        }
    }

    public function destroy($id){
        $promo = Promo::find($id);
        if($promo){

            $promo->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Promo Deleted Successfully'
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No such Promo Found!'
            ],404);
        }
    }
}
