<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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
                $destinationPath = 'client_avtar';
                $myimage = time() . $request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
                $avtar = $destinationPath . '/' . $myimage;
            } else {
                $avtar = '';
            }
            if (isset($request->company_logo)) {
                $destinationPath = 'company_logo';
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
                'added_by' => Auth::id()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Client Create Successfully.'], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function editClient($clientId)
    {
        try {
            $client = Client::find($clientId);

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
            $response = ['status' => 'success', 'data' => $data];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function updateClient($request, $clientId)
    {
        try {
            $client = Client::find($clientId);
            if (isset($request->avtar)) {
                $destinationPath = 'client_avtar';
                $myimage = time() . $request->avtar->getClientOriginalName();
                $request->avtar->move(public_path($destinationPath), $myimage);
                $avtar = $destinationPath . '/' . $myimage;
            } else {
                $avtar = '';
            }
            if (isset($request->company_logo)) {
                $destinationPath = 'company_logo';
                $myimage = time() . $request->company_logo->getClientOriginalName();
                $request->company_logo->move(public_path($destinationPath), $myimage);
                $company_logo = $destinationPath . '/' . $myimage;
            } else {
                $company_logo = '';
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
                'added_by' => Auth::id()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Client Updated Successfully.'], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteClient($clientId)
    {
        try {
            $client = Client::find($clientId)->delete();
            return response()->json(['status' => 'success', 'message' => 'Client Deleted Successfully.'], 200);
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
}
