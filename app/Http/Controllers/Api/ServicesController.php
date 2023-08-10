<?php

namespace App\Http\Controllers\Api;

use App\Models\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function index(){
        $service = Services::all();

        if($service->count() > 0) {
            return response()->json([
                'status' => 200,
                'service' => $service
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
            'description' => 'required|string|max:191',
            'price' => 'required|integer|max:191'
        ]);

        if($validator->fails()) {

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ], 402);
        }else{

        $services = new Services();
        $services->description = $request->input('description');
        $services->price = $request->input('price');
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). '.' .$extension;
            $path = $file->store('');
            $services->image = $path;
        } else {
            $services->image = '';
        }
        
        if ($services->save()) { 
            return response()->json([
                'status' => 200,
                'message' => 'Services Created Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong'
            ], 500);
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
        
        if ($services) {
            // Delete the associated image from storage if it exists
            if (!empty($services->image)) {
                Storage::delete($services->image);
            }
    
            $services->delete();
    
            return response()->json([
                'status' => 200,
                'message' => "Services Deleted Successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Services Found'
            ], 404);
        }
    }
}
