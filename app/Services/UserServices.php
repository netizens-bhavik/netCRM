<?php

namespace App\Services;

use App\Models\User;

class UserServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function show($request)
    {
        try {
            $user = User::find($request->user()->id);
            $data = [
                'name' => $user->name,
                'avtar' => $user->avtar,
                'email' => $user->email,
                'phone_no' => $user->phone_no,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'date_of_join' => $user->date_of_join,
                'address' => $user->address,
                'about' => $user->about
            ];
        return response()->json(['staus' => true,'data'=>$data],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
