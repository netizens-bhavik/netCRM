<?php

namespace App\Services;

use Exception;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectHasMembers;
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
            $users = User::withoutRole('super-admin')->with(['roles' => function ($query) {
                        $query->select('name', 'label');
                }])->get();
            if ($request->project_id) {
                // $users = DB::select('select u.*  from project_has_members as pm join users as u on u.id = pm.user_id where pm.project_id = "' . $request->project_id . '"');
                $users = DB::table('project_has_members as pm')
                ->select('u.*') // Selecting all columns from the users table
                ->leftJoin('users as u', 'u.id', '=', 'pm.user_id')
                ->where('pm.project_id', $request->project_id)
                ->get();


            }

            $response = ['status' => 'Success', 'data' => $users];
            return response()->json($response);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }

    public static function show($request)
    {
        try {
            $user = User::find($request->user()->id);
            $roles = $user->getRoleNames();
            $permissionNames = $user->getPermissionsViaRoles()->pluck('name');
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
                    'role' => $roles[0],
                    'permission' => $permissionNames->toArray()
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
                    'avtar' => $user->avtar,
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
                if($request->hasFile('adhar_image')){
                    $destinationPath = 'adharImages';
                    $myadhar = time().$request->adhar_image->getClientOriginalName();
                    $request->adhar_image->move(public_path($destinationPath), $myadhar);

                    if ($user->adhar_image && file_exists(public_path('adharImages/'.$user->adhar_image))) {
                        unlink(public_path('adharImages/'.$user->adhar_image));
                    }
                }else{
                    $myadhar = $user->adhar_image;
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
                    'phone_no' => $request->has('phone_no') ? $request->phone_no : null,
                    'date_of_birth' => $request->has('date_of_birth') ? $request->date_of_birth : null,
                    'gender' => $request->has('gender') ? $request->gender : null,
                    'date_of_join' => $request->has('date_of_join') ? $request->date_of_join : null,
                    'address' => $request->has('address') ? $request->address : null,
                    'adhar_image' => $myadhar

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
                $projects = Project::where('manage_by',$userId)->get();
                if($projects->isNotEmpty()){
                    throw new Exception('This User Manage Few Projects. Soo You can not Delete this User.');
                }
                // unlink('avatars/' . $user->avtar);
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
            $nonAdminUsersQuery = User::with('roles')->withoutRole('super-admin');

            if ($request->search) {
                $nonAdminUsersQuery->where('name', 'like', '%' . $request->search . '%');
            }
            if ($request->sortBy && $request->order) {
                $nonAdminUsersQuery->orderBy($request->sortBy, $request->order);
            }
            if($request->sortBy && $request->order && $request->search)
            {
                $nonAdminUsersQuery->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order);
            }
            $nonAdminUsers = $nonAdminUsersQuery->latest()->paginate(10);

            $nonAdminUsers->each(function ($user) {
                $firstRole = $user->roles->first();
                $role = ['value' => $firstRole ? $firstRole->name : null, 'label' => $firstRole ? Role::roles[$firstRole->name] ?? $firstRole->name : null];
                $user->roleName = $role;
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
                $role = $user->roles->first();
                $user->roleName = $role;
                unset($user->roles);

                $projects = Project::with('members.user', 'client', 'manageBy')
                    ->whereHas('members', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->Orwhere('manage_by', $user->id)
                    ->orWhere('created_by',$user->id)
                    ->get()->toArray();

                $tasks = Task::with('members.user', 'observers.user', 'project', 'createdBy', 'assignedTo')
                        ->orWhereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->orWhereHas('observers', function ($query) use ($user) {
                            $query->where('observer_id', $user->id);
                        })
                        ->orWhereHas('assignedTo', function ($query) use ($user) {
                            $query->where('assigned_to', $user->id);
                        })
                        ->orWhere('created_by', $user->id)->get()->toArray();
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
                        $response = ['status' => 'success', 'message' => 'Password Change Successfully.'];

                        return response()->json($response);
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
