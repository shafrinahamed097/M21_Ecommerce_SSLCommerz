<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\JsonResource;
use App\Helper\ResponseHelper;


class BrandController extends Controller
{


  public function BrandList():JsonResponse{
   $data = Brand::all();
   return ResponseHelper::Out("Success", $data,200);
  }
  
}

