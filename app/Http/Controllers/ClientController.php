<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Services\ClientServices;
use Illuminate\Support\Facades\Request;

class ClientController extends Controller
{
    function store(ClientRequest $request){
        try {
            $response = ClientServices::createClient($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function edit($clientId){
        try {
            $response = ClientServices::editClient($clientId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function update(ClientUpdateRequest $request,$clientId){
        try {
            $response = ClientServices::updateClient($request,$clientId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function destroy($clientId){
        try {
            $response = ClientServices::deleteClient($clientId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
