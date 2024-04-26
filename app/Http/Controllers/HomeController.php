<?php

namespace App\Http\Controllers;

use App\Services\HomeServices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function statastics()
    {
        try {
            $response = HomeServices::statastics();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getStates($countryId)
    {
        try {
            $response = HomeServices::getStates($countryId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getCities($stateId){
        try {
            $response = HomeServices::getCities($stateId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function allCountries(){
        try {
            $response = HomeServices::getAllCountries();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function topPerformers(){
        try {
            $response = HomeServices::topPerformers();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
