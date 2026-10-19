<?php
namespace App\Http\Controllers;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\EncryptTrait;

use App\Models\Order;
use App\Models\Customer;
use App\Models\PaymentMethod;

use App\Models\DropshipperOrder;
use App\Models\DropshipperOrderDetail;
use App\Models\OrderDetail;
use App\Models\ShippingMode;
use App\Models\Products;

use Session;
use URL;

class PaypalController extends Controller
{
	use CartTrait;
	use EncryptTrait;
	
	public function __construct()
	{
		$db_res = PaymentMethod::select('pm_group_name', 'pm_gateway_name','pm_details')
							->where('pm_group_name','=', 'PAYMENT_PAYPALEC')
							->where('pm_status', '=', 'Active')
							->get();
		if($db_res->count() > 0)
		{
			$arrPEVar		= unserialize($db_res[0]->pm_details);
			$Mode = trim(strtolower($arrPEVar['paypalec_Transaction_Mode']));
			/*
			//if(Session::has('sess_useremail') && Session::get('sess_useremail') == 'gequaldev@gmail.com'){
				$Mode = "sandbox";
				
				$PaypalConfig = [
					'mode'    => $Mode, 
					$Mode => [
						'username'    => 'hitumca33-facilitator_api1.gmail.com',
						'password'    => '1366188040', //'1366188040'
						'secret'      => 'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF', //'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF'
						'certificate' => '',
						'app_id'      => '',
					],
					'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
					'currency'       => 'USD',
					'billing_type'   => 'MerchantInitiatedBilling',
					'notify_url'     => '', // Change this accordingly for your application.
					'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
					'validate_ssl'   => true, // Validate SSL when creating api client.
				];
			} else {
			*/	
				$PaypalConfig = [
					'mode'    => $Mode, 
					$Mode => [
						'username'    => $this->decrypt($arrPEVar['paypalec_Username']),
						'password'    => $this->decrypt($arrPEVar['paypalec_Password']),
						'secret'      => $this->decrypt($arrPEVar['paypalec_Signature']),
						'certificate' => '',
						'app_id'      => '',
					],
					'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
					'currency'       => 'USD',
					'billing_type'   => 'MerchantInitiatedBilling',
					'notify_url'     => '', // Change this accordingly for your application.
					'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
					'validate_ssl'   => true, // Validate SSL when creating api client.
				];
			//}
			
			
			/* if($_SERVER['HTTP_X_FORWARDED_FOR'] == "27.109.8.106"){
				$Mode = "sandbox";
					
					$PaypalConfig = [
						'mode'    => $Mode, 
						$Mode => [
							'username'    => 'hitumca33-facilitator_api1.gmail.com',
							'password'    => '1366188040', //'1366188040'
							'secret'      => 'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF', //'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF'
							'certificate' => '',
							'app_id'      => '',
						],
						'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
						'currency'       => 'USD',
						'billing_type'   => 'MerchantInitiatedBilling',
						'notify_url'     => '', // Change this accordingly for your application.
						'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
						'validate_ssl'   => true, // Validate SSL when creating api client.
					];
			} */
			
			$this->provider = new ExpressCheckout;
			//$this->provider->setApiCredentials(config('paypal'));
			$this->provider->setApiCredentials($PaypalConfig);
		}
		
	}
	
