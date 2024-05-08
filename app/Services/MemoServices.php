<?php

namespace App\Services;

use App\Models\Memo;
use App\Traits\ApiResponses;
use Exception;
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
    public static function index($request)
    {
        try {
            // $memos = Memo::with('user')->where(function ($query) {
            //     $query->where('user_id', Auth::id())
            //         ->orWhere('status', 'public')
            //         ->orWhere('status', 'private');
            // })->get(['user_id', 'title', 'description', 'status','created_at','updated_at']);

            $memos = Memo::with('user')->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('status', 'public')
                      ->orWhere('status', 'private');
            });

            if ($request->search && $request->sortBy && $request->order) {
                $memos->where('title', 'like', '%' . $request->search . '%')
                      ->orderBy($request->sortBy, $request->order);
            } elseif ($request->search) {
                $memos->where('title', 'like', '%' . $request->search . '%');
            } elseif ($request->sortBy && $request->order) {
                $memos->orderBy($request->sortBy, $request->order);
            }
            else
            {
                $memos->latest()->paginate(10);
            }
            $memos = $memos->get(['user_id', 'title', 'description', 'status','created_at','updated_at']);
            return response()->json(['status' => 'success','data' => $memos]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function store($request){
        try {
            Memo::create(['user_id' => Auth::id(),'title' => $request->title,'description' => $request->description,'status'=>$request->status]);
            return response()->json(['status' => 'success','message' => 'Memo Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function edit($memo){
        try {
            if($memo){
                $memo->load('user');
                return response()->json(['status' => 'success','data' => $memo]);
            }else{
                throw new Exception('Memo not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function update($request,$memoId){
        try {
            $memo = Memo::find($memoId);
            $memo->update(['title' => $request->title,'description' => $request->description]);
            return response()->json(['status' => 'success','message' => 'Memo Update Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function destroy($memo){
        try {
            if($memo){
            $memo->delete();
            return response()->json(['status' => 'success','message' => 'Memo Delete Successfully.']);
        }else{
            throw new Exception('Memo not Found');
        }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
