<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\JsonResource;
use App\Helper\ResponseHelper;


class BrandController extends Controller
{


  public function ByBrandPage(){
    return view('pages.product-by-brand');
  }

  public function BrandList(Request $request){
    $data = Brand::all();
    if($data){
      return ResponseHelper::Out('success', $data,200);
    }else{
      return ResponseHelper::Out('fail', 'something went wrong', 200);
    }
  }
  
}

