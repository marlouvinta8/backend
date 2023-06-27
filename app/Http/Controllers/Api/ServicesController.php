<?php

namespace App\Http\Controllers\Api;

use App\Models\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function index(){
        $services = Services::all();

        if($services->count() > 0) {
            return response()->json([
                'status' => 200,
                'services' => $services
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Services'
            ], 404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        if($validator()->fails()) {

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ],402);
        }else{

            $services = Services::create([
                'image' => $request->image,
                'description' => $request->description,
                'price' => $request->price
            ]);

            if($services){

                return response()->json([
                    'status' => 200,
                    'message' => 'Services Created Successfully'
                ],200);
            }else{

                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ],500);
            }
        }
    }

    public function show($id){
        $services = Services::find($id);

        if($services){

            return response()->json([
                'status' => 200,
                'services' => $services
            ],200);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'No Services Found'
            ],404);
        }
    }

    public function edit($id) {
        $services = Services::find($id);
        if($services){

            return response()->json([
                'status' => 200,
                'services' => $services
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Services Found'
            ],404);
        }
    }

    public function destroy($id){
        $services = Services::find($id);
        if($services){

            $services->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Services Deleted Successfully'
            ],200);
        }else{
            return response()->json([
                'status' => '404',
                'message' => 'No such Services Found'
            ],404);
        }
    }
}
