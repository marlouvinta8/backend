<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    //para makita lahat ng member
    public function index(){
        $member = Member::all();

        if($member->count() >0) {
            return response()->json([
                'status' => 200,
                'member' => $member
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'member' => 'No Members Found!'
            ], 404);
        }
    }

    // di makakagawa ng account kapag may kulang sa finill-upan o may di nasunod sa hinihinging details
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'username' => 'required|string|max:191|unique:member',
            'password' => 'required|string|max:191',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ], 402);
        }else{

            //para sa paggawa ng bagong account ng member
            $member = Member::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if($member){

                return response()->json([
                    'status' => 200,
                    'message' => "Member Created Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => "Something Went Wrong!"
                ],500);
            }
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:5',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $credentials = $request->only('username', 'password');
        $member = Member::where('username', $credentials['username'])
                      ->where('password', $credentials['password'])
                      ->first();
        
        if ($member) {
           // $token = $admin->createToken('authToken')->plainTextToken;
        
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                //'token' => $token,
                'member' => $member,
            ], 200);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }


    //para makita ang member dipende sa id na inilagay
    public function show($id){
        $member = Member::find($id);
        if($member){

            return response()->json([
                'status' => 200,
                'member' => $member
            ],200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => "No such Member Found!"
            ],404);
        }
    }

    //para maisearch ang id ng member na ieedit
    public function edit($id){
        $member = Member::find($id);
        if($member){

            return response()->json([
                'status' => 200,
                'member' => $member
            ],200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => "No such Member Found!"
            ],404);
        }
    }
    //requirements para makapag update ng account ng member
    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'username' => 'required|string|max:191',
            'password' => 'required|string|max:191',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status' => 402,
                'errors' => $validator->messages()
            ], 402);
        }else{
            $member = Member::find($id);

            if($member){

                $member->update([
                    'fullname' => $request->fullname,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => $request->password,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Member Updated Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "No such Member Found!"
                ],404);
            }
        }
    }

    //pedeng tanggalin ang account ng member
    public function destroy($id){
        $member = Member::find($id);
        if($member){

            $member->delete();
            return response()->json([
                'status' => 200,
                'message' => "Member Deleted Successfully"
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "No such Member Found!"
            ],404);
        }
    }

    

}

