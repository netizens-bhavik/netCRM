<?php

namespace App\Services;

use App\Models\Permission;

class PermissionServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function index()
    {
        try {
            $permission = Permission::all();
            $res = ['status' => 'success', 'data' => $permission];
            return response()->json($res);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
