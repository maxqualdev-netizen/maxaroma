<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Models\MetaInfo;
use App\Models\Category;
use App\Models\Products;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\NewsLetter;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RewardPoint;
use App\Models\ShippingMode;
use App\Models\ShippingRule;
use App\Models\ShippingRate;
use App\Models\ShippingHoliday;
use App\Models\TaxAreas;
use App\Models\TaxRates;
use App\Models\PaymentMethod;
use App\Models\GiftCertificate;
use App\Models\MailBanner;
use App\Models\AmazonOrder;
use App\Models\AmazonOrderDetails;
use App\Models\Dealofweek;
use App\Models\ReferFriend;
use App\Models\Shoppingcart;
use App\Models\PaypalIpnLog;
use App\Models\CustomerCreditLimitLogs;

use Illuminate\Support\Facades\DB;
use Session;
use Cookie;

class ShoppingcartController extends Controller
{
	use CommonTrait;
	use VendorTrait;
	use CartTrait;
	
	public function __construct()
	{
		config(['logging.default' => 'shoppingcart']);
		$this->PageData['CSSFILES'] = ['static.css'];	
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
	}
	
	public function SetCart(Request $request)
	{	
		if($request->ajax()){
			if(isset($request->action))
			{
				if($request->action == 'insert')
				{
					$products_id=(int)$request->products_id;
					if(!isset($request->prodqty) && empty($request->prodqty))
						$quantity = 1;
					else
						$quantity = (int)$request->prodqty;
					return $this->AddToCart($products_id, $quantity ,$cookiee='No');
				}
                if($request->action == 'yotpo_free_gift_insert')
				{
					$products_id=(int)$request->products_id;
					if(!isset($request->prodqty) && empty($request->prodqty))
						$quantity = 1;
					else
						$quantity = (int)$request->prodqty;
                    config(['YotpoFreeGiftCoupon' => $request->yotpo_free_gift_coupon]);
					return $this->AddToCart($products_id, $quantity ,$cookiee='No','No','Yes');
				}
				if($request->action == 'reorder')
				{
					if(isset($request->prodDetails) && count($request->prodDetails) > 0)
					{
						$ProdDetails = $request->prodDetails;
						foreach($ProdDetails as $Prod)
						{
							$products_id=(int)$Prod['productID'];
							$quantity = (int)$Prod['prodqty'];
							$this->AddToCart($products_id, $quantity ,$cookiee='No');
						}
					}
				}
				if($request->action == 'remove')
				{
					return $this->RemoveFromCart($request->CartID);
				}
				if($request->action == 'update')
				{
					$products_id=(int)$request->products_id;
					$giftwrap = 'No';
					if(isset($request->giftwrap) && $request->giftwrap == 'Yes')
						$giftwrap = $request->giftwrap;
					if(!isset($request->prodqty) && empty($request->prodqty))
						$quantity = 1;
					else
						$quantity = (int)$request->prodqty;
					return $this->UpdateCart($products_id,$quantity,$giftwrap);
				}
				if($request->action == 'apply_coupon')
				{
					$CouponNumber = $request->coupon_number;
					$CustomerID = (Auth::user()?Session::get('sess_icustomerid'):null);
					$result = $this->ApplyCouponDiscount($CouponNumber,$CustomerID);
					$this->SetupCart();
					return response()->json(['error' => $result['error'],'message' => $result['message']]);
				}
				if($request->action == 'apply_credit_limit')
				{
					$res = $this->ApplyCreditDiscount(1);
					$Msg = "";
					if(Auth::user() && Auth::user()->eusertype == 'Wholesaler')
						$Msg = 'Applied credit limit successfully.';
					else	
						$Msg = 'Applied store credit successfully.';
					$this->SetupCart();
					return response()->json(['message' => $Msg,'NetTotal' => $this->GetNetTotal()]);
				}
				if($request->action == 'remove_credit_limit')
				{
					Session::put('ShoppingCart.credit_limit_discount',0);
					Session::put('ShoppingCart.customer_remaining_credit_amount',0);
					$Msg = "";
					if(Auth::user() && Auth::user()->eusertype == 'Wholesaler')
						$Msg = 'Applied Credit Limit removed successfully.';
					else	
						$Msg = 'Applied Store Credit removed successfully.';
					$this->SetupCart();
					return response()->json(['message' => $Msg,'NetTotal' => $this->GetNetTotal()]);
				}
				if($request->action == 'apply_gift_coupon')
				{
					if(isset($request->giftcard) && $request->giftcard != '')
					{
						$error = 0;
						if($this->ApplyGiftCoupons($request->giftcard)){
							$Msg = 'E-gift card code is successfully applied.';
							$error = 0;
						}else{	
							$Msg = 'E-gift card code is invalid or does not exists!';
							$error = 1;
						}
						$this->SetupCart();
						return response()->json(['error' => $error,'message' => $Msg]);
					}	
				}
				if($request->action == 'remove_gift_coupon')
				{
					Session::put('ShoppingCart.GiftCoupon.Code','');
					Session::put('ShoppingCart.GiftCoupon.Value','');
					Session::put('ShoppingCart.GiftCoupon.Applicable_Value','');
					$Msg = "Applied E-gift card code removed successfully.";
					$this->SetupCart();
					return response()->json(['message' => $Msg]);	
				}
				if($request->action == 'remove_coupon')
				{
					Session::put('ShoppingCart.PromoCoupon.FirstCouponDiscount',0);
					Session::put('ShoppingCart.PromoCoupon.SecondCouponDiscount',0);
					Session::put('ShoppingCart.PromoCoupon.CouponCode','');
					Session::put('ShoppingCart.PromoCoupon.CouponDiscount',0);
					Session::put('ShoppingCart.PromoCoupon.FreeShippingCouponModeIDFlag','');
					Session::put('ShoppingCart.PromoCoupon.FreeShipping','');
					Session::put('ShoppingCart.PromoCoupon.FreeShippingCouponModeID','');
					$Msg = "Applied coupon code removed successfully.";
					$this->SetupCart();
					return response()->json(['message' => $Msg]);	
				}
				if($request->action == 'clear_bag')
				{
					Session::forget('ShoppingCart');
                    /** OMANISEND **/ 
                        OmanisendRequest('removeCart');
                    /** OMANISEND **/
                    $this->StoreShopCartInCookie();
					return true;
				}
				if($request->action == 'FreeGift')
				{
					$freeproductsid = $request->freeproductsid;
					$products_id 	= (int)$request->products_id;
					$FreeGiftMsg = $this->FreeGiftInsertProductValue($products_id,$freeproductsid);
					if(trim($FreeGiftMsg) == "")
					{
						$Msg = "Free Gift Product Added Successfully";
						Session::flash('CartSuccess',$Msg);
					}else{ 
						$Msg = $FreeGiftMsg;
						Session::flash('CartError',$Msg);
					}
					//$Msg = "Free Gift Product Added Successfully";
					
					$this->SetupCart();
					return response()->json(['message' => $Msg]);
				}
				if($request->action == 'apply_free_gift')
				{
					if(isset($request->GiftValue) && $request->GiftValue != '' )
					{
						if(strtolower(trim($request->GiftMessage))=="*gift message")
						{
							$request->GiftMessage = '';
						}	
						return $this->ApplyFreeGift($request->GiftValue,$request->GiftFrom,$request->GiftTo,$request->GiftMessage);
					}
					else
					{
						return response()->json(['success' => '0','message' => 'Error']);
					}
				}
			}
		}	
	}
	
