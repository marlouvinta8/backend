<?php

namespace App\Http\Controllers\api;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //para mabasa lahat ng admin account
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

<<<<<<< HEAD
   // para masave ang ginawang admin account
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:191',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){

             return response()->json([
                'status' => 500,
                'message' => "Something Went Wrong!"
            ],500);
        }else{

            return response()->json([
                'status' => 200,
                'admin' => $validator->messages()
            ],200);
        }
        }
     
=======
    //para masave ang ginawang admin account
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:191',
            'password' => 'required|string|min:5',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => "Something Went Wrong!"
            ],500);

            
        
            }else{return response()->json([
                'status' => 402,
                'admin' => $validator->messages()
            ],402);
        }
     }
>>>>>>> 2a14a829dacdf2f0d3379eeca2d660a1af05a4bd

     //kapag mageedit ng account
     public function edit($id){
        $admin = Admin::find($id);
        if($admin){
            return response()->json([
                'status' => 200,
                'admin' => $admin
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Admin Found'
            ],404);
        }
     }

     public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:191',
            'password' => 'required|string|min:5',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ],402);
        }else{
            $member = Admin::find($id);

            if($member){

                $member->update([
                    'username' => $request->username,
                    'password' => $request->password,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Admin Updated Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "No such Admin Found!"
                ],404);
            }
        }
     }

     private function totalMember(){
        $totalMember = Member::count();

        if($totalMember){
            return response()->json([
                'status' => 200,
                'message' => $totalMember
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Member As Of Now'
            ],404);
        }
     }
}
