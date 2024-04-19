<?php

namespace App\Services;

use Exception;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserServices
{
    use ApiResponses;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function allUsers($request)
    {
        try {
            if ($request->project_id) {
                $project = Project::find($request->project_id);
                if ($project) {
                    $users = User::withoutRole('super-admin')->get();
                    $p = Project::with('members.user')->get();
                    $data = ['users' => $users, 'projectMember' => $p]; // Include both users and project members
                } else {
                    throw new Exception('Project Not Found');
                }

                // Move the JSON response outside the if block
                $response = ['status' => 'Success', 'data' => $data];
                return response()->json($response);
            } else {
                $users = User::withoutRole('super-admin')->get();
                $response = ['status' => 'Success', 'data' => ['users' => $users]];
                return response()->json($response);

            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }

    public static function show($request)
    {
        try {
            $user = User::find($request->user()->id);
            $roles = $user->getRoleNames();
            if ($user) {
                $data = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avtar' => $user->avtar,
                    'email' => $user->email,
                    'phone_no' => $user->phone_no,
                    'date_of_birth' => $user->date_of_birth,
                    'gender' => $user->gender,
                    'date_of_join' => $user->date_of_join,
                    'address' => $user->address,
                    'about' => $user->about,
                    'role' => $roles[0]
                ];
                return response()->json(['staus' => true, 'data' => $data], 200);
            } else {
                throw new Exception('User Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
            // return response()->json([
            //     'status' => false,
            //     'message' => $th->getMessage()
            // ], 500);
        }
    }
    public static function edit($userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                $data = [
                    'name' => $user->name,
                    'avtar' => url('avatars/' . $user->avtar),
                    'email' => $user->email,
                    'phone_no' => $user->phone_no,
                    'date_of_birth' => $user->date_of_birth,
                    'gender' => $user->gender,
                    'date_of_join' => $user->date_of_join,
                    'address' => $user->address,
                ];
                $response = ['status' => 'Success', 'data' => $data];
                return response()->json($response);
            } else {
                throw new Exception('Usr Not Found.');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function update($UserId, $request)
    {
        try {
            $user = User::find($UserId);

            if ($user) {
                if ($request->hasFile('avtar')) {
                    // unlink('avatars/' . $user->avtar);
                    $destinationPath = 'avatars';
                    $myimage = time() . $request->avtar->getClientOriginalName();
                    $request->avtar->move(public_path($destinationPath), $myimage);
                } else {
                    $myimage = $user->avtar;
                }
                if ($request->currunt_password) {
                    if (Hash::check($request->currunt_password, $user->password)) {
                        $password = Hash::make($request->password);
                    } else {
                        throw new Exception('Currunt Password is incorrect');
                    }
                } else {
                    $password = $user->password;
                }
                if ($request->role) {
                    // $user->removeRole($user->getRoleNames());
                    // $user->assignRole($request->role);
                    $user->syncRoles([$request->role]);
                }
                $user->update([
                    'name' => $request->name,
                    'avtar' => $myimage,
                    'email' => $request->email,
                    'password' => $password,
                    'phone_no' => $request->phone_no,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'date_of_join' => $request->date_of_join,
                    'address' => $request->address
                ]);
                $response = ['status' => 'success', 'message' => 'User Update Successfully.'];
                return response()->json($response);
            } else {
                throw new Exception('Usr Not Found.');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function userDelete($userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                unlink('avatars/' . $user->avtar);
                $user->delete();
                $response = ['status' => 'Success', 'message' => 'User Delete Successfully.'];
                return response()->json($response);
            } else {
                throw new Exception('User Not Found.');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function userList($request)
    {
        try {
            $role = [];
            if ($request->search && $request->sortBy && $request->order) {
                $nonAdminUsers = User::with('roles')->withoutRole('super-admin')->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)->paginate(10);
            } elseif ($request->search) {
                $nonAdminUsers = User::with('roles')->withoutRole('super-admin')->where('name', 'like', '%' . $request->search . '%')->paginate(10);
            } elseif ($request->sortBy && $request->order) {
                $nonAdminUsers = User::with('roles')->withoutRole('super-admin')->orderBy($request->sortBy, $request->order)->paginate(10);
            } else {
                $nonAdminUsers = User::with('roles')->withoutRole('super-admin')->paginate(10);
            }
            $nonAdminUsers->each(function ($user) {
                $firstRole = $user->roles->first();
                $role = ['value' => $firstRole ? $firstRole->name : null, 'label' => $firstRole ? Role::roles[$firstRole->name] ?? $firstRole->name : null];
                // $user->rolesName = $firstRole ? $firstRole->name : null;
                // $user->rolesLabel = $firstRole ? Role::roles[$firstRole->name] ?? $firstRole->name : null;
                $user->roleNmae = $role;
                unset($user->roles);
            });
            $response = ['status' => 'Success', 'data' => $nonAdminUsers];
            return response()->json($response);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function findUser($userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                $roles = $user->getRoleNames();
                $user['role'] = $roles;
                $projects = Project::with('members.user', 'client', 'manageBy')
                    ->whereHas('members', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->Orwhere('manage_by', $user->id)->get()->toArray();

                $tasks = Task::with('members.user', 'project', 'manageBy')
                    ->whereHas('members', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->Orwhere('manage_by', $user->id)->get()->toArray();
                $response = ['status' => 'Success', 'data' => ['user' => $user, 'Project' => $projects, 'task' => $tasks]];
                return response()->json($response);
            } else {
                throw new Exception('User Not Found.');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function resetPassword($request, $userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                if ($request->currunt_password) {
                    if (Hash::check($request->currunt_password, $user->password)) {
                        $password = Hash::make($request->password);
                        $user->update(['password' => $password]);
                    } else {
                        throw new Exception('Currunt Password is incorrect');
                    }
                }
            } else {
                throw new Exception('User Not Found.');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function forgotPassword($request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|email',
            ]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
