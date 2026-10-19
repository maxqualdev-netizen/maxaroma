<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\ShoppingcartController;

use App\Models\Category;
use App\Models\Products;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RewardPoint;
use App\Models\GiftCertificate;
use App\Models\ShippingMode;

use Session;
class PhoneOrderController extends Controller
{
    use CommonTrait;
	use VendorTrait;
	use CartTrait;

    public function __construct(){
        
    }
    public function CalculatePhoneOrderDiscount(Request $request){ 
        
        if(Session::has('ShoppingCart')){
            Session::forget('ShoppingCart');
        }

        if(Session::has('eusertype')){
            Session::forget('eusertype');
        } 

        if(!Session::has('isPhoneOrder')){
            Session::put('isPhoneOrder','Y');
        }

        if(Session::has('sess_icustomerid')){
            Session::forget('sess_icustomerid');
        }

        if(Session::has('is_dropshipper')){
            Session::forget('is_dropshipper');
        }        

        $eusertype = "";
        $product_details = "";
        $product_arr = "";
        if(isset($_REQUEST['eusertype']) && $_REQUEST['eusertype']!=''){
            $eusertype = $_REQUEST['eusertype'];
            Session::put('eusertype',$eusertype);
        }

        if(isset($_REQUEST['product_details']) && $_REQUEST['product_details']!=''){
            $product_details = $_REQUEST['product_details'];
        }
        $add_to_cart_msg = '';
        if($product_details != ''){
            $product_arr = explode("@",$product_details);
            for($i = 0;  $i < count($product_arr); $i++){
                $prod_arr = explode("*",$product_arr[$i]);
                $products_id = $prod_arr[0]; 
                $quantity = $prod_arr[1];    
                $product_price = $prod_arr[2];               
                //echo $i."--".$products_id."---".$quantity;
                try {    
                    // Session::put('ShoppingCart.PhoneOrderProductPrice.'.$products_id,$product_price);
                    // $this->AddToCart($products_id, $quantity ,$cookiee='No');
                    $chkStock = $this->ProductCheckInStock($products_id, $quantity,"insert",$cookiee='No');
                    $return['chkstock-'.$products_id] = $chkStock;
                    if(isset($chkStock['availableStock']) && $chkStock['availableStock'] <= 0){
                        $return['outofstock'][] = $products_id;                        
                    } else {  
                        Session::put('ShoppingCart.PhoneOrderProductPrice.'.$products_id,$product_price);
                        $this->AddToCart($products_id, $quantity ,$cookiee='No');
                    }
                } catch (Exception $e) {
                    $add_to_cart_msg .= $e->getMessage();
                }    
            }
            if($add_to_cart_msg !=''){
                $return['error_message'] = $add_to_cart_msg;                
            } else {
                $return['error_message'] = '';
            }
            $this->SetCartAttributes();
            if($eusertype!='Wholesaler'){
                $this->ApplyDogoDiscount();
            }            

            $shopping_controller = new ShoppingcartController;
            $request->ShipMethodID = 29;
            $request->action = 'shippinginfo';
            $request->OnlyHead = '0';
            $request->subaction ='stripecart';

            $couponCode = "";
            $rewardcouponCode = "";
            $gift_code = "";
            $shipping_insurance = "";
            $uid = "";
            $sub_total = "";
            $new_coupon_code_arr = array();
            $new_coupon_codes = "";
            $manual_discount = "";

            if(isset($request->country)){
                Session::put('ShoppingCart.ShippingAddress.country',$request->country);
            }
            if(isset($request->state)){
                Session::put('ShoppingCart.ShippingAddress.state',$request->state);
            }
            if(isset($request->zip)){
                Session::put('ShoppingCart.ShippingAddress.zip',$request->zip);
            }
            if(isset($request->coupon_number) && $request->coupon_number!=''){ 
                $couponCode = $request->coupon_number;
            }
            if(isset($request->reward_coupon) && $request->reward_coupon!=''){ 
                $rewardcouponCode = $request->reward_coupon;
            }
            if(isset($request->manual_discount) && $request->manual_discount!='' && $request->manual_discount!='0.00'){ 
                $manual_discount = $request->manual_discount;
                Session::put('ShoppingCart.phoneorder_manual_discount',$manual_discount);
            }

            //21032024 start
            if(isset($request->couponcodes) && $request->couponcodes!=''){ 
                $new_coupon_codes = $request->couponcodes;
                $new_coupon_code_arr = explode(",",$new_coupon_codes);
            }
            //21032024 end
            
            if(isset($request->gift_code) && $request->gift_code!=''){ 
                $gift_code = $request->gift_code;
            }

            if(isset($request->uid) && $request->uid!=''){ 
                $uid = $request->uid;
                Session::put('sess_icustomerid',$uid);
            }

            if(isset($request->is_dropshipper) && $request->is_dropshipper != ''){
                $is_dropshipper = $request->is_dropshipper;
                Session::put('is_dropshipper',$is_dropshipper);
            }

            if(isset($request->sb_total) && $request->sb_total!=''){ 
                $sub_total = $request->sb_total;
            }
            
            if(isset($request->shipping_insurance) && $request->shipping_insurance!=''){ 
                $shipping_insurance = $request->shipping_insurance;
            }
            $cart_data = '';
            if(Session::has('ShoppingCart')){
                $cart_data = Session::get('ShoppingCart');
            }

            $ShopCartItems = "";
            if(Session::has('ShoppingCart.Cart')){
                $ShopCartItems = Session::get('ShoppingCart.Cart');
            }  

            // $ship_country = Session::get('ShoppingCart.ShippingAddress.country');
			// $ship_state = Session::get('ShoppingCart.ShippingAddress.state');
			// $ship_zip = Session::get('ShoppingCart.ShippingAddress.zip');
            
            if($cart_data!='' && $ShopCartItems != ""){        
                //$GetCouponDiscounts = $couponCode!='' ? $this->ApplyCouponDiscount($couponCode, $uid, $sub_total, $eusertype, $OrderDetailCnt=0,$OrderDetailRs=array(),$qty=0,$shipping_amt=0,$tax=0,$shipping_method_id=0,"Yes") : '';
                
                //21032024 start
                $GetCouponDiscounts = array();
                $GetCouponDiscounts['WebsiteDiscountCoupon'] = "";
                $GetCouponDiscounts['RewardDiscountCoupon'] = "";
                $return['WebCouponMessage'] = '';
                $return['YotpoCouponMessage'] = '';

                if($couponCode!=''){
                    $coupontypeArr = array('Yotpo Referral Coupon','Yotpo Reward Coupon');
                    $couponRS = Coupon::where('coupon_number','=',$couponCode)->whereNotIn('coupon_title',$coupontypeArr)->where('is_used','No')->limit(1)->get();                    
                    if($couponRS && $couponRS->count() > 0){                        
                        array_push($new_coupon_code_arr,$couponCode);
                    } else {
                        $return['WebCouponMessage'] = 'Enter valid coupon code';
                    }
                }

                if($rewardcouponCode != ''){
                    $coupontypeArr = array('Yotpo Referral Coupon','Yotpo Reward Coupon');
                    $couponRS = Coupon::where('coupon_number','=',$rewardcouponCode)->whereIn('coupon_title',$coupontypeArr)->where('is_used','No')->limit(1)->get();                    
                    if($couponRS && $couponRS->count() > 0){                        
                        array_push($new_coupon_code_arr,$rewardcouponCode);
                    } else {
                        $return['YotpoCouponMessage'] = 'Enter valid coupon code';
                    }                                       
                }

                //if($uid == "34" && count($new_coupon_code_arr) > 0){
                    for($i = 0; $i < count($new_coupon_code_arr); $i++){
                        $GetCouponDiscounts = $this->ApplyCouponDiscount($new_coupon_code_arr[$i]);
                        If(Session::has('ShoppingCart.YotpoRewardDiscount') && Session::get('ShoppingCart.YotpoRewardDiscount') > 0){
                            $GetCouponDiscounts['RewardDiscountCoupon'] = $new_coupon_code_arr[$i];
                        } else {
                            $GetCouponDiscounts['WebsiteDiscountCoupon'] = $new_coupon_code_arr[$i];
                        }
                        if($GetCouponDiscounts != '' && $GetCouponDiscounts['error']=='0' && Session::has('ShoppingCart.PromoCoupon')){
                            $GetCouponDiscounts['PromoCoupon'] = Session::get('ShoppingCart.PromoCoupon');
                        }                        
                    }
                /*} else {
                    $GetCouponDiscounts = $couponCode!='' ? $this->ApplyCouponDiscount($couponCode) : '';
                    if($GetCouponDiscounts != '' && $GetCouponDiscounts['error']=='0' && Session::has('ShoppingCart.PromoCoupon')){
                        $GetCouponDiscounts['PromoCoupon'] = Session::get('ShoppingCart.PromoCoupon');
                    }
                }*/
                //21032024 end

                // $GetCouponDiscounts = $couponCode!='' ? $this->ApplyCouponDiscount($couponCode) : '';

                // if($GetCouponDiscounts != '' && $GetCouponDiscounts['error']=='0' && Session::has('ShoppingCart.PromoCoupon')){
                //     $GetCouponDiscounts['PromoCoupon'] = Session::get('ShoppingCart.PromoCoupon');
                // }
                $GetGiftDiscounts = $gift_code !='' ? $this->ApplyGiftCoupons($gift_code) : '';
                $available_shipping_methods = array();
                $return['GetShippingMethods'] = $shopping_controller->ShippingMethods($request);
                if(!empty($return['GetShippingMethods'])){
                    for($s = 0; $s < count($return['GetShippingMethods']); $s++){
                        array_push($available_shipping_methods,$return['GetShippingMethods'][$s]['id']);
                    }
                }
                if($shipping_insurance!=''){
                    $return['GetShippingInsuranceCharges'] = $this->SetShippingInsuranceCharge($shipping_insurance);                    
                }                
                $return['GetCouponDiscounts'] = $GetCouponDiscounts;
                $return['GetGiftDiscounts'] = $GetGiftDiscounts; 
                $return['GetAllDiscounts'] = $this->GetAllDiscounts(); 
                $return['GetItemDiscounts'] = $this->GetItemWiseDiscount();
                $return['GetAllCharges'] = $this->GetAllCharges();
                $return['GetCartData'] = json_encode($cart_data);
                $return['chkData'] = $shipping_insurance;
                $return['UnAvailableShippingMethods'] = $this->GetShippingMethodsPhoneOrder($eusertype,$available_shipping_methods);
                //$return['ccodes'] = $new_coupon_codes
            }
            // Session::get('ShoppingCart'); //$ship_country."--".$ship_state."--".$ship_zip; //$request->ajax();
            //$return['GetShippingMethods'] = $shopping_controller->CheckShippingMethod(29,$request->country,$request->state,$request->zip);
            //$return['GetShippingMethods'] = $shopping_controller->CheckShippingMethod(29,$request->country,$request->state,$request->zip);
            
            echo json_encode($return,true);

            if(Session::has('ShoppingCart')){
                Session::forget('ShoppingCart');
            }
            if(Session::has('eusertype')){
                Session::forget('eusertype');
            }
            if(Session::has('isPhoneOrder')){
                Session::forget('isPhoneOrder');
            }
            if(Session::has('sess_icustomerid')){
                Session::forget('sess_icustomerid');
            }    
            if(Session::has('is_dropshipper')){
                Session::forget('is_dropshipper');
            }
            // echo "<pre>";
            // print_r($Discounts);

            // $ShoppingCart = Session::get('ShoppingCart.Cart');
            // Session::forget('isPhoneOrder');
            // echo json_encode($ShoppingCart,true);
            // exit;
            
            // $product_arr = explode("@",$product_details);
            // for($i = 0;  $i < count($product_arr); $i++){
            //     $prod_arr = explode("*",$product_arr[$i]);
            //     $products_id = $prod_arr[0];
            //     $quantity = $prod_arr[1];
            //     $this->AddToCart($products_id, $quantity ,$cookiee='No');
            // }
            // $ShoppingCart = Session::get('ShoppingCart.Cart');
            // echo json_encode($ShoppingCart,true);
        }

        //echo "test";       
        // echo "<pre>";
        // print_r($_REQUEST);
        // exit;
        // $ShoppingCart = Session::get('ShoppingCart.Cart');
        // echo "<pre>";
        // print_r($ShoppingCart);
        // echo "</pre>";
        //return true;
    }