	public function RemoveFromCart($CartID)
	{
		$ShoppingCart = Session::get('ShoppingCart.Cart');
		if($CartID != '' && $ShoppingCart != null && count($ShoppingCart) > 0)
		{
            $IsYotpoFreeProduct = 'No';
			if($CartID >= 0)
            {
                if(isset($ShoppingCart[$CartID]['IsYotpoFreeProduct']) && $ShoppingCart[$CartID]['IsYotpoFreeProduct'] == 'Yes')
                {
                    $IsYotpoFreeProduct = 'Yes';
                    Session::forget('ShoppingCart.YotpoFreeGiftCoupon');
                }
				unset($ShoppingCart[$CartID]);
            }
			$ShoppingCart = array_values($ShoppingCart);
            if(count($ShoppingCart) == 1 && isset($ShoppingCart[0]['IsYotpoFreeProduct']) && $ShoppingCart[0]['IsYotpoFreeProduct'] == 'Yes')
            {
                $ShoppingCart = [];
            }
			Session::put('ShoppingCart.Cart',$ShoppingCart);
			$this->CalculateSubTotal();
			$this->SetupCart();
			$Msg = "Item removed successfully.";
            /** OMANISEND **/ 
            OmanisendRequest('setCart',['CartData' => Session::get('ShoppingCart')]);
            /** OMANISEND **/
			return response()->json(['message' => $Msg,'IsYotpoFreeProduct' => $IsYotpoFreeProduct]);
		}
	}
	
