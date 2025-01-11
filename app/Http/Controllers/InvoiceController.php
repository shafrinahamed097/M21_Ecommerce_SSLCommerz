<?php

namespace App\Http\Controllers;
use App\Models\ProductCart;

use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{

   function InvoiceCreate(Request $request){
    DB::beginTransaction();

    $user_id = $request->header('id');
    $user_email = $request->header('email');

    $Profile = CustomerProfile::where('user_id', '=', $user_id)->first();
    $cus_details = "Name: $Profile->cus_name, Address: $Profile->use_add, City: $Profile->cus_city, Phone: $Profile->cus_phone";
    $ship_details = "Name: $Profile->ship_name, Address: $Profile=>ship_add, City-> $Profile->ship_city, Phone: $Profile->phone";

    // Payable Calculation
    $total = 0;
    $cartList = ProductCart::where('user_id', '=', $user_id)->get();
    forEach($cartList as $cartItem){
        $total = $total+$cartItem->price;

    }

    $vat = ($total*3)/100;
    $payable = $total+$vat;
    

   }
 
}
