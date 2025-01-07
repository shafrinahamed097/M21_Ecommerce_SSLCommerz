<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
   public function CategoryList(){
      $data = Category::all();
      if($data){
         return ResponseHelper::OUt('success', $data,200);
      }else{
         return ResponseHelper::Out("fail", 'Something went wrong',200);
      }
   }
}
