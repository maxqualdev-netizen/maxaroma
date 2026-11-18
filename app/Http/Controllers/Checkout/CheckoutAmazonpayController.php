<?php
namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\EncryptTrait;
use App\Services\Payment\AmazonPayService;
use Client;
use App\Models\Order;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\AmazonFundLog;
use Session;
use URL;

class CheckoutAmazonpayController extends Controller
{
    protected $amazonPayService;
	use CartTrait;
	use EncryptTrait;

	public function __construct(AmazonPayService $amazonPayService)
    {
        $this->amazonPayService = $amazonPayService;

        // Preserve properties consumed by existing checkout/view logic.
        foreach ([
            'MERCHANT_ID', 'MARKETPLACE_ID', 'AWS_ACCESS_KEY', 'AWS_SECRET_KEY',
            'CLIENT_ID', 'TRANSACTION_MODE', 'CURRENCY_CODE', 'JS_SERVER_URL',
            'API_URL', 'API_PROFILE_URL', 'SANDBOX_VAL',
        ] as $property) {
            $this->{$property} = $amazonPayService->{$property};
        }
    }

	public function transliterate($string)
    {
        return $this->amazonPayService->transliterate((string) $string);
    }

	public function SetupAmazon(Request $request)
	{

		if(isset($request->access_token) && $request->access_token != '')
		{
			Session::put('AMAZON_ACCESS_TOKEN',$request->access_token);
			Session::put('AMAZON_ACCESS_TOKEN_EXPIRES_AT',time() + (int) $request->expires_in);
			if(isset($request->page_from))
			{
				Session::put('page_from',$request->page_from);
				if($request->page_from == "phoneorder_payment_receipt"){
					return redirect('/phoneorder-amazon');
				}else{
					return redirect('/secure-checkout/amazonpay');
				}
			} else {
				$reqtoken = isset($request->access_token) ? $request->access_token : false;

				if(!empty($reqtoken))
				{
					$Profile = $this->amazonPayService->getUserInfo($reqtoken);
					if($Profile && isset($Profile['email'])){
						if(isset($Profile['email']) && $Profile['email'] !=''){
							if(checkBlockedUser($Profile['email'],0,'AmazonGuest')==true)
							{
								Session::flash('PlaceOrderError',config('message.Register.Blocked'));
								return redirect('/shoppingcart');
							}
						}
					}
					$ProfileCheck = $this->amazonPayService->getUserInfo($reqtoken);
					if($ProfileCheck)
					{
						//return redirect('/billing-amazon-checkout');
						return redirect('/secure-checkout/amazonpay');
					} else {
						Session::forget('AMAZON_ACCESS_TOKEN');
						return redirect('/shoppingcart');
					}
				}
				else {
					Session::forget('AMAZON_ACCESS_TOKEN');
					return redirect('/shoppingcart');
				}
			}
		}
	}

