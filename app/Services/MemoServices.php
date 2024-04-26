<?php

namespace App\Services;

use App\Models\Memo;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;

class MemoServices
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
            $memos = Memo::where('user_id',Auth::id())->get(['title','description']);
            return response()->json(['status' => 'success','data' => $memos]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function store($request){
        try {
            Memo::create(['user_id' => Auth::id(),'title' => $request->title,'description' => $request->description]);
            return response()->json(['status' => 'success','message' => 'Memo Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
