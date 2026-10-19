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
			
			$this->provider = new ExpressCheckout;
			//$this->provider->setApiCredentials(config('paypal'));
			$this->provider->setApiCredentials($PaypalConfig);
		}
		
	}
	
	public function SetPaypal(Request $request)
	{	
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
		
		foreach($ShopCart as $key => $CartItem)
		{
			$data['items'][] = [
		        'name' => $CartItem['ProductName'],
		        'price' => $CartItem['Price'],
		        'qty' => $CartItem['Qty']
		    ];
		}

		$data['return_url'] = url('/paypal/success');
		$data['cancel_url'] = url('/shoppingcart');
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

			$Billing['bill_city']	  		= $response['SHIPTOCITY'];
			$Billing['bill_country']		= $response['SHIPTOCOUNTRYCODE'];
			if($Billing['bill_country'] != 'US') {
				$Billing['bill_other_state'] 	= $response['SHIPTOSTATE'];
			} else {
				$Billing['bill_state'] 	  		= $response['SHIPTOSTATE'];
			}
			$Billing['bill_zip']			= $response['SHIPTOZIP'];
			$Billing['bill_phone']	  		= '';
			$Billing['bill_email'] 	  		= $response['EMAIL'];
			$Billing['bill_cemail'] 	  	= $response['EMAIL'];
			$Billing['sameasbill'] 	  		= 'Yes';
			
			$this->SetBillingAddress($Billing);			
			$this->SetShippingAddress($Billing);			
			return redirect('billing/paypal');
			
		}
		else	
		{
			$ErrorCode 			= $response["L_ERRORCODE0"];
			$ErrorShortMsg		= $response["L_SHORTMESSAGE0"];
			$ErrorLongMsg 		= $response["L_LONGMESSAGE0"];
			$ErrorSeverityCode  = $response["L_SEVERITYCODE0"];
			
			Session::flash('CartError',$ErrorLongMsg);
			return redirect('shoppingcart');
		}		


	}

	public function Cancel(Request $request)
	{
		## 'This method is not in use.'
		dd($request->all());
	}

	public function DoPayment(Request $request)
	{
		if(!Session::has("PayPalToken") || empty(Session::get("PayPalToken")))
		{
			$ErrorLongMsg = "Error in Processing Request, Please try again.";
			Session::flash('CartError',$ErrorLongMsg);
			return redirect('shoppingcart');
		}
		
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
								'pay_status' 	   			=> 'Paid',
								'transaction_info' 			=> $transaction_info,
								'payment_gateway_response' 	=> $payment_gateway_response
							  );

			$order_id = Session::get('ShoppingCart.OrderID');
			$updOrder = Order::Where("orders_id","=",$order_id)
								->update($updAray);				
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
							  			
			$order_id = Session::get('ShoppingCart.OrderID');
			$updOrder = Order::Where("orders_id","=",$order_id)
								->update($updAray);
			$ErrorLongMsg = $payment_response["L_LONGMESSAGE0"];
			Session::flash('CartError',$ErrorLongMsg);								
			return redirect('shoppingcart');
			
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
								->select('o.orders_id', 'o.orders_no','o.customer_id', 'o.order_total', 'o.bill_email','o.bill_first_name','o.bill_last_name','o.bill_company','o.bill_email','o.bill_address1','o.bill_address2','o.bill_city','o.bill_zip','o.bill_state','o.bill_country','o.bill_phone','o.ship_first_name','o.ship_last_name','o.ship_company','o.ship_email','o.ship_address1','o.ship_address2','o.ship_city','o.ship_zip','o.ship_state','o.ship_country','o.ship_phone','o.sub_total','o.auto_discount','o.quantity_discount','o.reward_discount','o.coupon_amount','o.gc_amount','o.refer_amount','o.apply_credit','ord.price','ord.quantity','ord.sku','ord.product_name','ord.total','o.shipping_amt','o.tax','o.shipping_signature','o.gift_charge')
								->where('o.orders_id', '=', $OrderID)
								->get();
		
		$order_total = $OrderRs[0]->order_total;
		$OrderSubTotal 	 =  $OrderRs[0]->sub_total;
		
		$OtherCharges = NumberFormat($OrderRs[0]->shipping_amt + $OrderRs[0]->tax+$OrderRs[0]->shipping_signature+$OrderRs[0]->gift_charge);
		
		$TotalDiscount 	 = NumberFormat($OrderRs[0]->auto_discount + $OrderRs[0]->quantity_discount+$OrderRs[0]->reward_discount+$OrderRs[0]->coupon_amount+$OrderRs[0]->gc_amount+$OrderRs[0]->refer_amount+$OrderRs[0]->apply_credit);
		// $OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);
		$OrderSubTotal 	 = NumberFormat($order_total);
		/*
		$provider = new ExpressCheckout;
		//$provider->setApiCredentials(config('paypal'));
		$provider->setApiCredentials($PaypalConfig);
		*/
		
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
									->select('o.orders_id', 'o.orders_no','o.customer_id', 'o.order_total', 'o.bill_email','o.bill_first_name','o.bill_last_name','o.bill_company','o.bill_email','o.bill_address1','o.bill_address2','o.bill_city','o.bill_zip','o.bill_state','o.bill_country','o.bill_phone','o.ship_first_name','o.ship_last_name','o.ship_company','o.ship_email','o.ship_address1','o.ship_address2','o.ship_city','o.ship_zip','o.ship_state','o.ship_country','o.ship_phone','o.sub_total','o.auto_discount','o.quantity_discount','o.reward_discount','o.coupon_amount','o.gc_amount','o.refer_amount','o.apply_credit','ord.price','ord.quantity','ord.sku','ord.product_name','ord.total','o.shipping_amt','o.tax','o.shipping_signature','o.gift_charge')
									->where('o.orders_id', '=', $OrderID)
									->get();
			
			$order_total = $OrderRs[0]->order_total;
			$OrderSubTotal 	 =  $OrderRs[0]->sub_total;
			
			$OtherCharges = NumberFormat($OrderRs[0]->shipping_amt + $OrderRs[0]->tax+$OrderRs[0]->shipping_signature+$OrderRs[0]->gift_charge);
			
			$TotalDiscount 	 = NumberFormat($OrderRs[0]->auto_discount + $OrderRs[0]->quantity_discount+$OrderRs[0]->reward_discount+$OrderRs[0]->coupon_amount+$OrderRs[0]->gc_amount+$OrderRs[0]->refer_amount+$OrderRs[0]->apply_credit);
			// $OrderSubTotal 	 = NumberFormat($OrderSubTotal - $TotalDiscount);
			$OrderSubTotal 	 = NumberFormat($order_total);
			
			// $tempBillingAdd  = Session::get('ShoppingCart.BillingAddress');
			// $tempShippingAdd = Session::get('ShoppingCart.ShippingAddress');
			
			
			$data['total'] 		= NumberFormat($order_total);		
			$data['invoice_description'] = '';
			$data['invoice_id'] = '';
			
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
