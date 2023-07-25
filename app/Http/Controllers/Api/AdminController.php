<?php

namespace App\Http\Controllers\api;

use session;
use App\Models\Admin;
use App\Models\Sales;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

//para masave ang ginawang admin account
    public function store(Request $request){
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
        $admin = Admin::where('username', $credentials['username'])
                      ->where('password', $credentials['password'])
                      ->first();
        
        if ($admin && !Auth::attempt($credentials)) {
           // $token = $admin->createToken('authToken')->plainTextToken;
        
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                //'token' => $token,
                'admin' => $admin,
            ], 200);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
            ], 401);
        }


        // - eto yung gumagana
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|max:191',
    //         'password' => 'required|string|min:5',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'status' => 500,
    //             'message' => "Incorrect Username or Password!"
    //         ],500);

            
        
    //         }else{return response()->json([
    //             'status' => 200,
    //             'admin' => $validator->messages()
    //         ],200);
    //     }
    // }

        // $validator = Validator::make($request->all(), [
        //     'username' => 'required|string',
        //     'password' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Validation Error',
        //         'errors' => $validator->errors(),
        //     ], 400);
        // }

        // $credentials = $request->only('username', 'password');

        // if (Auth::attempt($credentials)) {
        //     $admin = Auth::admin();
        //     $token = $admin->createToken('authToken')->plainTextToken;

        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Login successful',
        //         'token' => $token,
        //         'admin' => $admin,
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => 401,
        //         'message' => 'Invalid credentials',
        //     ], 401);
        // }
    }
        // $validator = Validator::make($request->all(), [
        //     'username' => 'required|string|max:191',
        //     'password' => 'required|string|min:5',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 500,
        //         'message' => "Incorrect Username or Password!"
        //     ], 500);
        // } else {
        //     // Authenticate the user
        //     $credentials = $request->only('username', 'password');
        //     if (Auth::attempt($credentials)) {
        //         // Authentication passed
        //         return response()->json([
        //             'status' => 200,
        //             'admin' => $validator->messages()
        //         ], 200);
        //     } else {
        //         // Authentication failed
        //         return response()->json([
        //             'status' => 401,
        //             'message' => "Invalid credentials"
        //         ], 401);
        //     }
        // }

        // if($validator->fails()){
        //     return response()->json([
        //         'status' => 500,
        //         'message' => "Incorrect Username or Password!"
        //     ],500);

            
        
        //     }else{return response()->json([
        //         'status' => 200,
        //         'admin' => $validator->messages()
        //     ],200);
        // }
    

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

     public function saveOrder(Request $request)
     {
         // Validate the incoming request data
         $request->validate([
             'productname' => 'required',
             'price' => 'required|numeric',
             'quantity' => 'required|integer',
             'date' => 'required|date',
             'discount' => 'nullable|numeric',
             'tax' => 'nullable|numeric',
         ]);
 
        $subtotal = $request->price * $request->quantity;
        $discountPercentage = $request->discount ?? 0; // If discount is not provided, assume it's 0%
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount;
        $tax = $total * 0.12;
 
         $sales = Sales::create([
             'productname' => $request->productname,
             'price' => $request->price,
             'quantity' => $request->quantity,
             'subtotal' => $subtotal,
             'discount' => $request->discount ?? 0,
             'date' => $request->date,
             'tax' => $tax,
             'total' => $total,
         ]);
 
         if ($sales) {
             return response()->json([
                 'status' => 200,
                 'message' => 'Order saved successfully.'
             ], 200);
         } else {
             return response()->json([
                 'status' => 500,
                 'message' => 'Failed to save order.'
             ], 500);
         }
     }

     public function getorder(){
        $orders = Sales::all();

        return response()->json([
            'status' => 200,
            'message' => $orders
        ],200);
     }

     public function totalMember(){
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

    
     public function totalSales(){
        $sales = Sales::all();

    if ($sales->isEmpty()) {
        return response()->json([
            'status' => 500,
            'message' => 'No sales data found'
        ], 500);
    }

    $totalSales = $sales->sum('total');

    return response()->json([
        'status' => 200,
        'totalsales' => $totalSales
    ], 200);
     }

     public function totalProduct(){
        $totalproduct = Product::count();

        if($totalproduct){
            return response()->json([
                'status' => 200,
                'message' => $totalproduct
            ],200);
        }else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong'
            ],500);
        }
     }

     public function logout(Request $request)
     {
         Auth::logout();
         return redirect('/login');
     }
 }
