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
    public static function allRole()
    {
        try {
            $roles = Role::roles;
            $data = [];
            foreach ($roles  as $key => $role) {
                $data[] = [
                    'label' => $role,
                    'value' => $key,
                ];
            }
            // $data['roles'] = $data;
            return response()->json(['status' => 'success', 'data' => $data], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function index(){
        try {
            $roles = Role::with('permissions')->paginate(10);
            return response()->json(['status' => 'success', 'data' => $roles]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function create($request)
    {
        try {
            $roleName = strtolower($request->name);
            $label = ucfirst($roleName);
            $role =Role::create(['name' => $request->name, 'label' => $label]);
            $role->givePermissionTo($request->permissions);
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
                $permissions = $role->permissions()->get();
                return response()->json(['status' => 'success', 'data' => ['role' => $role,'permission' => $permissions]]);
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
                $role->givePermissionTo($request->permissions);
                return response()->json(['status' => 'success', 'message' => 'Role Upadated Successfully.'], 200);
            } else {
                throw new Exception('Role Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function destroy($roleId)
    {
        try {
            $role = Role::find($roleId);
            if ($role) {
                $users = User::role($role->name)->get();
                if ($users->count() > 0) {
                    throw new Exception('Cannot delete role. Users are assigned to this role.');
                }
                $role->delete();
                return response()->json(['status' => 'success', 'message' => 'Role Delete Successfully.'], 200);
            } else {
                throw new Exception('Role Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage() . $th->getFile() . $th->getLine()];
            return response()->json($res, 500);
        }
    }
}
