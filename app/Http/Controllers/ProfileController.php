<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;

class ProfileController extends Controller
{
 
    public function CreateProfile(Request $request){
        $user_id = $request->header('id');
        $request->merge(['user_id'=>$user_id]);
        $data = CustomerProfile::updateOrCreate([
            'user_id'=>$user_id
        ],
    $request->input()
    );

    return ResponseHelper::Out('success', $data,200);
    }
    
}
