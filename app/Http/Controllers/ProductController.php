<?php
namespace App\Http\Controllers;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDetails;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use App\Models\ProductWish;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{


    public function WishList()
    {
        return view('pages.wish-list-page');
    }


    public function CartListPage()
    {
        return view('pages.cart-list-page');
    }


    public function Details()
    {
        return view('pages.details-page');
    }


    public function ListProductByCategory(Request $request):JsonResponse{
        $data=Product::where('category_id',$request->id)->with('brand','category')->get();
        return ResponseHelper::Out('success',$data,200);
    }

    public function ListProductByRemark(Request $request):JsonResponse{
        $data=Product::where('remark',$request->remark)->with('brand','category')->get();
        return ResponseHelper::Out('success',$data,200);
    }

    public function ListProductByBrand(Request $request){
        $data=Product::where('brand_id',$request->id)->with('brand','category')->get();
        return ResponseHelper::Out('success',$data,200);
    }

    public function ListProductSlider(){
        $data=ProductSlider::all();
        return ResponseHelper::Out('success',$data,200);
    }

    public function ProductDetailsById(Request $request){

        $data=ProductDetails::where('product_id',$request->id)->with('product','product.brand','product.category')->get();

        return ResponseHelper::Out('success',$data,200);
    }

    public function ListReviewByProduct(Request $request):JsonResponse{
        $data=ProductReview::where('product_id',$request->product_id)
            ->with(['profile'=>function($query){
                $query->select('id','cus_name');
            }])->get();
        return ResponseHelper::Out('success',$data,200);
    }

 

    // public function CreateProductReview(Request $request):JsonResponse{
    //     $user_id=$request->header('id');
    //     $profile=CustomerProfile::where('user_id',$user_id)->first();

    //     if($profile){
    //         $request->merge(['customer_id' =>$profile->id]);
    //         $data=ProductReview::updateOrCreate(
    //             ['customer_id' => $profile->id,'product_id'=>$request->input('product_id')],
    //             $request->input()
    //         );
    //         return ResponseHelper::Out('success',$data,200);
    //     }
    //     else{
    //         return ResponseHelper::Out('fail','Customer profile not exists',200);
    //     }

    // }


  public function CreateProductReview(Request $request){
    $user_id = $request->header('id');
    $profile = CustomerProfile::where('user_id', $user_id)->first();

    if($profile){
        $request->merge(['customer_id'=>$profile->id]);
        $data = ProductReview::updateOrCreate(['customer_id'=>$profile->id, 'product_id'=>$request->input('product_id')], $request->input());
        return ResponseHelper::Out('success', $data,200);
    }else{
        return ResponseHelper::Out('fail','Customer Profile not exits',200);
    }
  }

    
   public function CreateWishList(Request $request){
    $user_id = $request->header('id');
    $data = ProductWish::updateOrCreate(['user_id'=>$user_id, 'product_id'=>$request->product_id],
['user_id'=>$user_id, 'product_id'=>$request->product_id]);
     if($data){
        return ResponseHelper::Out('success', $data,200);

 }else{
    return ResponseHelper::Out('fail', 'Something went wrong',200);
 }


   }

    // public function RemoveWishList(Request $request):JsonResponse{
    //     $user_id=$request->header('id');
    //     $data=ProductWish::where(['user_id' => $user_id,'product_id'=>$request->product_id])->delete();
    //     return ResponseHelper::Out('success',$data,200);
    // }

    public function RemoveWishList(Request $request){
        $user_id = $request->header('id');
        $data = ProductWish::where(['user_id'=>$user_id, 'product_id'=>$request->product_id])->delete();

        if($data){
            return ResponseHelper::Out('success', 'Product remove', 200);
        }else{
            return ResponseHelper::Out('fail', 'Product Does not remove, something went wrong, Try again!',200);
        }
    }

    // public function ProductWishList(Request $request):JsonResponse{
    //     $user_id=$request->header('id');
    //     $data=ProductWish::where('user_id',$user_id)->with('product')->get();
    //     return ResponseHelper::Out('success',$data,200);
    // }


     public function ProductWishList(Request $request){
        $user_id = $request->header('id');
        $data = ProductWish::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::Out('success', $data,200);
     }
   
    // public function CreateCartList(Request $request){
    //     $user_id = $request->header('id');
    //     $product_id = $request->input('product_id');
    //     $color = $request->input('color');
    //     $size = $request->input('size');
    //     $qty = $request->input('size');

    //     $UnitPrice = 0;

    //     $productDetails = Product::where('id', '=', $product_id)->first();

    //     if($productDetails->discount==1){
    //         $UnitPrice = $productDetails->discount_price;
    //     }else{
    //         $UnitPrice = $productDetails->price;
    //     }

    //     $totalPrice = $qty*$UnitPrice;

    //     $data = ProductCart::updateOrCreate(['user_id'=>$user_id, 'product_id'=>$product_id],
    //     [

    //         'user_id' =>$user_id,
    //         'product_id' =>$product_id,
    //         'color' =>$color,
    //         'size' =>$size,
    //         'qty' =>$qty,
    //         'price' =>$totalPrice
    //     ]
    
    // );

    // return ResponseHelper::Out("success", $data,200);
    // }

      
    public function CreateCartList(Request $request){
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $color = $request->input('color');
        $size = $request->input('size');
        $qty = $request->input('qty');

        $UnitPrice = 0;

        $productDetails = Product::where('id', '=', $product_id)->first();

        if($productDetails->discount == 1){
            $UnitPrice = $productDetails->discount_price;
        }else{
            $UnitPrice = $productDetails->price;
        }
        $totalPrice = $qty * $UnitPrice;

        $data = ProductCart::updateOrCreate(['user_id'=> $user_id, 'product_id'=>$product_id],
    [
        'user_id'=>$user_id,
        'product_id'=>$product_id,
        'color'=>$color,
        'size'=>$size,
        'qty'=>$qty,
        'price'=>$totalPrice

    ]);

    return ResponseHelper::Out("success", $data,200);

    }
    // public function CartList(Request $request){

    //     $user_id = $request->header('id');
    //     $data = ProductCart::where('user_id', $user_id)->with('product')->get();
    //     return ResponseHelper::Out("success", $data,200);
    
    // }


   public function CartList(Request $request){
    $user_id = $request->header('id');
    $data =ProductCart::where('user_id', $user_id)->with('product')->get();
    return ResponseHelper::Out('success', $data,200);
   }

    // public function DeleteCartList(Request $request){
    //     $user_id = $request->header('id');
    //     $data = ProductCart::where('user_id', '=', $user_id)->where('product_id', '=', $request->product_id)->delete();
    //     return ResponseHelper::Out("success", $data,200);
    // }

    

   public function DeleteCartList(Request $request){
    $user_id = $request->header('id');
    $data = ProductCart::where('user_id', '=', $user_id)->where('product_id', '=', $request->product_id)->delete();
    return ResponseHelper::Out('success', $data,200);
   }



    


}
