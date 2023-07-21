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
        $promo = new Promo();
        $promo->promoname = $request->input('promoname');
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). '.' .$extension;
            $path = $file->store('');
            $promo->image = $path;
        } else {
            $promo->image = '';
        }
        
        if ($promo->save()) { 
            return response()->json([
                'status' => 200,
                'message' => 'Promo Created Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong'
            ], 500);
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
