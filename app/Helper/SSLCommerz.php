<?php

namespace App\Helper;

use App\Models\SslcommerzAccount;
use Exception;
use Illuminate\Support\Facades\Http;

class SSLCommerz{
    static function InitiatePayment($Profile, $payable, $tran_id, $user_email){
        try{
            $ssl = SslcommerzAccount::first();
            $response = Http::asForm()->post($ssl->init_url,[
                "store_id"=>$ssl->store_id,
                "store_passwd"=>$ssl->store_passwd,
                "total_amount" =>$payable,
                "currency"=>$ssl->currency,
                "tran_id"=>$tran_id,
                "success_url"=>"$ssl->success_url?tran_id=$tran_id",
                "fail_url"=>"$ssl->cancel_url?tran_id=$tran_id",
                "ipn_url"=>$ssl->ipn_url,
                "cus_name"=>$Profile->cus_name,
                "cus_email"=>$user_email,
                "cus_add1"=>$Profile->cus_add,
                "cus_add2"=>$Profile->cus_add,
                "cus_city"=>$Profile->cus_city,
                "cus_state"=>$Profile->cus_city,
                "cus_postcode"=>"1200",
                "cus_country"=>$Profile->cus_country,
                "cus_phone"=>$Profile->cus_phone,
                "cus_fax"=>$Profile->cus_phone,
                "shipping_method"=>"YES",
                "ship_name"=>$Profile->ship_name,
                "ship_add1"=>$Profile->ship_add,
                "ship_add2"=>$Profile->ship_add,
                "ship_city"=>$Profile->ship_city,
                "ship_state"=>$Profile->ship_city,
                "ship_country"=>$Profile->ship_country,
                "ship_postcode"=>"1200",
                "product_name"=>"Shafrin Ahamed",
                "product_category"=>"Shafrin Ahamd",
                "product_profile"=>"Shafrin Ahamed",
                "product_amount"=>$payable
            ]);

        }catch(Exception $e){
            return $ssl;
        }

    }
}
