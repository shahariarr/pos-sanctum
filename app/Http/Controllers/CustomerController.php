<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{

    function CustomerCreate(Request $request):JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:50',
                'mobile' => 'required|string|min:11'
            ]);

            $user_id=Auth::id();
            Customer::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
                'user_id'=>$user_id
            ]);
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    function CustomerList(Request $request): JsonResponse
    {
        try {
            $user_id=Auth::id();
            $rows= Customer::where('user_id',$user_id)->get();
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    function CustomerDelete(Request $request){
        try {
            $request->validate([
                'id' => 'required|string|min:1'
            ]);
            $customer_id=$request->input('id');
            $user_id=Auth::id();
            Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    function CustomerByID(Request $request){
        try {
            $request->validate([
                'id' => 'required|min:1'
            ]);
            $customer_id=$request->input('id');
            $user_id=Auth::id();
            $rows= Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

     function CustomerUpdate(Request $request){

         try {
             $request->validate([
                 'name' => 'required|string|max:50',
                 'email' => 'required|string|email|max:50',
                 'mobile' => 'required|string|min:11',
                 'id'=>'required|min:1',
             ]);

             $customer_id=$request->input('id');
             $user_id=Auth::id();
             Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
                 'name'=>$request->input('name'),
                 'email'=>$request->input('email'),
                 'mobile'=>$request->input('mobile'),
             ]);
             return response()->json(['status' => 'success', 'message' => "Request Successful"]);
         }catch (Exception $e){
             return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
         }
    }

}