	public function ShoppingcartPage(Request $Request)
	{
		$allCartItems = '';
		$this->PageData['CSSFILES'] = ['slick.css','shoppingcart.css'];	
		$this->PageData['JSFILES'] = ['slick.js','afterpay.js','shoppingcart_page.js'];		
		$this->PageData['meta_title'] = "Shopping Bag :: ".config('Settings.SITE_TITLE');
		//Log::channel('customer')->info('You are on Shopping Cart Page'); 
		//Session::forget('ShoppingCart');
		
		if(config('global.SHOPP_STATUS') == 'Close')
		{
			Session::forget('ShoppingCart');
			return redirect('/');	
		}
		$this->SetShippingInsuranceCharge('remove');		
		
		$this->SetupCart();
		
		if(Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0)
		{
			$CartInfo = Session::get('ShoppingCart.Cart');
			$SKUINCART = [];
			/* foreach($CartInfo as $Cart)
				$SKUINCART[]=$Cart['SKU']; */
				foreach($CartInfo as $Cart){
					$allCartItems .= $Cart['SKU'].",";
					$SKUINCART[]=$Cart['SKU'];
				}
				$allCartItems = rtrim($allCartItems,",");

			$Filters['NotProductSKUs']= $SKUINCART;
			//$RecentViewProducts = $this->GetProducts('ShoppingCart','',12,$Filters);
			$RecentViewProducts['Products'] = [];
			$this->PageData['RecentViewProducts'] = $RecentViewProducts['Products'];	
			$this->PageData['RecentViewAttr'] = [
				'Title' => 'We think you’ll also love',
				'Slider' => 'products-slider',
				'SeeMore' => '',
			];
		}
		if(!Session::has('Afterpay.Min_AP_AMT') && !Session::has('Afterpay.Max_AP_AMT'))
		{
			$this->AfterpayMinMax();
		}
		$this->SetAmazonConfig('shoppingcart');
		
		$GTMDATA = ['page' => 'shoppingcart', 'pagetype' => 'cart'];
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		$this->PageData['body_class'] = 'cart-body';
		$this->PageData['allCartItems'] = $allCartItems;
		return view('shoppingcart.cart')->with($this->PageData);		
	}
	
	public function SetupCart()
	{
		if(!Session::has('eusertype') || (Session::has('eusertype') && strtolower(trim(Session::has('eusertype')) != 'wholesaler')))
		{	
			$this->ApplyAutoDiscount();
			$this->ApplyQuantityDiscount();
		}
        if(Session::has('Niche_Fragrances_Membership') && Session::get('Niche_Fragrances_Membership') == 'Yes')
		{
			$cname = trim(config('Settings.NICHEFRAGRANCESCODE'));
			$niche_res = Coupon::select('coupon_number')->where('coupon_number','=',$cname)->limit(1)->get();
			if($niche_res && $niche_res->count() > 0)
			{
				$this->ApplyCouponDiscount($cname,Session::get('sess_icustomerid'));
			}
		}
		$this->ApplyDogoDiscount();
		if(config('global.YOTPO_PROG') == false)
        { 
			$this->ApplyAutoRewardDiscount();
		}
		if(Session::has('ShoppingCart.credit_limit_discount') && Session::get('ShoppingCart.credit_limit_discount') > 0)
		{
			$this->ApplyCreditDiscount(1);
		}
		
		$CartAttr = $this->SetCartAttributes();
		$this->PageData['CartAttr'] = $CartAttr;
		$this->ApplyGiftWrapping();
		
		if($CartAttr['onlyGCPurchased'] == 1)
		{
			Session::forget('ShoppingCart.Shipping');
			Session::forget('ShoppingCart.Tax');
			Session::forget('ShoppingCart.GiftWrapping');
			Session::forget('ShoppingCart.ShippingSignature');
			Session::forget('shipping_insurance_charge');
			Session::forget('ShoppingCart.ShippingSignature');
		}
		
		$AllCharges = $this->GetAllCharges();
		$this->PageData['AllCharges'] = $AllCharges['Charges'];
		$AllDiscounts = $this->GetAllDiscounts();
		$this->PageData['AllDiscounts'] = $AllDiscounts['Discounts'];
		$CreditDiscount = (isset($AllDiscounts['Discounts']['CreditLimitDiscount']['discount']) ? $AllDiscounts['Discounts']['CreditLimitDiscount']['discount']:0); 		
		$CreditLimit = (isset($CartAttr["CreditLimit"])?$CartAttr["CreditLimit"]:0);
		$this->PageData['CreditDiscount'] = $CreditDiscount;
		if(Auth::user() && ($CreditLimit <= 0 || config('Settings.WHOLESALE_CREDIT_LIMIT') != 'Yes' || Session::get("etype") != "M" || Auth::user()->is_dropshipper == "Yes") && $CreditDiscount > 0)
		{
			Session::put('ShoppingCart.credit_limit_discount',0);
			Session::put('ShoppingCart.customer_remaining_credit_amount',0);
		}
		
		if(Session::has('ShoppingCart.GiftCoupon.Code') && Session::get('ShoppingCart.GiftCoupon.Code') !='' )
		{
			Session::put('ShoppingCart.GiftCoupon.Value', 0.0);
			Session::put('ShoppingCart.GiftCoupon.Applicable_Value', 0.0);
			$this->ApplyGiftCoupons(Session::get('ShoppingCart.GiftCoupon.Code'));
		}
		
		$AllDiscounts = $this->GetAllDiscounts();
		$TotalValue = NumberFormat(Session::get('ShoppingCart.SubTotal')) - $AllDiscounts['TotalDiscount'];
		
		$Gift_Free_In_Cart = $this->CheckFreeGiftInCart($TotalValue);
		
		

		
		
		
		if($Gift_Free_In_Cart == 'No')
		{
			$this->SetFreegift($Gift_Free_In_Cart);
			/*
			if(strtolower(trim(Session::get('eusertype'))) !="wholesaler" && trim(Session::get('is_dropshipper')) != "Yes" && $Gift_Free_In_Cart == "No")
			{
				$Free_Gift_Res = $this->GetFreeCouponPopup($this->GetNetTotal());
				
				if(count($Free_Gift_Res) == 1)
				{
					$products_id = $Free_Gift_Res[0]['products_id'];
					$freeproductsid = $Free_Gift_Res[0]['free_gift_products_id'];
					$this->FreeGiftInsertProductValue($products_id,$freeproductsid);
					$Msg = "Free Gift Product Added Successfully";
					Session::flash('CartSuccess',$Msg);	
				}
			}	*/			
		}
		
		$this->PageData['NetTotal'] = $this->GetNetTotal();
		$this->StoreShopCartInCookie();
	}
	