	public function CheckUser($AccessToken)
	{
		$Profile = $this->amazonPayService->getUserInfo($AccessToken);
		if($Profile && isset($Profile['email']))
		{

			$username 		= (isset($Profile['name']))?$this->transliterate($Profile['name']):'';
			$email 			= $Profile['email'];
			$vfirst_name	= (isset($Profile['name']))?$this->transliterate($Profile['name']):'';
			$social_id 		= $Profile['user_id'];
			$postal_code    = '';
			if(!empty($Profile['postal_code']))
			{
			$postal_code	= $Profile['postal_code'];
			}

			Session::put('amazon_id', $social_id);

			//dd(Session::all());
			if(!Session::has('sess_icustomerid') || empty(Session::get('sess_icustomerid')))
			{
				$result = Customer::select('customer_id','status','first_name','email','eusertype','is_dropshipper')
							->where('email', '=' , $email)->where('registration_type','=','M')->get();

				$registration_type = "Member";
				if(!$result || $result->count() <= 0)
				{
					//check for guest
					$result = Customer::select('customer_id','status','first_name','email','eusertype','is_dropshipper')
							->where('email', '=' , $email)->where('registration_type','=','G')
							->where('is_deleted','=','No')->get();
					$registration_type = "Guest";
				}

				if($result && isset($result[0]["email"]) && $result[0]["email"]!="" && !empty($result[0]["customer_id"]) && $result[0]["customer_id"]>0)
				{
					//convert guest to member
					if(isset($registration_type) && $registration_type == "Guest"){
						if(isset($email) && $email != '')
						{
							//$aData['email'] 			= $email;
							$aData['first_name'] 		= $vfirst_name;
							$aData['is_amazon'] 		= "Yes";
							$aData['amazon_id'] 		= $social_id;
							$aData['zip'] 				= $postal_code;
							$aData['registration_type'] = 'M'; // member or guest customer
							$aData['upd_datetime'] 		= date('Y-m-d H:i:s');
							$aData['status'] 			= 1;
							$aData['customer_ip'] 		= $_SERVER['REMOTE_ADDR'];
							$aData['customer_browser'] 	= $_SERVER['HTTP_USER_AGENT'];
							$aData['is_dropshipper'] 	= "No";
							$aData['eusertype']		    = 'Retailer';
							$aData['merge_log'] = "Auto updated guest to member from amazon login";

							$iCustomerId = $result[0]["customer_id"];
							Customer::where('customer_id','=',$iCustomerId)->update($aData);
						}
					}
					//convert guest to member

					if($result[0]['status'] == "0"){	//mostly member
						$CustomerArr = array (
							'upd_datetime' 		=> date('Y-m-d H:i:s'),
							'merge_log' 		=> "Auto updated to Active from amazon page",
							'status' 			=> '1'
						);
						$iCustomerId = $result[0]["customer_id"];
						Customer::where('customer_id','=',$iCustomerId)->update($CustomerArr);
					}

					Session::put('sess_useremail',$result[0]["email"]);
					Session::put('sess_icustomerid',$result[0]["customer_id"]);
					Session::put('eusertype',$result[0]["eusertype"]);
					Session::put('is_dropshipper',$result[0]["is_dropshipper"]);
					Session::put('etype',"M");
					Session::put('amazon_id',$social_id);

					//merge guest accounts will called only when login
					$user_email = Session::get('sess_useremail');
					$this->Merge_Guest_Register($user_email,Session::get('sess_icustomerid'));
					//merge guest accounts
				} else {
					if($email != '')
					{
						$aData['email'] 			= $email;
						$aData['first_name'] 		= $vfirst_name;
						$aData['is_amazon'] 		= "Yes";
						$aData['amazon_id'] 		= $social_id;
						$aData['zip'] 				= $postal_code;
						$aData['registration_type'] = 'M'; // member or guest customer
						$aData['reg_datetime'] 		= date('Y-m-d H:i:s');
						$aData['status'] 			= 1;
						$aData['customer_ip'] 		= $_SERVER['REMOTE_ADDR'];
						$aData['customer_browser'] 	= $_SERVER['HTTP_USER_AGENT'];
						$aData['is_dropshipper'] 	= "No";
						$aData['eusertype']		    = 'Retailer';
						$CustAdd = Customer::create($aData);
						$iCustomerId = $CustAdd->customer_id;
					}

					if (isset($iCustomerId) && $iCustomerId>0)
					{

						Session::put('sess_useremail' ,$email);
						Session::put('sess_icustomerid',$iCustomerId);
						Session::put('eusertype',$aData['eusertype']);
						Session::put('is_dropshipper',$aData['is_dropshipper']);
						Session::put('etype',"M");
						Session::put('amazon_id',$social_id);

						//merge guest accounts will called only when login
						$user_email = Session::get('sess_useremail');
						$this->Merge_Guest_Register($user_email,Session::get('sess_icustomerid'));
						//merge guest accounts
					}else{
						//echo "else";
					}
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function GetOrderInfo(Request $request)
	{
		if(isset($request->amazon_order_id) && $request->amazon_order_id != '')
		{
			Session::put('AMAZON_ORDER_REFERENCE_ID',$request->amazon_order_id);
			$requestParameters = [];
			$requestParameters['amazon_order_reference_id'] = $request->amazon_order_id;
			$requestParameters['access_token']  = Session::get('AMAZON_ACCESS_TOKEN');
			$requestParameters['mws_auth_token']         = 'MWS_AUTH_TOKEN';
			$response = $this->amazonPayService->getOrderReferenceDetails($requestParameters);
			$response = $response->toArray();
			if($response['ResponseStatus'] == '200')
			{
				$destination = $response['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
				$buyer = array();
				if(isset($response['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Buyer'])){
					$buyer = $response['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Buyer'];
				}
				if(strtoupper($destination['CountryCode']) == "GB") {
					$destination['CountryCode'] = "UK";
				}
				if(isset($destination['StateOrRegion']) && $destination['StateOrRegion']!='')
				{
					Session::put('AmazonShipState', $destination['StateOrRegion']);
				}
				else
				{
					Session::put('AmazonShipState','');
				}
				if(isset($destination['PostalCode']) && $destination['PostalCode']!='')
				{
					Session::put('AmazonShipZip', $destination['PostalCode']);
				}
				else
				{
					Session::put('AmazonShipZip', '');
				}
				if(isset($destination['CountryCode']) && $destination['CountryCode']!='')
				{
					Session::put('AmazonShipCountry', $destination['CountryCode']);
				}
				else
				{
					Session::put('AmazonShipCountry', '');
				}
				if(isset($destination['City']) && $destination['City']!='')
				{
					Session::put('AmazonShipCity', $destination['City']);
				}
				else
				{
					Session::put('AmazonShipCity', '');
				}
				$temp = array();
				$temp['first_name'] 	= (isset($destination['Name']))?$this->transliterate($destination['Name']):'';//$destination['Name'];
				$temp['last_name']  	= '';
				$temp['company']    	= '';
				$temp['address1'] 		= (isset($destination['AddressLine1']))?$this->transliterate($destination['AddressLine1']):'';//$destination['AddressLine1'];
				$temp['address2'] 		= (isset($destination['AddressLine2']))?$destination['AddressLine2']:'';
				$temp['city'] 			= (isset($destination['City']))?$destination['City']:'';//$destination['City'];
				$temp['country'] 		= (isset($destination['CountryCode']))?$destination['CountryCode']:'';//$destination['CountryCode'];
				$temp['state'] 			= (isset($destination['StateOrRegion']))?$destination['StateOrRegion']:'';
				$temp['zip'] = '';
				$temp['email'] 			= "";
				$temp['confirm_email'] 	= "";
				if(isset($destination['PostalCode']) && $destination['PostalCode']!='')
				{
					$temp['zip'] 			= $destination['PostalCode'];
				}
				$temp['phone']          = "";

				if(isset($destination['Phone']) && $destination['Phone']!='')
				{
					$temp['phone'] 			= $destination['Phone'];
				}
				else if(isset($buyer['Phone']) && $buyer['Phone']!='')
				{
					$temp['phone'] 			= $buyer['Phone'];
				}
				if(isset($buyer['Email']) && $buyer['Email'] !=''){
					$temp['email'] 			= $buyer['Email'];
					$temp['confirm_email'] 	= $buyer['Email'];
				}

				Session::put('ShoppingCart.BillingAddress',$temp);
				Session::put('ShoppingCart.ShippingAddress',$temp);

				return response()->json(['success' => true, 'address' => Session::get('ShoppingCart.ShippingAddress')]);
			} else {
				return response()->json(['success' => false]);
			}

		} else {
			return response()->json(['success' => false]);
		}
	}
}
