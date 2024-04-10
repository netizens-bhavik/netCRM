<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Services\ClientServices;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    function index(){
return view('clients.index');
    }
    function store(ClientRequest $request){
        try {
            $response = ClientServices::createClient($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function edit(Request $request,$clientId){
        try {
            $response = ClientServices::editClient($request,$clientId);
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
    function destroy(Request $request,$clientId){
        try {
            $response = ClientServices::deleteClient($request,$clientId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function clientHasProject($clientId){
        try {
            $response = ClientServices::clienHasProjects($clientId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function clientList(Request $request){
        try {
            if ($request->ajax()) {
            $response = ClientServices::ClientList($request);
            return $response;
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Error','message' => $th->getMessage()]);
        }
    }
    function create(){
        try {
            $response = ClientServices::create();
            return $response;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    function StoreClientForm(ClientRequest $request){
        try {
            $response = ClientServices::StoreClientForm($request);
            return $response;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    function allClientList(Request $request){
        try {
            $response = ClientServices::allClientList($request);
            return $response;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    function allClients(){
        try {
            $response = ClientServices::allClients();
            return $response;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
