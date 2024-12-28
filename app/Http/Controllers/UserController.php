<?php
namespace App\Http\Controllers;
use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function LoginPage()
    {
        return view('pages.login-page');
    }

    public function VerifyPage()
    {
        return view('pages.verify-page');
    }


   
    

   public function UserLogin(Request $request){
    try{
        $UserEmail = $request->UserEmail;
    $OTP = rand(100000,999999);
    $details = ['code'=>$OTP];
    Mail::to($UserEmail)->send(new OTPMail($details));
    User::updateOrCreate(['email'=>$UserEmail],['email'=>$UserEmail, 'otp'=>$OTP]);
    return ResponseHelper::Out('success', 'A 6 Digit OTP has been send to your email address', 200);
    }

    catch(Exception){
        return ResponseHelper::Out("fail", "OTP Does not send", 200);
    }
   }

   public function VerifyLogin(Request $request){
    $UserEmail = $request->UserEmail;
    $OTP = $request->OTP;

    $verification = User::where('email', $UserEmail)->where('otp', $OTP)->first();

    if($verification){
        User::where('email', $UserEmail)->where('otp', $OTP)->update(["otp"=>"0"]);
        $token = JWTToken::CreateToken($UserEmail, $verification->id);
        return ResponseHelper::Out("Success", "", 200)->cookie('token', $token, 60*24*30);
    }else{
        return ResponseHelper::Out("fail", null,401);
    }
   }


}
