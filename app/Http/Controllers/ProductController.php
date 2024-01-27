<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{

    function CreateProduct(Request $request)
    {
        try {
            $user_id=Auth::id();
            $request->validate([
                'name' => 'required|string|max:50',
                'price' => 'required|string|max:50',
                'unit' => 'required|string|max:11',
                "category_id"=> 'required|string',
            ]);
            Product::create([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'category_id'=>$request->input('category_id'),
                'user_id'=>$user_id
            ]);
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }


    function DeleteProduct(Request $request)
    {
        try {
            $user_id=Auth::id();
            $request->validate([
                "id"=> 'required|string',
            ]);
            Product::where('id',$request->input('id'))->where('user_id',$user_id)->delete();
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }


    function ProductByID(Request $request)
    {
        try {
            $user_id=Auth::id();
            $request->validate(["id"=> 'required|string']);
            $rows= Product::where('id',$request->input('id'))->where('user_id',$user_id)->first();
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }


    function ProductList(Request $request)
    {
        try {
            $user_id=Auth::id();
            $rows= Product::where('user_id',$user_id)->get();
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }


    function UpdateProduct(Request $request)
    {
        try {
            $user_id=Auth::id();
            $request->validate([
                'name' => 'required|string|max:50',
                'price' => 'required|string|max:50',
                'unit' => 'required|string|max:11',
                "category_id"=> 'required|string',
                "id"=> 'required|string',
            ]);

            Product::where('id',$request->input('id'))->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'category_id'=>$request->input('category_id'),
            ]);

            return response()->json(['status' => 'success', 'message' => "Request Successful"]);

        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }

    }
}
