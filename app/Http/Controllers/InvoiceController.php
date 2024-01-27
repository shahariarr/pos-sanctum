<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{

    function invoiceCreate(Request $request){
        DB::beginTransaction();

        try {

            $request->validate([
                'total' => 'required|string|max:50',
                'discount' => 'required|string|max:50',
                'vat' => 'required|string|max:50',
                'payable' => 'required|string|max:50',
                'customer_id' => 'required|string|max:50',
            ]);
            $user_id=Auth::id();
            $total=$request->input('total');
            $discount=$request->input('discount');
            $vat=$request->input('vat');
            $payable=$request->input('payable');
            $customer_id=$request->input('customer_id');


            $invoice= Invoice::create([
                'total'=>$total,
                'discount'=>$discount,
                'vat'=>$vat,
                'payable'=>$payable,
                'user_id'=>$user_id,
                'customer_id'=>$customer_id,
            ]);




            $invoiceID=$invoice->id;

            $products= $request->input('products');
            foreach ($products as $EachProduct) {
                    InvoiceProduct::create([
                        'invoice_id' => $invoiceID,
                        'user_id'=>$user_id,
                        'product_id' => $EachProduct['product_id'],
                        'qty' =>  $EachProduct['qty'],
                        'sale_price'=>  $EachProduct['sale_price'],
                    ]);
             }






            DB::commit();
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }


    }


    function invoiceSelect(Request $request){
        try {
            $user_id=Auth::id();
            $rows= Invoice::where('user_id',$user_id)->with('customer')->get();
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }


    function InvoiceDetails(Request $request){
        try {
            $user_id=Auth::id();
            $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();
            $invoiceTotal=Invoice::where('user_id','=',$user_id)->where('id',$request->input('inv_id'))->first();
            $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
                ->where('user_id',$user_id)->with('product')
                ->get();
            $rows= array(
                'customer'=>$customerDetails,
                'invoice'=>$invoiceTotal,
                'product'=>$invoiceProduct,
            );
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }
        catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }



    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id=Auth::id();

            InvoiceProduct::where('invoice_id',$request->input('inv_id'))
                ->where('user_id',$user_id)
                ->delete();

            Invoice::where('id',$request->input('inv_id'))->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }
        catch (Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }



}
