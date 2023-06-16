<?php

namespace App\Http\Controllers\api;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
        $admin = Admin::all();
        
        if($admin->count() >0) {
            return response()->json([
                'status' => 200,
                'admin' => $admin 
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'admin' => 'No Admin Found'
            ],404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:191',
            'password' => 'required|string|min:5',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 402,
                'admin' => $validator->messages()
            ],402);
        }else{
            $admin = Admin::create([
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if($admin){

                return response()->json([
                    'status' => 200,
                    'message' => "Admin Created Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => "Something Went Wrong!"
                ],500);
            }
        }
     }

     public function edit($id){
        
     }
}
