<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Project;

use App\Models\User;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function createClient($request)
    {
        try {
            if (isset($request->avtar)) {
                $destinationPath = 'clientAvtar';
                $myimage = time() . $request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
                $avtar = $myimage;
            } else {
                $avtar = '';
            }
            if (isset($request->company_logo)) {
                $destinationPath = 'companyLogo';
                $myimage = time() . $request->company_logo->getClientOriginalName();
                $request->company_logo->move(public_path($destinationPath), $myimage);
                $company_logo = $myimage;
            } else {
                $company_logo = '';
            }
            $client = Client::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'avtar' => $avtar,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'zipcode' => $request->zipcode,
                'phone_no' => $request->phone_no,
                'company_name' => $request->company_name,
                'company_website' => $request->company_website,
                'company_address' => $request->company_address,
                'company_logo' => $company_logo,
                'tax' => $request->tax,
                'gst_vat' => $request->gst_vat,
                'office_mobile' => $request->office_mobile,
                'address' => $request->address,
                'note' => $request->note,
                'added_by' => Auth::id()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Client Create Successfully.'], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function editClient($request, $clientId)
    {
        try {
            $client = Client::find($clientId);
            if ($client) {
                $data = [
                    'name' => $client->name,
                    'email' => $client->email,
                    'avtar' => url($client->avtar),
                    'country_id' => $client->country_id,
                    'state_id' => $client->state_id,
                    'city_id' => $client->city_id,
                    'zipcode' => $client->zipcode,
                    'phone_no' => $client->phone_no,
                    'company_name' => $client->company_name,
                    'company_website' => $client->company_website,
                    'company_address' => $client->company_address,
                    'company_logo' => url($client->company_logo),
                    'tax' => $client->tax,
                    'gst_vat' => $client->gst_vat,
                    'office_mobile' => $client->office_mobile,
                    'address' => $client->address,
                    'note' => $client->note,
                ];
                if ($request->expectsJson()) {
                    //For API
                    $response = ['status' => 'success', 'data' => $data];
                    return response()->json($response, 200);
                } else {
                    //For  WEB
                    $countries = DB::table('countries')->get(['id', 'name']);
                    $state = DB::table('states')->where('id', $client->state_id)->first(['id', 'name']);
                    $city = DB::table('cities')->where('id', $client->city_id)->first(['id', 'name']);
                    return view('clients.create', compact('countries', 'city', 'state', 'client'));
                }
            } else {
                throw new Exception('Client Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function updateClient($request, $clientId)
    {
        try {
            $client = Client::find($clientId);
            if ($client) {
                if ($request->hasFile('avtar')) {
                    $destinationPath = 'clientAvtar';
                    $myimage = time() . $request->avtar->getClientOriginalName();
                    $request->avtar->move(public_path($destinationPath), $myimage);
                    $avtar = $myimage;
                } else {
                    $avtar = $client->avtar;
                }
                if ($request->hasFile('company_logo')) {
                    $destinationPath = 'companyLogo';
                    $myimage = time() . $request->company_logo->getClientOriginalName();
                    $request->company_logo->move(public_path($destinationPath), $myimage);
                    $company_logo = $myimage;
                } else {
                    $company_logo = $client->company_logo;
                }
                $client->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'avtar' => $avtar,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'zipcode' => $request->zipcode,
                    'phone_no' => $request->phone_no,
                    'company_name' => $request->company_name,
                    'company_website' => $request->company_website,
                    'company_address' => $request->company_address,
                    'company_logo' => $company_logo,
                    'tax' => $request->tax,
                    'gst_vat' => $request->gst_vat,
                    'office_mobile' => $request->office_mobile,
                    'address' => $request->address,
                    'note' => $request->note,
                    'added_by' => Auth::id() ? Auth::id() : '06ba9b1d-f70c-450c-b675-6a3147d9f205'
                ]);
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'message' => 'Client Updated Successfully.'], 200);
                } else {
                    toastr()->success('Data has been saved successfully!');
                    return redirect('client');
                }
            } else {
                throw new Exception('Client Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteClient($request, $clientId)
    {
        try {
            $client = Client::find($clientId);
            if ($client) {
                $client->delete();
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'message' => 'Client Deleted Successfully.'], 200);
                } else {
                    toastr()->success('Data has been Deleted successfully!');
                    return redirect('client');
                }
            } else {
                throw new Exception('Client Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function clienHasProjects($clientId)
    {
        try {
            $projects = Project::where('client_id', $clientId)->get();
            if (!$projects->isEmpty()) {
                $data = [];
                $_projects = [];
                foreach ($projects as $key => $project) {
                    $_projects[] = [
                        'name' => $project->name,
                        'start_date' => $project->start_date,
                        'deadline' => $project->deadline,
                        'summary' => $project->summary,
                        'currency' => $project->currency
                    ];
                }
                $data['projects'] = $_projects;
                $response = ['status' => 'success', 'data' => $data];
                return response()->json($response, 200);
            } else {
                throw new Exception('No Project Of This CLient.');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function ClientList($request)
    {
        try {
            $data = [];
            $i = 1;
            $clients = Client::latest('created_at')->get(['id', 'name', 'email', 'avtar', 'company_name']);
            foreach ($clients as $key => $client) {
                $data[] = [
                    '#' => $i++,
                    'name' => '<img src="' . url($client->avtar) . '" class="h-50 w-50" />' . $client->name,
                    'email' => $client->email,
                    'company_name' => $client->company_name,
                    'action' => "<a href='" . url('client/' . $client->id . '/edit') . "' class='me-3'><i class='ti ti-edit'></i></a><a href='" . url('delete/' . $client->id . '/delete') . "' class='me-3'><i class='ti ti-trash'></i></a>"
                ];
            }
            return Datatables::of($data)->rawColumns(['name', 'action'])->make(true);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Error', 'message' => $th->getMessage()]);
        }
    }
    public static function create()
    {
        try {
            $countryData = [];
            $countries = DB::table('countries')->get();
            return view('clients.create', compact('countries'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function StoreClientForm($request)
    {
        try {
            if (isset($request->avtar)) {
                $destinationPath = 'clientAvtar';
                $myimage = time() . $request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
                $avtar = $destinationPath . '/' . $myimage;
            } else {
                $avtar = '';
            }
            if (isset($request->company_logo)) {
                $destinationPath = 'companyLogo';
                $myimage = time() . $request->company_logo->getClientOriginalName();
                $request->company_logo->move(public_path($destinationPath), $myimage);
                $company_logo = $destinationPath . '/' . $myimage;
            } else {
                $company_logo = '';
            }
            $client = Client::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'avtar' => $avtar,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'zipcode' => $request->zipcode,
                'phone_no' => $request->phone_no,
                'company_name' => $request->company_name,
                'company_website' => $request->company_website,
                'company_address' => $request->company_address,
                'company_logo' => $company_logo,
                'tax' => $request->tax,
                'gst_vat' => $request->gst_vat,
                'office_mobile' => $request->office_mobile,
                'address' => $request->address,
                'note' => $request->note,
                'added_by' => '06ba9b1d-f70c-450c-b675-6a3147d9f205'
            ]);
            toastr()->success('Data has been saved successfully!');
            return redirect('client');
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allClientList($request)
    {
        try {
            $data = [];
            if ($request->search && $request->sortBy && $request->order) {
                $clients = Client::with('country', 'state', 'city')->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)->paginate(10);
            }elseif($request->search){
                $clients = Client::with('country', 'state', 'city')->where('name', 'like', '%' . $request->search . '%')->paginate(10);
            } elseif ($request->sortBy && $request->order) {
                $clients = Client::with('country', 'state', 'city')->orderBy($request->sortBy, $request->order)->paginate(10);
            } else {
                $clients = Client::latest()->with('country', 'state', 'city')->paginate(10);
            }

            return response()->json(['status' => 'success', 'data' => $clients], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allClients()
    {
        try {
            $clients = Client::all();
            return response()->json(['status' => 'success', 'data' => $clients], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    // public static function clienHasProjects($clientId)
    // {
    //     try {
    //         $projects = Project::where('client_id', $clientId)->get();
    //         if (!$projects->isEmpty()) {
    //             $data = [];
    //             $_projects = [];
    //             foreach ($projects as $key => $project) {
    //                 $_projects[] = [
    //                     'name' => $project->name,
    //                     'start_date' => $project->start_date,
    //                     'deadline' => $project->deadline,
    //                     'summary' => $project->summary,
    //                     'currency' => $project->currency
    //                 ];
    //             }
    //             $data['projects'] = $_projects;
    //             $response = ['status' => 'success', 'data' => $data];
    //             return response()->json($response, 200);
    //         } else {
    //             throw new Exception('No Project Of This CLient.');
    //         }
    //     } catch (\Throwable $th) {
    //         $res = ['status' => 'error', 'message' => $th->getMessage()];
    //         return response()->json($res);
    //     }
    // }
}