    public function GetShippingMethodsPhoneOrder($eusertype,$available_shipping_methods){
        $ShippingMethodRS = ShippingMode::where('status','=','1')->where('eusertype','=',$eusertype)->whereNotIn('shipping_mode_id',$available_shipping_methods)->get();
        return $ShippingMethodRS;
        //return false;
    }

    public function GetItemWiseDiscount(){
        $retArr = array();
        $tempCart = array();
        if(Session::has('ShoppingCart.Cart')){        
            $tempCart = Session::get('ShoppingCart.Cart');
        }    
		$cnt_row  = count($tempCart);
        for($i=0; $i<$cnt_row; $i++)
		{			
			if(empty($this->GetAllDiscounts('CouponDiscount')) || $this->GetAllDiscounts('CouponDiscount') <= 0)
			{
				$tempCart[$i]['CouponDisItemWiseDiscout'] = 0; 
			}
			if(empty($this->GetAllDiscounts('AutoDiscount')) || $this->GetAllDiscounts('AutoDiscount') <= 0)
			{
				$tempCart[$i]['AutoItemWiseDiscout'] = 0; 
			}
			if(empty($this->GetAllDiscounts('QuantityDiscount')) || $this->GetAllDiscounts('QuantityDiscount') <= 0)
			{
				$tempCart[$i]['QuantityItemWiseDiscout'] = 0; 
			}
			if(empty($this->GetAllDiscounts('YotpoRewardDiscount')) || $this->GetAllDiscounts('YotpoRewardDiscount') <= 0)
			{
				$tempCart[$i]['RewardItemWiseDiscout'] = 0; 
			}
			if(empty($this->GetAllDiscounts('DogoDiscount')) || $this->GetAllDiscounts('DogoDiscount') <= 0)
			{
				$tempCart[$i]['BogoItemWiseDiscout'] = 0; 
			}
			if(empty($this->GetAllDiscounts('GiftCoupon')) || $this->GetAllDiscounts('GiftCoupon') <= 0)
			{
				$tempCart[$i]['GiftCertificateItemWiseDiscout'] = 0; 
			}
			// if(empty($apply_credit) && $apply_credit<=0)
			// {
			// 	$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0; 
			// }
			
				
			$ActualPrice = 0;
			$CouponDisItemWiseDiscout = 0;
			$AutoItemWiseDiscout = 0;
			$QuantityItemWiseDiscout = 0;
			$RewardItemWiseDiscout = 0;
			$BogoItemWiseDiscout = 0;
			$GiftCertificateItemWiseDiscout = 0;
			$CreditLimitItemWiseDiscout = 0;

            $discount_price = '';
				
			if(!empty($tempCart[$i]['CouponDisItemWiseDiscout']) && $tempCart[$i]['CouponDisItemWiseDiscout'] > 0)
			{
				$CouponDisItemWiseDiscout = NumberFormat($tempCart[$i]['CouponDisItemWiseDiscout']);
			}	
			if(!empty($tempCart[$i]['AutoItemWiseDiscout']) && $tempCart[$i]['AutoItemWiseDiscout'] > 0)
			{
				$AutoItemWiseDiscout = NumberFormat($tempCart[$i]['AutoItemWiseDiscout']);
			}		
			if(!empty($tempCart[$i]['QuantityItemWiseDiscout']) && $tempCart[$i]['QuantityItemWiseDiscout'] > 0)
			{
				$QuantityItemWiseDiscout = NumberFormat($tempCart[$i]['QuantityItemWiseDiscout']);
			}
			if(!empty($tempCart[$i]['RewardItemWiseDiscout']) && $tempCart[$i]['RewardItemWiseDiscout'] > 0)
			{
				$RewardItemWiseDiscout = NumberFormat($tempCart[$i]['RewardItemWiseDiscout']);
			}
			if(!empty($tempCart[$i]['BogoItemWiseDiscout']) && $tempCart[$i]['BogoItemWiseDiscout'] > 0)
			{
				$BogoItemWiseDiscout = NumberFormat($tempCart[$i]['BogoItemWiseDiscout']);
			}
			if(!empty($tempCart[$i]['GiftCertificateItemWiseDiscout']) && $tempCart[$i]['GiftCertificateItemWiseDiscout'] > 0)
			{
				$GiftCertificateItemWiseDiscout = NumberFormat($tempCart[$i]['GiftCertificateItemWiseDiscout']);
			}
			if(!empty($tempCart[$i]['CreditLimitItemWiseDiscout']) && $tempCart[$i]['CreditLimitItemWiseDiscout'] > 0)
			{
				$CreditLimitItemWiseDiscout = NumberFormat($tempCart[$i]['CreditLimitItemWiseDiscout']);
			}
			$ActualPrice = $CouponDisItemWiseDiscout + $AutoItemWiseDiscout + $QuantityItemWiseDiscout + $RewardItemWiseDiscout + $BogoItemWiseDiscout + $GiftCertificateItemWiseDiscout + $CreditLimitItemWiseDiscout;
		    if(!empty($ActualPrice) && $ActualPrice > 0)
		    {
			    $ActualPrice = $tempCart[$i]['TotPrice'] - $ActualPrice;
				$ActualPrice = NumberFormat($ActualPrice);
			}
            $tempCart[$i]['PhoneActualPrice'] = $ActualPrice;
            //Auto10,Qty20,Bogo10,Reward5,Coupon6,Giftcertificate10

            if($AutoItemWiseDiscout != 0){
                $discount_price .= ",Auto".$AutoItemWiseDiscout;
            }
			if($QuantityItemWiseDiscout != 0){
                $discount_price .= ",Qty".$QuantityItemWiseDiscout;
            }
            if($BogoItemWiseDiscout != 0){                
                $discount_price .= ",Bogo".$BogoItemWiseDiscout;
            }
            if($RewardItemWiseDiscout != 0){
                $discount_price .= ",Reward".$RewardItemWiseDiscout;
            }
            if($CouponDisItemWiseDiscout != 0){
                $discount_price .= ",Coupon".$CouponDisItemWiseDiscout;
            }
            if($GiftCertificateItemWiseDiscout != 0){
                $discount_price .= ",Giftcertificate".$GiftCertificateItemWiseDiscout;
            }
            if($discount_price != ''){
                $discount_price = substr($discount_price,1);
            }
            $retArr[$i]['discount_price'] = $discount_price;
            $retArr[$i]['product_id'] = $tempCart[$i]['ProductID'];
            // $retArr[$i]['CouponDisItemWiseDiscout'] = $CouponDisItemWiseDiscout;
            // $retArr[$i]['AutoItemWiseDiscout'] = $AutoItemWiseDiscout;
            // $retArr[$i]['QuantityItemWiseDiscout'] = $QuantityItemWiseDiscout;
            // $retArr[$i]['RewardItemWiseDiscout'] = $RewardItemWiseDiscout;
            // $retArr[$i]['BogoItemWiseDiscout'] = $BogoItemWiseDiscout;
            // $retArr[$i]['GiftCertificateItemWiseDiscout'] = $GiftCertificateItemWiseDiscout;
            // $retArr[$i]['CreditLimitItemWiseDiscout'] = $CreditLimitItemWiseDiscout;
            
		}
        //$retArr = $tempCart;
        return $retArr;
    }

    public function GetPhoneOrderToken(){
        echo csrf_token();
    }
}
?>