<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
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

    public function store(Request $request) {
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
            ], 422);
        }else{

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
            ], 422);
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

