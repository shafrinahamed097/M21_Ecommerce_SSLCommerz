<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function LoginPage(){
        return view('pages.login-page');
    }

    public function VerifyPage(){
        return view('pages.verify-page');
    }


    public function UserLogin(Request $request){
        try{
            $UserEmail = $request->UserEmail;
            $OTP = rand(100000,999999);
            $details = ['code' =>$OTP];
            Mail::to($UserEmail)->send(new OTPMail($details));

        }catch(Exception $e){

        }
    }

   
   
}
