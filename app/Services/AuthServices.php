<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function registerUser($request){
        try {
            if ($request->avtar) {
                $destinationPath = 'user_avtar';
                $myimage = time().$request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
            }
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'avtar' => url($myimage),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_no' => $request->phone_no,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'date_of_join' => $request->date_of_join,
                'address' => $request->address
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public static function login($request){
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
