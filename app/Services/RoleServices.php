<?php

namespace App\Services;

use Exception;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Exceptions\RoleHasUsersException;

class RoleServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function create($request)
    {
        try {
            $roleName = strtolower($request->name);
            $label = ucfirst($roleName);
            Role::create(['name' => $request->name, 'label' => $label]);
            return response()->json(['status' => 'success', 'message' => 'Role Create Successfully.'], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function edit($roleId)
    {
        try {
            $role = Role::find($roleId);
            if ($role) {
                return response()->json(['status' => 'success', 'data' => $role]);
            } else {
                throw new Exception('Role Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function update($request, $roleId)
    {
        try {
            $role = Role::find($roleId);
            if ($role) {
                $roleName = strtolower($request->name);
                $label = ucfirst($roleName);
                $role->update(['name' => $request->name, 'label' => $label]);
                return response()->json(['status' => 'success', 'message' => 'Role Upadated Successfully.'], 200);
            } else {
                throw new Exception('Role Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function destroy($roleId){
        try {
            $role = Role::find($roleId);
            if ($role) {
                $users = User::role($role->name)->get();
                if ($users->count() > 0) {
                    throw new Exception('Cannot delete role. Users are assigned to this role.');
                }
                // $role->delete();
                dd('sd');
                return response()->json(['status' => 'success', 'message' => 'Role Delete Successfully.'], 200);
            } else {
                throw new Exception('Role Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage().$th->getFile().$th->getLine()];
            return response()->json($res,500);
        }
    }
}
