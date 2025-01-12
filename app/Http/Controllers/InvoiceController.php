<?php

namespace App\Http\Controllers;
use App\Models\Invoice;

use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{


    function InvoiceCreate(Request $request){
        DB::beginTransaction();

        $user_id = $request->header('id');
        $user_email = $request->header('email');

        $tran_id = uniqid();
        $delivery_status = "Pending";
        $payment_status = "Pending";

        $Profile = CustomerProfile::where('user_id', '=', $user_id)->first();
        $cus_details = "Name: $Profile->cus_name, Address: $Profile->cus_add, City: $Profile->cus_city, Phone: $Profile->cus_phone";
        $ship_details = "Name: $Profile->ship_name, Address: $Profile->ship_add, City: $Profile->ship_city";

        // Payable Calculator
        $total = 0;
        $cartList = ProductCart::where('user_id', '=', $user_id)->get();
        forEach($cartList as $cartItem){
            $total = $total+$cartItem->price;
        }

        $vat = ($total*3)/100;
        $payable = $total+$vat;

        $invoice = Invoice::create([
            'total' => $total,
            'vat'=> $vat,
            'payable' => $payable,
            'cus_details' =>$cus_details,
            'ship_details' =>$ship_details,
            'tran_id' =>$tran_id,
            'delivery_status' => $delivery_status,
            'payment_status' => $payment_status,
            'user_id' => $user_id
        ]);

        


    }
 
}
