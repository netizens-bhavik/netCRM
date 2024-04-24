<?php

namespace App\services;

use App\Models\Role;

class RoleServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function create($request){
        try {
            Role::create();
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