	public function GetCartHTML(Request $request)
	{
		if($request->ajax())
		{
			$ShoppingCart = [];
			$TotalItemInCart = 0;
			if(Session::has('ShoppingCart'))
			{
				$ShoppingCart = Session::get('ShoppingCart');
				if(isset($ShoppingCart['Cart']) && count($ShoppingCart['Cart']) > 0)
					$TotalItemInCart = $ShoppingCart['TotalItemInCart'];
			}	
			
			$CartAttr = $this->SetCartAttributes();
			$ShoppingCart['IsPaypalExpressCheckout'] = $CartAttr['IsPaypalExpressCheckout'];
			$ShoppingCart['Amazon_pay_Checkout'] = $CartAttr['Amazon_pay_Checkout'];
			$ShoppingCart['Afterpay_Checkout'] = $CartAttr['Afterpay_Checkout'];
			$this->PageData['CartDetails'] = $ShoppingCart;
			$this->SetAmazonConfig();
			$this->StoreShopCartInCookie();
			$MerchantID = config('MERCHANT_ID');
			$CallBackURL = config('CALLBACK_CHECKOUT_URL');
			$CartHTML = view('layouts.sidecart_ajax')->with($this->PageData)->render();
			return response()->json(array('ShoppingCart' => $CartHTML,'TotalItemInCart' => $TotalItemInCart, 'MerchantID' => $MerchantID, 'CallBackURL' => $CallBackURL));
		}
	}
	
	public function UpdateCart($products_id,$prodqty,$giftwrap='No')
	{
		$ProductChkStock = $this->ProductCheckInStock($products_id, $prodqty,"update");	
		$CartErrors = [];
		if($ProductChkStock == '1111')
			$CartErrors[] = config('message.Cart.ProductNotAvailable');
		if($ProductChkStock == '2222')
			$CartErrors[] = config('message.Cart.QuantityNotAvailable');
		if(count($CartErrors) > 0)
		{
			Session::flash('CartErrors', $CartErrors);
			return response()->json(array('Update' => 0,'CartErrors' => $CartErrors));
		}
		$ProductChkFlg = $this->ProductCheckInCart($products_id, $prodqty, 'update','No',$giftwrap);
		$this->CalculateSubTotal();
		$this->SetupCart();
         /** OMANISEND **/ 
        OmanisendRequest('setCart',['CartData' => Session::get('ShoppingCart')]);
        /** OMANISEND **/
	}

	public function GetCartPartial(Request $request)
	{
		$allCartItems = '';
		if($request->ajax())
		{
			$ShoppingCart = [];
			$TotalItemInCart = 0;
			if(Session::has('ShoppingCart'))
			{
				$this->SetupCart();
				$ShoppingCart = Session::get('Shoppin