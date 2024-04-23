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
                $destinationPath = 'avatars';
                $myimage = time().$request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
            }else{
                $myimage= null;
            }
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'avtar' => $myimage,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_no' => $request->has('phone_no') ? $request->phone_no : null,
                'date_of_birth' => $request->has('date_of_birth') ? $request->date_of_birth : null,
                'gender' => $request->has('gender') ? $request->gender : null,
                'date_of_join' => $request->has('date_of_join') ? $request->date_of_join : null,
                'address' => $request->has('address') ? $request->address : null,
            ]);
            $user->assignRole($request->role);
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
            $roles = $user->getRoleNames();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'role' => $roles
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