	public function SetPaypal(Request $request)
	{
		if(isset($request->dropsipflag) && $request->dropsipflag == 'dropship')
		{
			$data = [];
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			$OrderTotal  = Session::get('DropShipperOrderAmount');
			$data['return_url'] = url('/paypal/success/dropship');
			$data['cancel_url'] = url('/imported-order-list.html');
			$data['subtotal'] = $OrderTotal;
			$data['total'] = $OrderTotal;
			$data['items'][] = 
					[
						'name' 	=> 'Dropshipper Order',
						'price'	=> $OrderTotal,
						'desc' 	=> "Dropshipper Order",
						'qty' 	=> 1
					];
			$options = [
						'noshipping' => 0,
						'LOGOIMG' => 'https://www.maxaroma.com/images/logo.jpg',
						'BRANDNAME' => 'Maxaroma',
					];
					
			$response = $this->provider->addOptions($options)->setExpressCheckout($data);
			
			if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
				return redirect($response['paypal_link']);
			}else{
				$ErrorCode 			= $response["L_ERRORCODE0"];
				$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
				$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
				$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];	
				Session::flash('CartError',$ErrorLongMsg);								
				return redirect('/imported-order-list.html');
			}
		} else {	
			if($this->Is_WholeSaler_Allow() == false)
			{
				return redirect('/shoppingcart');
			}
			
			if(Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) <= 0)
			{
				Session::forget('ShoppingCart');
				return redirect('/shoppingcart');	
			}	
			$tempBillingAdd  = Session::get('ShoppingCart.BillingAddress');
			$tempShippingAdd = Session::get('ShoppingCart.ShippingAddress');
			$OrderSubTotal 	 =  Session::get('ShoppingCart.SubTotal');
			$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
			$GiftValue = 0;
			if($GiftCouponInfo && count($GiftCouponInfo) > 0)
			{
				$GiftValue = $GiftCouponInfo['Value'];
			}
			$NetDiscount 	 = $this->GetAllDiscounts();
			$TotalDiscount 	 = NumberFormat($NetDiscount['TotalDiscount'] + $GiftValue);
			$OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);

			$data = [];
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			
			$ShopCart = Session::get('ShoppingCart.Cart');
			$data['items'] = array();
			if(is_array($ShopCart) && $ShopCart)
			{
				foreach($ShopCart as $key => $CartItem)
				{
					$data['items'][] = [
						'name' => $CartItem['ProductName'],
						'price' => $CartItem['Price'],
						'qty' => $CartItem['Qty']
					];
				}
			}
			$data['return_url'] = url('/paypal/success');
			$data['cancel_url'] = url('/checkout');
			$data['subtotal'] = $OrderSubTotal;
			$data['total'] = $OrderSubTotal;
			if($TotalDiscount > 0)
			{
				$data['items'][] = 
					[
						'name' 	=> 'All Discount',
						'price'	=> -$TotalDiscount,
						'desc' 	=> urlencode("All Discounts"),
						'qty' 	=> 1
					];
			}
			$options = [
						'noshipping' => 0,
						'LOGOIMG' => 'https://www.maxaroma.com/images/logo.jpg',
						'BRANDNAME' => 'Maxaroma',
					];
						
			$response = $this->provider->addOptions($options)->setExpressCheckout($data);
			
			if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
				return redirect($response['paypal_link']);
			}else{
				$ErrorCode 			= $response["L_ERRORCODE0"];
				$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
				$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
				$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];	
				Session::flash('CartError',$ErrorLongMsg);								
				return redirect('shoppingcart');
			}
		}		
	}

	public function Success(Request $request)
	{
		$token = "";

		if (!empty($request->token))	
		{	
			Session::forget('PayPalToken');			
			Session::put('PayPalToken',$request->token);
			Session::save();
		}
		## token check end here 

		## if token not set then redired on shoppin cart
		if(!Session::has("PayPalToken") || empty(Session::get("PayPalToken")))
		{
			$ErrorLongMsg = "Error in Processing Request, Please try again.";
			Session::flash('CartError',$ErrorLongMsg);
			return redirect('shoppingcart');
		}
	

		$response = $this->provider->getExpressCheckoutDetails($request->token);
		// dd($response);

		if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
			
			Session::put('PAYPAL_PAYER_ID',$response['PAYERID']);
			Session::save();
			
			$name = explode(" ",trim($response['SHIPTONAME']));
			$first_name = $name[0];
			if(isset($name[1]) && $name[1] != '') {
				$last_name = $name[1];
			} else {
				$last_name = '';
			}

			## Billing Address Start Here
			$Billing = [];
			$Billing['bill_fname']	  		= $first_name;
			$Billing['bill_lname']	  		= $last_name;
			$Billing['bill_company']				= '';
			$Billing['bill_address1']	  		= $response['SHIPTOSTREET'];

			if(isset($response['SHIPTOSTREET2'])) {
				$Billing['bill_address2'] 	= $response['SHIPTOSTREET2'];
			} else {
				$Billing['bill_address2']	= '';
			}

			$Billing['bill_city']	  		= (isset($response['SHIPTOCITY'])?$response['SHIPTOCITY']:'');
			$Billing['bill_country']		= $response['SHIPTOCOUNTRYCODE'];
			if($Billing['bill_country'] != 'US') {
				$Billing['bill_other_state'] 	= (isset($response['SHIPTOSTATE'])?$response['SHIPTOSTATE']:'');
			} else {
				$Billing['bill_state'] 	  		= (isset($response['SHIPTOSTATE'])?$response['SHIPTOSTATE']:'');
			}
			$Billing['bill_zip']			= (isset($response['SHIPTOZIP'])?$response['SHIPTOZIP']:'');
			$Billing['bill_phone']	  		= '';
			$Billing['bill_email'] 	  		= $response['EMAIL'];
			$Billing['bill_cemail'] 	  	= $response['EMAIL'];
			$Billing['sameasbill'] 	  		= 'Yes';
			
			if(isset($request->dropsipflag) && $request->dropsipflag == 'dropship')
			{
				Session::put('tempShippingAdd1Val',$Billing);
				Session::put('tempBillingAdd1Val',$Billing);
				return redirect('paypal/dopayment/dropship');
			} else {
				$this->SetBillingAddress($Billing);			
				$this->SetShippingAddress($Billing);			
				return redirect('checkout/paypal');
			}
		}
		else	
		{
			$ErrorCode 			= $response["L_ERRORCODE0"];
			$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
			$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
			$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];
			
			Session::flash('CartError',$ErrorLongMsg);
			if(isset($request->dropsipflag) && $request->dropsipflag == 'dropship')
			{
				return redirect('/imported-order-list.html');
			} else {
				return redirect('shoppingcart');
			}
		}		
	}

	public function Cancel(Request $request)
	{
		## 'This method is not in use.'
		dd($request->all());
	}

	public function DoPayment(Request $request)
	{
		$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
		$order_id = Session::get('ShoppingCart.OrderID');	
		
		if(Session::has('ShoppingCart.OrderID') && Session::get('ShoppingCart.OrderID') > 0)
		{
			$order_id = Session::get('ShoppingCart.OrderID');
		}
		else if(isset($request->dropsipflag) && $request->dropsipflag != 'dropship' && $request->dropsipflag > 0)
		{
			$order_id = $request->dropsipflag;
		}
		
		$sessionDetails	= Session::get('ShoppingCart.BillingAddress');
		
		$customerEmail = '';
		if(isset($sessionDetails['email']) && $sessionDetails['email']!='')
		{
			$customerEmail	= $sessionDetails['email'];
		}
		
		$orderNo		= 'OR'.$order_id;
		$customerName 	= '';
		if((isset($sessionDetails['first_name']) && $sessionDetails['first_name']!=''))
		{
			$customerName	= $sessionDetails['first_name']." ";
		}
		
		if(isset($sessionDetails['last_name']) && $sessionDetails['last_name'])
		{
			$customerName = $customerName.$sessionDetails['last_name'];
		}
		
		if(fopen($myFile, 'a+'))
		{
			$fh = fopen($myFile, 'a+');

			$stringData = date("m/d/Y H:i:s")." And ".$order_id." : Paypal DoPayment Function With Paypal Token2 In Paypal Controller.\n";
			fwrite($fh, $stringData);
			fclose($fh);
		}			
		
		if(!Session::has("PayPalToken") && empty(Session::get("PayPalToken")))
		{
			$ErrorLongMsg = "Error in Processing Request, Please try again.";
			Session::flash('CartError',$ErrorLongMsg);
			if(isset($request->dropsipflag) && $request->dropsipflag == 'dropship')
			{
				
				$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
			
				if(fopen($myFile, 'a+'))
				{
					$fh = fopen($myFile, 'a+');

					$stringData = date("m/d/Y H:i:s")." And ".$order_id." : Paypal DoPayment Function With Paypal Token1 In Paypal Controller.\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}			
				
				return redirect('imported-order-list.html');
			}else{
				
				$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
			
				if(fopen($myFile, 'a+'))
				{
					$fh = fopen($myFile, 'a+');

					$stringData = date("m/d/Y H:i:s")." And ".$order_id." : Paypal DoPayment Function With Paypal Token In Paypal Controller.\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}								
				
				if(isset($order_id) && $order_id > 0)
				{
					$transaction_info = "This transaction has been Declined.";

					$updAray = array (
										'status' 	   				=> 'Declined',
										'transaction_info' 			=> $transaction_info
									  );
												
					$updOrder = Order::Where("orders_id","=",$order_id)
										->update($updAray);
										
					$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
					
					/*$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
					
					$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e',$Data);*/
				}
				return redirect('shoppingcart');
			}
		}
		
		if(isset($request->dropsipflag) && $request->dropsipflag == 'dropship')
		{
			$data = [];
			$tempBillingAdd  = Session::get('tempBillingAdd1Val');
			$tempShippingAdd = Session::get('tempShippingAdd1Val');
			
			$data = [];
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			$OrderTotal  = Session::get('DropShipperOrderAmount');
			$data['return_url'] = url('/paypal/success/dropship');
			$data['cancel_url'] = url('/imported-order-list.html');
			$data['subtotal'] = $OrderTotal;
			$data['total'] = $OrderTotal;
			$data['items'][] = 
					[
						'name' 	=> 'Dropshipper Order',
						'price'	=> $OrderTotal,
						'desc' 	=> "Dropshipper Order",
						'qty' 	=> 1
					];
			$payerId = Session::get("PAYPAL_PAYER_ID");
			$token = Session::get("PayPalToken");
			$payment_response = $this->provider->doExpressCheckoutPayment($data, $token, $payerId,"dropship");

			if (in_array(strtoupper($payment_response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
				
				$payerID 	   		= $payerId;
				
				$ACK 				= $payment_response["ACK"];
				$CORRELATIONID 		= $payment_response["CORRELATIONID"];
				$TIMESTAMP 			= $payment_response["TIMESTAMP"];
				$ORDERTIME 			= $payment_response["PAYMENTINFO_0_ORDERTIME"];
				$PAYMENTSTATUS 		= $payment_response["PAYMENTINFO_0_PAYMENTSTATUS"];
				$PENDINGREASON 		= $payment_response["PAYMENTINFO_0_PENDINGREASON"];
				$REASONCODE 		= $payment_response["PAYMENTINFO_0_REASONCODE"];
				$TRANSACTIONID		= $payment_response["PAYMENTINFO_0_TRANSACTIONID"]; 
				$TRANSACTIONTYPE 	= $payment_response["PAYMENTINFO_0_TRANSACTIONTYPE"]; 
				$PAYMENTTYPE		= $payment_response["PAYMENTINFO_0_PAYMENTTYPE"];  
				$ORDERAMOUNT		= $payment_response["PAYMENTINFO_0_AMT"];  
				$CURRENCYCODE		= $payment_response["PAYMENTINFO_0_CURRENCYCODE"];  
				
				$TRANSACTIONCHARGE	= 0;
				if(isset($payment_response["PAYMENTINFO_0_FEEAMT"])) {
					$TRANSACTIONCHARGE	= $payment_response["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
				}
				if(isset($payment_response["SETTLEAMT"])) {
					$settleAmt			= $payment_response["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
				} else {
					$settleAmt			= 0;
				}
				$taxAmt				= $payment_response["PAYMENTINFO_0_TAXAMT"];  //' Tax charged on the transaction.

				if(isset($payment_response["EXCHANGERATE"])) {
					$exchangeRate		= $payment_response["EXCHANGERATE"];
				} else {
					$exchangeRate		= 0;
				}
				
				$currency_info = Session::get('currency_code')."#".Session::get('currency_symbol')."#".Session::get('currency_rate');
				$transaction_info = "This transaction has been approved.";

				$payment_gateway_response ="ACK=".$ACK." -- "."PAYER ID=".$payerID." -- TIMESTAMP=".$TIMESTAMP." -- CORRELATIONID=".$CORRELATIONID." -- "."TRANSACTION ID=".$TRANSACTIONID." -- "."TRANSACTION TYPE=".$TRANSACTIONTYPE." -- "."PAYMENT TYPE=".$PAYMENTTYPE." -- ORDERTIME=".$ORDERTIME." -- PAYMENTSTATUS=".$PAYMENTSTATUS." -- PENDINGREASON=".$PENDINGREASON." -- REASONCODE=".$REASONCODE." -- ORDERAMOUNT=".$ORDERAMOUNT;
				$UpdateOrderNoArr =  Session::get("UpdateOrderNoArr");
				
				if(count($UpdateOrderNoArr) > 0)
				{
					for($i=0; $i<count($UpdateOrderNoArr);$i++)
					{
						$dropshipper_order_res = DropshipperOrder::where('orders_id','=',$UpdateOrderNoArr[$i])->get();
						$TotalOrder = $dropshipper_order_res->count();
						if($TotalOrder > 0)
						{
							$ShippingMethodRS = ShippingMode::where('shipping_mode_id','=',$dropshipper_order_res[0]["shippingModeId"])->where('status','=','1')->get();

							$fullShippingname = '';
							$shippingDays = $dropshipper_order_res[0]["shipping_days"];
							//$estimateShipDate = getShipmentEstimateDate($shippingDays);
							$estimateShipDate = "";
							if($dropshipper_order_res[0]["shipping_amt"] > 0)
							{
								$fullShippingname =  $ShippingMethodRS[0]['type']. " <b>(".Session::get('currency_symbol').$dropshipper_order_res[0]["shipping_amt"].")</b> ".$estimateShipDate;
							}
							else
							{
								$fullShippingname =  $ShippingMethodRS[0]['type']. " <b>(Free)</b> ".$estimateShipDate;
							}

							$OrderInsert = array (
								'customer_id'				=> Session::get('sess_icustomerid'),
								'dropshipper_order_no'	 	=> $dropshipper_order_res[0]["orders_no"],
								'sub_total' 				=> $dropshipper_order_res[0]["sub_total"],
								'shipping_amt' 				=> $dropshipper_order_res[0]["shipping_amt"],
								'tax' 						=> $dropshipper_order_res[0]["tax"],
								'gift_charge' 				=> $dropshipper_order_res[0]['gift_charge'],
								'gift_message' 				=> '',
								'is_gift_order'				=> 'No',
								'handling_charge' 			=> '0.00',
								'wire_discount' 			=> '0.00',
								'auto_discount' 			=> 0,
								'quantity_discount'			=> 0,
								'reward_discount'			=> 0,
								'coupon_amount' 			=> 0,
								'coupon_id' 				=> $dropshipper_order_res[0]["coupon_id"],
								'Second_coupon_id'			=> $dropshipper_order_res[0]["Second_coupon_id"],
								'coupon_code' 				=> $dropshipper_order_res[0]["coupon_code"],
								'gc_amount' 				=> $dropshipper_order_res[0]["gc_amount"],
								'gc_code' 					=> $dropshipper_order_res[0]["gc_code"],
								'refer_id'					=> $dropshipper_order_res[0]["refer_id"],
								'refer_amount' 				=> $dropshipper_order_res[0]["refer_amount"],
								'order_total' 				=> $dropshipper_order_res[0]["order_total"],
								'shipinfo' 					=> $ShippingMethodRS[0]['type'],
								'payment_type' 				=> "PAYMENT_PAYPALEC",
								'payment_method' 			=> "Paypal Express Checkout",
								'pay_status' 				=> 'Paid',
								'ccinfo' 					=> '',
								'customer_comment' 			=> $dropshipper_order_res[0]['customer_comment'],
								'status'					=> 'Pending',
								'currency_info'				=> $currency_info,
								'checkout_type' 			=> Session::get('etype'),
								'user_type' 				=> Session::get('eusertype'),
								'ilevelid' 					=> '0',
								'level_price' 				=> $dropshipper_order_res[0]['level_price'],
								'ship_first_name' 			=> $dropshipper_order_res[0]['ship_first_name'],
								'ship_last_name' 			=> $dropshipper_order_res[0]['ship_last_name'],
								'ship_company' 				=> $dropshipper_order_res[0]['ship_company'],
								'ship_email' 				=> $dropshipper_order_res[0]['ship_email'],
								'ship_address1' 			=> $dropshipper_order_res[0]['ship_address1'],
								'ship_address2' 			=> $dropshipper_order_res[0]['ship_address2'],
								'ship_city' 				=> $dropshipper_order_res[0]['ship_city'],
								'ship_zip' 					=> $dropshipper_order_res[0]['ship_zip'],
								'ship_state' 				=> $dropshipper_order_res[0]['ship_state'],
								'ship_country' 				=> $dropshipper_order_res[0]['ship_country'],
								'ship_phone' 				=> $dropshipper_order_res[0]['ship_phone'],
								'bill_first_name' 			=> $dropshipper_order_res[0]['bill_first_name'],
								'bill_last_name' 			=> $dropshipper_order_res[0]['bill_last_name'],
								'bill_company' 				=> $dropshipper_order_res[0]['bill_company'],
								'bill_email' 				=> $dropshipper_order_res[0]['bill_email'],
								'bill_address1' 			=> $dropshipper_order_res[0]['bill_address1'],
								'bill_address2' 			=> $dropshipper_order_res[0]['bill_address2'],
								'bill_city' 				=> $dropshipper_order_res[0]['bill_city'],
								'bill_zip' 					=> $dropshipper_order_res[0]['bill_zip'],
								'bill_state' 				=> $dropshipper_order_res[0]['bill_state'],
								'bill_country' 				=> $dropshipper_order_res[0]['bill_country'],
								'bill_phone' 				=> $dropshipper_order_res[0]['bill_phone'],
								'customer_ip' 				=> $_SERVER['REMOTE_ADDR'],
								'customer_browser' 			=> $_SERVER['HTTP_USER_AGENT'],
								'is_only_gc'				=> $dropshipper_order_res[0]['is_only_gc'],
								'free_gift'					=> $dropshipper_order_res[0]['free_gift'],
								'gift_from'					=> $dropshipper_order_res[0]['gift_from'],
								'gift_to'					=> $dropshipper_order_res[0]['gift_to'],
								'gift_message_customer'		=> $dropshipper_order_res[0]['gift_message_customer'],
								'cust_current_credit_limit' => $dropshipper_order_res[0]['cust_current_credit_limit'],
								'apply_credit'          	=> $dropshipper_order_res[0]['apply_credit'],
								'remaining_credit'      	=> $dropshipper_order_res[0]['remaining_credit'],
								'use_credit_limit'      	=> $dropshipper_order_res[0]['use_credit_limit'],
								'is_dropship_order'     	=> $dropshipper_order_res[0]['is_dropship_order'],
								'shipping_signature'	 	=> $dropshipper_order_res[0]['shipping_signature'],
								'Is_GiftCertificatPurchase' => $dropshipper_order_res[0]['Is_GiftCertificatPurchase'],
								'order_come_from'			=> "Dropshipper",
								'transaction_info' 			=> $transaction_info,
								'payment_gateway_response' => $payment_gateway_response,
								'paypal_payer_id' 	   		=> $payerID,
								'paypal_transaction_id' 	=> $TRANSACTIONID,
								'paypal_transaction_status' => $PAYMENTSTATUS,
								'paypal_transaction_date' 	=> $TIMESTAMP,
								'fullshipping_info'			=> $fullShippingname
							);
							$NewOrder = Order::create($OrderInsert) ;
							$OrderID = $NewOrder->orders_id;

							if($OrderID > 0)
							{
								$updateOrder = array ('orders_no' => "OR".$OrderID );
								$udpRefer = Order::where('orders_id','=',$OrderID)->update($updateOrder);
								$dropshipperOrderDetails = DropshipperOrderDetail::where('orders_id','=',$dropshipper_order_res[0]['orders_id'])->get();
								$totalOrdersDetails = $dropshipperOrderDetails->count();

								if($totalOrdersDetails > 0)
								{
									for($j=0;$j<$totalOrdersDetails;$j++)
									{
										$OrderDetailInsert = array (
											'orders_id'					=> $OrderID,
											'orders_no'					=> "OR".$OrderID,
											'products_id'				=> $dropshipperOrderDetails[$j]["products_id"],
											'sku' 						=> $dropshipperOrderDetails[$j]['sku'],
											'product_name'				=> $dropshipperOrderDetails[$j]['product_name'],
											'quantity' 					=> $dropshipperOrderDetails[$j]['quantity'],
											'price' 					=> $dropshipperOrderDetails[$j]['price'],
											'total' 					=> $dropshipperOrderDetails[$j]['total'],
											'status' 					=> '1',
											'item_price' 				=> $dropshipperOrderDetails[$j]['item_price'],
											'excluded_flag'  			=> $dropshipperOrderDetails[$j]['excluded_flag'],
											'is_gift_wrap'				=> $dropshipperOrderDetails[$j]['is_gift_wrap'],
											'is_free_gift_products' 	=> $dropshipperOrderDetails[$j]['is_free_gift_products'],
											'VendorSKU'					=> $dropshipperOrderDetails[$j]['VendorSKU'],
											'IsCosmo'					=> $dropshipperOrderDetails[$j]['IsCosmo'],
											'IsNandansons'  			=> $dropshipperOrderDetails[$j]['IsNandansons'],
											'IsPerfumePW'				=> $dropshipperOrderDetails[$j]['IsPerfumePW'],
											'coupon_itemwise_discount' 	=> $dropshipperOrderDetails[$j]['coupon_itemwise_discount']
										);
										$OrderDetailID = OrderDetail::create($OrderDetailInsert) ;

										$ProductSt = Products::select('current_stock','cosmo_current_stock','cosmo_sku','nandansons_sku','nandansons_current_stock','perfumeworldwide_sku','perfumeworldwide_currentstock')
														->where('status','=','1')->where('sku','=',$dropshipperOrderDetails[$j]['sku'])->get();
										
										$TotalProductCnt = $ProductSt->count();
										$new_stock = 0;

										if($TotalProductCnt > 0)
										{
											if($ProductSt[0]["current_stock"]>$dropshipperOrderDetails[$j]['quantity'])
											{
												$new_stock = $ProductSt[0]["current_stock"]-$dropshipperOrderDetails[$j]['quantity'];
											}
											else if($dropshipperOrderDetails[$j]['quantity']>$ProductSt[0]["current_stock"])
											{
												$new_stock = $dropshipperOrderDetails[$j]['quantity']-$ProductSt[0]["current_stock"];
											}
											if($new_stock<=0)
											{
												$new_stock=0;
											}

											$UpdateStock   = array ('current_stock' => $new_stock);
											Products::where('sku','=',$dropshipperOrderDetails[$j]['sku'])->update($UpdateStock);
										}
									}
								}
								DropshipperOrderDetail::where('orders_id','=',$dropshipper_order_res[0]['orders_id'])->delete();
								DropshipperOrder::where('orders_id','=',$dropshipper_order_res[0]['orders_id'])->where('customer_id','=',Session::get('sess_icustomerid'))->delete();
							}
						}
					}
				}
				
				$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
			
				if(fopen($myFile, 'a+'))
				{
					$fh = fopen($myFile, 'a+');

					$stringData = date("m/d/Y H:i:s")." And ".$order_id." : Paypal DoPayment Function With Order History In Paypal Controller.\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}			
				
				return redirect('/order-history.html');
			}
		} 
		else {
			$data = [];
			
			## Gather the information to make the final call to finalize the PayPal payment.  		
			$tempBillingAdd  = Session::get('ShoppingCart.BillingAddress');
			$tempShippingAdd = Session::get('ShoppingCart.ShippingAddress');
			$OrderSubTotal 	 =  Session::get('ShoppingCart.SubTotal');
			$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
			$GiftValue = 0;
			if($GiftCouponInfo && count($GiftCouponInfo) > 0)
			{
				$GiftValue = $GiftCouponInfo['Value'];
			}
			## For the All Type Of Discounts Start Here
			$NetDiscount 	 = $this->GetAllDiscounts();
			
			//$TotalDiscount 	 = NumberFormat($NetDiscount['TotalDiscount'] + $GiftValue);
			$TotalDiscount 	 = NumberFormat($NetDiscount['TotalDiscount']);
			## For the all types of discounts end here
			$OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);

			$total_charges = $this->GetAllCharges();
			
			$data['total'] 		= NumberFormat($this->GetNetTotal());
			
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			$data['ShipToname'] = $tempShippingAdd['first_name']." ".$tempShippingAdd['last_name'];
			$data['company'] 	= $tempShippingAdd["company"];
			$data['address1'] 	= $tempShippingAdd["address1"];
			$data['address2'] 	= $tempShippingAdd["address2"];
			$data['city'] 		= $tempShippingAdd["city"];
			$data['country'] 	= $tempShippingAdd["country"];
			$data['state'] 		= $tempShippingAdd["state"];
			$data['zip'] 		= $tempShippingAdd["zip"];
			$data['phone'] 		= $tempShippingAdd["phone"];
			
			
			$ShopCart = Session::get('ShoppingCart.Cart');
			$data['items'] = [];
			foreach($ShopCart as $key => $CartItem)
			{
				if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
				{	
					$ItemPrice = $CartItem['TotPrice'];
				}else{ 
					$ItemPrice = $CartItem['ItemPrice'];
				}
				
				$data['items'][] = [
					'name' => $CartItem['ProductName'],
					'price' => $ItemPrice,
					'qty' => $CartItem['Qty'] 
				];
			}
			if($total_charges['TotalCharges'] > 0) 
			{
				foreach($total_charges['Charges'] as $key => $charge)
				{
						$data['items'][] = [
							'name' => $charge['label'],
							'price' => NumberFormat($charge['charge']),
							'qty' => 1
						];
				}
			}

			if($TotalDiscount > 0)
			{
				$TotalDiscount = NumberFormat($TotalDiscount);
				$data['items'][] = 
					[
						'name' 	=> 'All Discount',
						'price'	=> -$TotalDiscount,
						'desc' 	=> urlencode("All Discounts"),
						'qty' 	=> 1
					];
			}
			$payerId = Session::get("PAYPAL_PAYER_ID");
			$token = Session::get("PayPalToken");
			$payment_response = $this->provider->doExpressCheckoutPayment($data, $token, $payerId);

			if (in_array(strtoupper($payment_response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
				
				$payerID 	   		= $payerId;
				
				$ACK 				= $payment_response["ACK"];
				$CORRELATIONID 		= $payment_response["CORRELATIONID"];
				$TIMESTAMP 			= $payment_response["TIMESTAMP"];
				$ORDERTIME 			= $payment_response["PAYMENTINFO_0_ORDERTIME"];
				$PAYMENTSTATUS 		= $payment_response["PAYMENTINFO_0_PAYMENTSTATUS"];
				$PENDINGREASON 		= $payment_response["PAYMENTINFO_0_PENDINGREASON"];
				$REASONCODE 		= $payment_response["PAYMENTINFO_0_REASONCODE"];
				$TRANSACTIONID		= $payment_response["PAYMENTINFO_0_TRANSACTIONID"]; 
				$TRANSACTIONTYPE 	= $payment_response["PAYMENTINFO_0_TRANSACTIONTYPE"]; 
				$PAYMENTTYPE		= $payment_response["PAYMENTINFO_0_PAYMENTTYPE"];  
				$ORDERAMOUNT		= $payment_response["PAYMENTINFO_0_AMT"];  
				$CURRENCYCODE		= $payment_response["PAYMENTINFO_0_CURRENCYCODE"];  
				
				$TRANSACTIONCHARGE	= 0;
				if(isset($payment_response["PAYMENTINFO_0_FEEAMT"])) {
					$TRANSACTIONCHARGE	= $payment_response["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
				}
				if(isset($payment_response["SETTLEAMT"])) {
					$settleAmt			= $payment_response["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
				} else {
					$settleAmt			= 0;
				}
				$taxAmt				= $payment_response["PAYMENTINFO_0_TAXAMT"];  //' Tax charged on the transaction.

				if(isset($payment_response["EXCHANGERATE"])) {
					$exchangeRate		= $payment_response["EXCHANGERATE"];
				} else {
					$exchangeRate		= 0;
				}
				
				$transaction_info = "This transaction has been approved.";

				$payment_gateway_response ="ACK=".$ACK." -- "."PAYER ID=".$payerID." -- TIMESTAMP=".$TIMESTAMP." -- CORRELATIONID=".$CORRELATIONID." -- "."TRANSACTION ID=".$TRANSACTIONID." -- "."TRANSACTION TYPE=".$TRANSACTIONTYPE." -- "."PAYMENT TYPE=".$PAYMENTTYPE." -- ORDERTIME=".$ORDERTIME." -- PAYMENTSTATUS=".$PAYMENTSTATUS." -- PENDINGREASON=".$PENDINGREASON." -- REASONCODE=".$REASONCODE." -- ORDERAMOUNT=".$ORDERAMOUNT;
				
				$updAray = array (
									'status'					=> 'Pending',
									'pay_status' 	   			=> 'Paid',
									'transaction_info' 			=> $transaction_info,
									'payment_gateway_response' 	=> $payment_gateway_response
								  );

				//$order_id = Session::get('ShoppingCart.OrderID');
				$updOrder = Order::Where("orders_id","=",$order_id)
									->update($updAray);	
									
									
				$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
			
				if(fopen($myFile, 'a+'))
				{
					$fh = fopen($myFile, 'a+');

					$stringData = date("m/d/Y H:i:s")." And ".$order_id." And ".serialize(Session::get('ShoppingCart')). ": Paypal DoPayment Function In Paypal Controller.\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}								
				return redirect('order-receipt');
			} 
			else  
			{
				$payerID = $payerId;
				
				$count=0;
				$errormsg = '';

				while (isset($payment_response["L_ERRORCODE".$count])) 
				{		
					$errormsg.="Error Code:".$payment_response["L_ERRORCODE".$count]." ### ";
					$errormsg.="Short Error Msg:".$payment_response["L_SHORTMESSAGE".$count]. " ### ";
					$errormsg.="Long Error Msg:".$payment_response["L_LONGMESSAGE".$count]." ### ";
					$count=$count+1;
				}

				$transaction_info = "This transaction has been Declined.";

				$payment_gateway_response ="ACK=".urldecode($payment_response["ACK"])."--"."CORRELATIONID=".urldecode($payment_response["CORRELATIONID"])." -- "."TIMESTAMP =".urldecode($payment_response["TIMESTAMP"])." -- ".$errormsg;

				$updAray = array (
									'status' 	   				=> 'Declined',
									'transaction_info' 			=> $transaction_info,
									'payment_gateway_response' 	=> $payment_gateway_response
								  );
											
				//$order_id = Session::get('ShoppingCart.OrderID');
				$updOrder = Order::Where("orders_id","=",$order_id)
									->update($updAray);
				$ErrorLongMsg = $payment_response["L_LONGMESSAGE0"];
				Session::flash('CartError',$ErrorLongMsg);
				
				$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);	
				
				/*$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
				
				$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);*/
				
				$myFile = '/home/maxaroma/public_html/Logs/Walmart/PaypalLog.txt';
			
				if(fopen($myFile, 'a+'))
				{
					$fh = fopen($myFile, 'a+');

					$stringData = date("m/d/Y H:i:s")." And ".$order_id." : Paypal DoPayment Function With Shoppingcart In Paypal Controller.\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}			
											
				return redirect('shoppingcart');
			}	
		}	
	}
	
	public function PhoneOrder(Request $request)
	{	
		if(!Session::has('phoneorder_detail.order_id'))
		{
			return redirect(config('global.SITE_URL'));
		}
		$OrderID = Session::get('phoneorder_detail.order_id');
		/*
		$db_res = PaymentMethod::select('pm_group_name', 'pm_gateway_name','pm_details')
							->where('pm_group_name','=', 'PAYMENT_PAYPALEC')
							->where('pm_status', '=', 'Active')
							->get();
		if($db_res->count() > 0)
		{
			$arrPEVar		= unserialize($db_res[0]->pm_details);
			$Mode = strtolower($arrPEVar['paypalec_Transaction_Mode']);
			
			$username = $this->decrypt($arrPEVar['paypalec_Username']);
			$password = $this->decrypt($arrPEVar['paypalec_Password']);
			$secret = $this->decrypt($arrPEVar['paypalec_Signature']);
			
			// if($_SERVER['HTTP_X_FORWARDED_FOR'] == "157.32.11.202"){
			// // if(Session::has('sess_useremail') && Session::get('sess_useremail') == 'gequaldev@gmail.com'){
				// $Mode = "sandbox";
				// $username = 'hitumca33-facilitator_api1.gmail.com';
				// $password = '1366188040';
				// $secret = 'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF';
			// }
			
			$PaypalConfig = [
				'mode'    => $Mode, 
				$Mode => [
					'username'    => $username,
					'password'    => $password,
					'secret'      => $secret,
					'certificate' => '',
					'app_id'      => '',
				],
				'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
				'currency'       => 'USD',
				'billing_type'   => 'MerchantInitiatedBilling',
				'notify_url'     => '', // Change this accordingly for your application.
				'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
				'validate_ssl'   => true, // Validate SSL when creating api client.
			];
			// dd($PaypalConfig);exit;
		}	
		*/
		$OrderRs = DB::table('pu_orders as o')
								->join('pu_order_detail as ord','ord.orders_id','=','o.orders_id')
								->select('o.orders_id', 'o.orders_no','o.customer_id', 'o.order_total', 'o.bill_email','o.bill_first_name','o.bill_last_name','o.bill_company','o.bill_email','o.bill_address1','o.bill_address2','o.bill_city','o.bill_zip','o.bill_state','o.bill_country','o.bill_phone','o.ship_first_name','o.ship_last_name','o.ship_company','o.ship_email','o.ship_address1','o.ship_address2','o.ship_city','o.ship_zip','o.ship_state','o.ship_country','o.ship_phone','o.sub_total','o.auto_discount','o.quantity_discount','o.reward_discount','o.coupon_amount','o.gc_amount','o.refer_amount','o.apply_credit','ord.price','ord.quantity','ord.sku','ord.product_name','ord.total','o.shipping_amt','o.tax','o.shipping_signature','o.route_shipping_insurance_charge','o.gift_charge')
								->where('o.orders_id', '=', $OrderID)
								->get();
		
		$order_total = $OrderRs[0]->order_total;
		$OrderSubTotal 	 =  $OrderRs[0]->sub_total;
		
		$OtherCharges = NumberFormat($OrderRs[0]->shipping_amt + $OrderRs[0]->tax+$OrderRs[0]->shipping_signature+$OrderRs[0]->route_shipping_insurance_charge+$OrderRs[0]->gift_charge);
		
		$TotalDiscount 	 = NumberFormat($OrderRs[0]->auto_discount + $OrderRs[0]->quantity_discount+$OrderRs[0]->reward_discount+$OrderRs[0]->coupon_amount+$OrderRs[0]->gc_amount+$OrderRs[0]->refer_amount+$OrderRs[0]->apply_credit);
		// $OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);
		$OrderSubTotal 	 = NumberFormat($order_total);
		/*
		$provider = new ExpressCheckout;
		//$provider->setApiCredentials(config('paypal'));
		$provider->setApiCredentials($PaypalConfig);
		*/
		
		//to test in sandbox start
		/* $CustEmail = $OrderRs[0]->bill_email;
		
		if($CustEmail == 'wgequaldev@gmail.com' || $CustEmail == 'gequaldev@gmail.com' || $CustEmail == 'qqualdev@gmail.com' || $CustEmail == 'gequaldev1234567@gmail.com')
		{ 
			$Mode = "sandbox";
				
			$PaypalConfig = [
				'mode'    => $Mode, 
				$Mode => [
					'username'    => 'hitumca33-facilitator_api1.gmail.com',
					'password'    => '1366188040', //'1366188040'
					'secret'      => 'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF', //'A0yZRiRRXJNuAwT87zJMDK4IiKmGAwdCn1izbbP5sUwNzlZFy8kFQsIF'
					'certificate' => '',
					'app_id'      => '',
				],
				'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
				'currency'       => 'USD',
				'billing_type'   => 'MerchantInitiatedBilling',
				'notify_url'     => '', // Change this accordingly for your application.
				'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
				'validate_ssl'   => true, // Validate SSL when creating api client.
			];
			
			$this->provider = new ExpressCheckout;
			$this->provider->setApiCredentials($PaypalConfig);
		} */
		//to test in sandbox end
		
		$data = [];
		$data['invoice_description'] = '';
		$data['invoice_id'] = '';
		
		foreach($OrderRs as $key => $CartItem)
		{
			$data['items'][] = [
		        'name' => $CartItem->product_name,
		        'price' => $CartItem->price,
		        'qty' => $CartItem->quantity
		    ];
		}
		
		$data['return_url'] = url('/paypal/success_phoneorder');
		$data['cancel_url'] = url('/paypal/cancel_phoneorder');
		$data['subtotal'] = $OrderSubTotal;
		$data['total'] = $OrderSubTotal;
		
		if($OtherCharges > 0)
		{
			$data['items'][] = 
				[
					'name' 	=> 'Other Charges (Tax,Shipping etc.)',
					'price'	=> $OtherCharges,
					'desc' 	=> urlencode("Other Charges (Tax,Shipping etc.)"),
					'qty' 	=> 1
				];
		}
		
		if($TotalDiscount > 0)
		{
			$data['items'][] = 
				[
					'name' 	=> 'All Discount',
					'price'	=> -$TotalDiscount,
					'desc' 	=> urlencode("All Discounts"),
					'qty' 	=> 1
				];
		}
		
		$options = [
				    'noshipping' => 0,
				    'LOGOIMG' => 'https://www.maxaroma.com/images/logo.jpg',
				    'BRANDNAME' => 'Maxaroma',
				];
				
		// echo "<pre>";print_r($data);exit;

		$response = $this->provider->addOptions($options)->setExpressCheckout($data);
		
		$UpdateOrderInformation = [
					'status'			=> 'Pending - PhoneOrder',
					'payment_type' 		=> 'PAYMENT_PAYPALEC',
					'payment_method' 	=> 'Paypal Express Checkout'
				]; 
		$Order = Order::where('orders_id','=',$OrderID)->update($UpdateOrderInformation);
		
		if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
			return redirect($response['paypal_link']);
		}else{
			$ErrorCode 			= $response["L_ERRORCODE0"];
			$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
			$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
			$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];	
			Session::flash('error',$ErrorLongMsg);								
			return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
		}	
	}

	public function Success_Phoneorder(Request $request)
	{
		$OrderID = Session::get('phoneorder_detail.order_id');
		$token = "";

		if (!empty($request->token))	
		{	
			Session::forget('phoneorder_detail.Paypal.PayPalToken');			
			Session::put('phoneorder_detail.Paypal.PayPalToken',$request->token);
			Session::save();
		}
		## token check end here 

		## if token not set then redired on shoppin cart
		if(!Session::has("phoneorder_detail.Paypal.PayPalToken") || empty(Session::get("phoneorder_detail.Paypal.PayPalToken")))
		{
			$ErrorLongMsg = "Error in Processing Request, Please try again.";
			Session::flash('error',$ErrorLongMsg);
			return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
		}
	

		$response = $this->provider->getExpressCheckoutDetails($request->token);
		// dd($response);

		if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
			
			Session::put('phoneorder_detail.Paypal.PAYPAL_PAYER_ID',$response['PAYERID']);
			Session::save();

			//payment confirm DoPayment Start
			$data = [];
			
			## Gather the information to make the final call to finalize the PayPal payment. 
			$OrderRs = DB::table('pu_orders as o')
									->join('pu_order_detail as ord','ord.orders_id','=','o.orders_id')
									->select('o.orders_id', 'o.orders_no','o.customer_id', 'o.order_total', 'o.bill_email','o.bill_first_name','o.bill_last_name','o.bill_company','o.bill_email','o.bill_address1','o.bill_address2','o.bill_city','o.bill_zip','o.bill_state','o.bill_country','o.bill_phone','o.ship_first_name','o.ship_last_name','o.ship_company','o.ship_email','o.ship_address1','o.ship_address2','o.ship_city','o.ship_zip','o.ship_state','o.ship_country','o.ship_phone','o.sub_total','o.auto_discount','o.quantity_discount','o.reward_discount','o.coupon_amount','o.gc_amount','o.refer_amount','o.apply_credit','ord.price','ord.quantity','ord.sku','ord.product_name','ord.total','o.shipping_amt','o.tax','o.shipping_signature','o.route_shipping_insurance_charge','o.gift_charge')
									->where('o.orders_id', '=', $OrderID)
									->get();
			
			$order_total = $OrderRs[0]->order_total;
			$OrderSubTotal 	 =  $OrderRs[0]->sub_total;
			
			$OtherCharges = NumberFormat($OrderRs[0]->shipping_amt + $OrderRs[0]->tax+$OrderRs[0]->shipping_signature+$OrderRs[0]->route_shipping_insurance_charge+$OrderRs[0]->gift_charge);
			
			$TotalDiscount 	 = NumberFormat($OrderRs[0]->auto_discount + $OrderRs[0]->quantity_discount+$OrderRs[0]->reward_discount+$OrderRs[0]->coupon_amount+$OrderRs[0]->gc_amount+$OrderRs[0]->refer_amount+$OrderRs[0]->apply_credit);
			// $OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);
			$OrderSubTotal 	 = NumberFormat($order_total);
			
			// $tempBillingAdd  = Session::get('ShoppingCart.BillingAddress');
			// $tempShippingAdd = Session::get('ShoppingCart.ShippingAddress');
			
			
			$data['total'] 		= NumberFormat($order_total);		
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			$data['ShipToname'] = $OrderRs[0]->ship_first_name." ".$OrderRs[0]->ship_last_name;
			$data['company'] 	= $OrderRs[0]->ship_company;
			$data['address1'] 	= $OrderRs[0]->ship_address1;
			$data['address2'] 	= $OrderRs[0]->ship_address2;
			$data['city'] 		= $OrderRs[0]->ship_city;
			$data['country'] 	= $OrderRs[0]->ship_country;
			$data['state'] 		= $OrderRs[0]->ship_state;
			$data['zip'] 		= $OrderRs[0]->ship_zip;
			$data['phone'] 		= $OrderRs[0]->ship_phone;
			
			$data['items'] = [];
			foreach($OrderRs as $key => $CartItem)
			{
				$data['items'][] = [
					'name' => $CartItem->product_name,
					'price' => $CartItem->price,
					'qty' => $CartItem->quantity
				];
			}
			
			if($OtherCharges > 0)
			{
				$data['items'][] = 
					[
						'name' 	=> 'Other Charges (Tax,Shipping etc.)',
						'price'	=> $OtherCharges,
						'desc' 	=> urlencode("Other Charges (Tax,Shipping etc.)"),
						'qty' 	=> 1
					];
			}

			if($TotalDiscount > 0)
			{
				$data['items'][] = 
					[
						'name' 	=> 'All Discount',
						'price'	=> -$TotalDiscount,
						'desc' 	=> urlencode("All Discounts"),
						'qty' 	=> 1
					];
			}
			$payerId = Session::get("phoneorder_detail.Paypal.PAYPAL_PAYER_ID");
			$token = Session::get("phoneorder_detail.Paypal.PayPalToken");
			$payment_response = $this->provider->doExpressCheckoutPayment($data, $token, $payerId);

			if (in_array(strtoupper($payment_response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
				
				$payerID 	   		= $payerId;
				
				$ACK 				= $payment_response["ACK"];
				$CORRELATIONID 		= $payment_response["CORRELATIONID"];
				$TIMESTAMP 			= $payment_response["TIMESTAMP"];
				$ORDERTIME 			= $payment_response["PAYMENTINFO_0_ORDERTIME"];
				$PAYMENTSTATUS 		= $payment_response["PAYMENTINFO_0_PAYMENTSTATUS"];
				$PENDINGREASON 		= $payment_response["PAYMENTINFO_0_PENDINGREASON"];
				$REASONCODE 		= $payment_response["PAYMENTINFO_0_REASONCODE"];
				$TRANSACTIONID		= $payment_response["PAYMENTINFO_0_TRANSACTIONID"]; 
				$TRANSACTIONTYPE 	= $payment_response["PAYMENTINFO_0_TRANSACTIONTYPE"]; 
				$PAYMENTTYPE		= $payment_response["PAYMENTINFO_0_PAYMENTTYPE"];  
				$ORDERAMOUNT		= $payment_response["PAYMENTINFO_0_AMT"];  
				$CURRENCYCODE		= $payment_response["PAYMENTINFO_0_CURRENCYCODE"];  
				
				$TRANSACTIONCHARGE	= 0;
				if(isset($payment_response["PAYMENTINFO_0_FEEAMT"])) {
					$TRANSACTIONCHARGE	= $payment_response["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
				}
				if(isset($payment_response["SETTLEAMT"])) {
					$settleAmt			= $payment_response["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
				} else {
					$settleAmt			= 0;
				}
				$taxAmt				= $payment_response["PAYMENTINFO_0_TAXAMT"];  //' Tax charged on the transaction.

				if(isset($payment_response["EXCHANGERATE"])) {
					$exchangeRate		= $payment_response["EXCHANGERATE"];
				} else {
					$exchangeRate		= 0;
				}
				
				$transaction_info = "This transaction has been approved.";

				$payment_gateway_response ="ACK=".$ACK." -- "."PAYER ID=".$payerID." -- TIMESTAMP=".$TIMESTAMP." -- CORRELATIONID=".$CORRELATIONID." -- "."TRANSACTION ID=".$TRANSACTIONID." -- "."TRANSACTION TYPE=".$TRANSACTIONTYPE." -- "."PAYMENT TYPE=".$PAYMENTTYPE." -- ORDERTIME=".$ORDERTIME." -- PAYMENTSTATUS=".$PAYMENTSTATUS." -- PENDINGREASON=".$PENDINGREASON." -- REASONCODE=".$REASONCODE;
				
				$updAray = array (
						'status' 	   				=> 'Pending',
						'pay_status' 	   			=> 'Paid',
						'transaction_info' 			=> $transaction_info,
						'payment_gateway_response' 	=> $payment_gateway_response,
						'paypal_payer_id' 	   		=> $payerID,
						'paypal_transaction_id' 	=> $TRANSACTIONID,
						'paypal_transaction_status' => $PAYMENTSTATUS,
						'paypal_transaction_date' 	=> $TIMESTAMP,
						'phoneorder_paymentdate' => date("Y-m-d H:i:s")
				);

				$updOrder = Order::Where("orders_id","=",$OrderID)->update($updAray);				
				
				############################# Complete Other Related processes Start #########################################
				$response_arr = $this->PhoneorderPaymentSuccess('Paypal');
				if($response_arr['success'] == "1"){
					Session::flash('success',$response_arr['err_msg']);
				}else{
					Session::flash('error',$response_arr['err_msg']);
				}	
				
				############################# Complete Other Related processes End #########################################
				return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
			} 
			else  
			{
				$payerID = $payerId;
				
				$count=0;
				$errormsg = '';

				while (isset($payment_response["L_ERRORCODE".$count])) 
				{		
					$errormsg.="Error Code:".$payment_response["L_ERRORCODE".$count]." ### ";
					$errormsg.="Short Error Msg:".$payment_response["L_SHORTMESSAGE".$count]. " ### ";
					$errormsg.="Long Error Msg:".$payment_response["L_LONGMESSAGE".$count]." ### ";
					$count=$count+1;
				}

				$transaction_info = "This transaction has been Declined.";

				$payment_gateway_response ="ACK=".urldecode($payment_response["ACK"])."--"."CORRELATIONID=".urldecode($payment_response["CORRELATIONID"])." -- "."TIMESTAMP =".urldecode($payment_response["TIMESTAMP"])." -- ".$errormsg;

				$updAray = array (
									'status' 	   				=> 'Declined',
									'transaction_info' 			=> $transaction_info,
									'payment_gateway_response' 	=> $payment_gateway_response
								  );
											
				$updOrder = Order::Where("orders_id","=",$OrderID)->update($updAray);
				$ErrorLongMsg = $payment_response["L_LONGMESSAGE0"];
				Session::flash('error',$ErrorLongMsg);
				
				$customerEmail = '';
				if(isset($OrderRs[0]->bill_email) && $OrderRs[0]->bill_email!='')
				{
					$customerEmail	= $OrderRs[0]->bill_email;
				}
				
				$orderNo		= $OrderRs[0]->orders_no;
				$customerName 	= '';
				if((isset($OrderRs[0]->ship_first_name) && $OrderRs[0]->ship_first_name!=''))
				{
					$customerName	= $OrderRs[0]->ship_first_name." ";
				}
				
				if(isset($OrderRs[0]->ship_last_name) && $OrderRs[0]->ship_last_name)
				{
					$customerName = $customerName.$OrderRs[0]->ship_last_name;
				}
				
				$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
				
				/*$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
				
				$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
				OmanisendRequest('63c113f03d897d0020e8b31e',$Data);*/
								
				return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
				
			}	
			//payment confirm DoPayment end
				
		}
		else	
		{
			$ErrorCode 			= $response["L_ERRORCODE0"];
			$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
			$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
			$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];
			
			Session::flash('error',$ErrorLongMsg);
			return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
		}		 
	}

	public function Cancel_Phoneorder(Request $request)
	{
		$OrderID = Session::get('phoneorder_detail.order_id');
		
		## 'This method is not in use.'
		// dd($request->all());
		Session::flash('error','Error in Processing Request, Please try again.');									
		return redirect(config('global.SITE_URL')."payment/".base64_encode($OrderID));
	}

}
