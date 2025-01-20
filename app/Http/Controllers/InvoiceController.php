<?php

namespace App\Http\Controllers;

use Exception;
use App\Helper\SSLCommerz;

use App\Models\Invoice;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\InvoiceProduct;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{

   function InvoiceCreate(Request $request){
    DB::beginTransaction();

    try{

        $user_id = $request->header('id');
        $user_email = $request->header('email');

        $tran_id = uniqid();
        $delivery_status = "Pending";
        $payment_status = "Pending";


        $Profile = CustomerProfile::where('user_id', '=', $user_id)->first();
        $cus_details = "Name: $Profile->cus_name, Address: $Profile->cus_add, City: $Profile->cus_city, Phone: $Profile->cus_phone";
        $ship_details = "Name: $Profile->ship_name, Address: $Profile->ship_add, City: $Profile->ship_city, Phone: $Profile->ship_phone";

        // Payable Calculation
        $total = 0;
        $cartList = ProductCart::where('user_id', '=', $user_id)->get();
        forEach($cartList as $cartItem){
            $total = $total+$cartItem->price;
        }

        $vat = ($total*3)/100;
        $payable = $total + $vat;

        $invoice = Invoice::create([
            'total' => $total,
            'vat'=>$vat,
            'payable'=>$payable,
            'cus_details'=>$cus_details,
            'ship_details' =>$ship_details,
            'tran_id'=>$tran_id,
            'delivery_status'=>$delivery_status,
            'payment_status'=>$payment_status,
            'user_id'=>$user_id
            
        ]);

        $invoiceID = $invoice->id;
        forEach($cartList as $EachProduct){
            InvoiceProduct::create([
                'invoice_id'=>$invoiceID,
                'product_id'=>$EachProduct['product_id'],
                'user_id' =>$user_id,
                'qty'=>$EachProduct['qty'],
                'sale_price'=>$EachProduct['sale_price'],
            ]);
        }

        $paymentMethod = SSLCommerz::InitiatePayment($Profile, $payable, $tran_id, $user_email);

        DB::commit();

        return ResponseHelper::Out('success', array(['paymentMethod'=>$paymentMethod, 'payable'=>$payable, 'vat'=>$vat, 'total'=>$total]),200);



    }
     catch(Exception $e){
        DB::rollBack();
        return ResponseHelper::Out("fail", $e, 200);
    
    }
   }

   function InvoiceList(Request $request){
    $user_id = $request->header('id');
    return Invoice::where('user_id', $user_id)->get();
   }

   function InvoiceProductList(Request $request){
    $user_id = $request->header('id');
    $invoice_id = $request->invoice_id;
    return InvoiceProduct::where(['user_id'=>$user_id, 'invoice_id'=>$invoice_id])->with('product')->get();
   }


   function PaymentSuccess(Request $request){
    SSLCommerz::InitiateSuccess($request->query('tran_id'));
    return redirect('/profile');
   }

   function PaymentCancel(Request $request){
    SSLCommerz::InitiateCancel($request->query('tran_id'));
    return redirect('/profile');
   }

   function PaymentFail(Request $request){
    SSLCommerz::InitiateFail($request->query('tran_id'));
    return redirect('/profile');
   }

   function PaymentPIN(Request $request){
    return SSLCommerz::InitiatePIN($request->input('tran_id'), $request->input('status'), $request->input('val_id'));
   }






 
}


