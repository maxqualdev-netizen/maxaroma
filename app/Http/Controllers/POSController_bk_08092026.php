<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\POSConnecterController;
use Hash;
use Session;
use App\Models\MetaInfo;
use App\Models\StoreUser;
use App\Models\StorePayment;
use App\Models\Editprofile;
use App\Models\StoreLoginLog;
use App\Models\StoreSalesPerson;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\Shoppingcart;
use App\Models\CustomerCreditLimitLogs;
use App\Models\PaymentMethod;
use App\Models\MailBanner;
use App\Models\Dealofweek;
use App\Models\Dealofweektitle;
use App\Models\Coupon;
use App\Models\GiftCertificate;
use App\Models\Customer;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\AfterpayTrait;
use App\Http\Controllers\ShoppingcartController;
use App\Http\Controllers\Traits\POSTrait;
use App\Http\Controllers\Traits\StripeTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

use DB;
use Mail;

use Carbon\Carbon;
// use File;
use Illuminate\Support\Facades\File;

use PDF;
use Cookie;

class POSController extends Controller
{
	use POSTrait;
	use StripeTrait;
	protected array $PageData = [];

	public function __construct()
	{
		// $PageType = 'NR';
		// $MetaInfo = MetaInfo::where('type','=',$PageType)->get();
		// if($MetaInfo->count() > 0 )
		// {
		// 	$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
		// 	$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		// }
		//$this->LoginFromCookiee();
	}
	public function Myaccount(Request $request)
	{

		//Session::forget("PaymentMessagePOS");
		// $this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Account Overview';
		// $GTMDATA = ['page' => 'myaccount', 'pagetype' => 'other'];
		// $this->PageData['CSSFILES'] = ['myaccount.css'];
		// //$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		// $GA4= "";
		// if(Session::has('MyAccountGA4') && Session::get('MyAccountGA4')!='')
		// {
		// $GA4= googleAnalyticsGA4("LoginPage");
		// Session::forget("MyAccountGA4");
		// }

		// $this->PageData['GA4'] = $GA4;
		// $GARegister1 = "";

		// if(Session::has('GARegister') && Session::get('GARegister')!='')
		// {
		// $GARegister1= googleAnalyticsGA4("RegisterPage");
		// Session::forget("GARegister");
		// }
		// $this->PageData['GARegister1'] = $GARegister1;

		// $account_image_path = config('global.SITE_URL').'images/';
		// $account_image = generalsetting('ACCOUNT_IMAGE',1);
		// $account_image = $account_image_path.$account_image.'?ver='.time();
		// $this->PageData['account_image'] = $account_image;
		$this->PageData['posPaymentMsg'] = '';

		if (isset($request->posmsg) && $request->posmsg == 1 ) {
			$this->PageData['posPaymentMsg'] = "Payment failed or declined.";
			Session::forget("BillingSkipEmail");
			Session::forget("BillingSkipVariable");
			Session::forget('ShoppingCart');
		}

		else if(isset($request->posmsg) && $request->posmsg == 2)
		{
			$this->PageData['posPaymentMsg'] = "You can not cancel this payment,this payment not processed";
			Session::forget("BillingSkipEmail");
			Session::forget("BillingSkipVariable");
			Session::forget('ShoppingCart');
			Session::forget([
				'sess_icustomerid',
				'etype',
				'eusertype',
				'sess_useremail'
			]);
		}
		else if(isset($request->posmsg) && $request->posmsg == 3)
		{
			$this->PageData['posPaymentMsg'] = "Payment has been canceled";
			Session::forget("BillingSkipEmail");
			Session::forget("BillingSkipVariable");
			Session::forget('ShoppingCart');
			Session::forget([
				'sess_icustomerid',
				'etype',
				'eusertype',
				'sess_useremail'
			]);
		}

		//echo "ddd".$request->posmsg; exit;
		$store_user = Auth::guard('store')->user();
		$this->PageData['store_user_id'] =  $store_user->store_user_id;

		//$this->PageData['JSFILES'] = ['bootstrap.min.js','jquery.mobile-1.0rc2.min.js','StarWebPrintBuilder.js','StarBarcodeEncoder.js','StarWebPrintExtManager.js','StarWebPrintDisplayBuilder.js','StarWebPrintTrader.js','pos_cashdrawer.js'];
		//$this->PageData['JSFILES'] = ['bootstrap.min.js','jquery.mobile-1.0rc2.min.js','StarWebPrintBuilder.js','StarBarcodeEncoder.js','StarWebPrintExtManager.js','StarWebPrintDisplayBuilder.js','StarWebPrintTrader.js','store_device_status.js'];

		$this->PageData['JSFILES'] = ['bootstrap.min.js'];

		/*$isshow_dailyopen_pending = 0;
		$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_user->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
		//dd($cashd_res);
		if (empty($cashd_res)) {
			$isshow_dailyopen_pending = 1;
		}*/

		$CardReaderData = $this->GetCardReaderStatus();
		$CardReaderStatus = $CardReaderData['status'];

		$this->PageData['cardreaderStatus'] = $CardReaderStatus;
		/*
		if($CardReaderStatus == 'ONLINE')
			$this->PageData['cardreaderStatus'] = "<div style='color:green'>".$CardReaderStatus."</div>";
		else
			$this->PageData['cardreaderStatus'] = "<div style='color:red'>".$CardReaderStatus."</div>";
		*/
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Dashboard';
		//$this->PageData['isshow_dailyopen_pending'] =  $isshow_dailyopen_pending;
		$this->PageData['CSSFILES'] = ['myaccount.css', 'register.css'];

		return view('pos.dashboard')->with($this->PageData);
	}

	/*public function checkIsDailyOpenPending(Request $request)
	{
		$isshow_dailyopen_pending = 0;

		$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_user->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
		//dd($cashd_res);

		if (empty($cashd_res)) {
			$isshow_dailyopen_pending = 1;
		}

		$this->PageData['isshow_dailyopen_pending'] =  $isshow_dailyopen_pending;
		return view('pos.leftmenu')->with($this->PageData);
	}*/

	public function CustomerLogin(Request $request)
	{
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Customer Auto Login';
		$this->PageData['CSSFILES'] = ['myaccount.css', 'register.css'];
		$this->PageData['email'] =  '';
		$this->PageData['password'] =  '';
		$this->PageData['action'] =  '';
		$this->PageData['msg'] =  'Sorry, Something went wrong. Please try again';

		Auth::guard('web')->logout();
		$request->session()->forget('sess_useremail');
		$request->session()->forget('sess_username');
		$request->session()->forget('sess_icustomerid');
		$request->session()->forget('eusertype');
		$request->session()->forget('is_dropshipper');
		$request->session()->forget('etype');
		$request->session()->forget('payment_amount');
		$request->session()->forget('sess_useraddress');
		$request->session()->forget('sess_custname');
		$request->session()->forget('LoyaltyTier');
		$this->ReformatCartPrice();

		if ($request->has('k')) {
			$k = $request->get('k');
			$customerId = base64_decode($k);

			$ChkCustomer = Customer::where('customer_id', '=', $customerId)->where('status', '=', '1')->where('registration_type', 'M')->get();
			if ($ChkCustomer) {
				if (isset($ChkCustomer[0]->email) && $ChkCustomer[0]->email != '' && isset($ChkCustomer[0]->password) && $ChkCustomer[0]->password != '') {
					$email    = $ChkCustomer[0]->email;
					$password = $ChkCustomer[0]->password;

					$this->PageData['email'] =  $email;
					$this->PageData['password'] =  $password;
					$this->PageData['action'] =  config('global.SITE_URL') . 'autologin';
					$this->PageData['msg'] = '';

					// $postData = [
					// 	'vemail'          => $email,
					// 	'vpassword'       => $password,
					// 	'automated_login' => 'yes',
					// 	'page' => 'storeuserloginqr'
					// ];

					// $url = config('global.SITE_URL') . 'autologin';

					// $ch = curl_init();

					// curl_setopt_array($ch, [
					// 	CURLOPT_URL            => $url,
					// 	CURLOPT_POST           => true,
					// 	CURLOPT_POSTFIELDS     => http_build_query($postData),
					// 	CURLOPT_RETURNTRANSFER => true,
					// 	CURLOPT_HEADER         => true,
					// 	CURLOPT_FOLLOWLOCATION => true,
					// ]);

					// $response = curl_exec($ch);
					// $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					// $body       = substr($response, $headerSize);
					// $error    = curl_error($ch);
					// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					// curl_close($ch);

					// $normaluser = Auth::user();
					// if (Auth::guard('store')->check()) {
					// 	$normaluser = Auth::guard('web')->user();
					// }
					// echo $body;

					// if(isset($normaluser->customer_id) && $normaluser->customer_id > 0){
					// 	echo 'logged in';
					// } else {
					// 	echo "plz check login";
					// }

					// exit;
					//return redirect('/store/store-dashboard.html');
					/*if($body === 'userloginsuccess'){
						return redirect('/store/store-dashboard.html');
					} else {
						return redirect('/store/create-order.html');
					}*/
				}
			}
		}
		return view('pos.customerautologin')->with($this->PageData);
	}

	public function editProfile(Request $request)
	{

		$store_user = Auth::guard('store')->user();

		$Userdata = DB::table('pu_store_user')
			->select(
				'store_id',
				'store_user_email',
				'first_name',
				'last_name',
				'phone',
				'store_user_id'
			)
			->where('store_user_id', '=', $store_user->store_user_id)
			->first();

		if ($request['action'] == 'update') {
			$validatedData = $request->validate([
				'store_user_email'    => 'required',
				'first_name'     => 'required',
				'last_name'      => 'required'
			]);

			$storeUser = StoreUser::where('store_user_id', $store_user->store_user_id)->first();
			if ($storeUser) {
				$storeUser->first_name = $request->first_name;
				$storeUser->last_name  = $request->last_name;

				$storeUser->store_user_email = $request->store_user_email;
				$storeUser->phone  = $request->store_user_phone;
				$storeUser->save();
			}

			Session::flash('success', config('message.POS.SuccessEditProfile'));
			return redirect()->back();
		}

		// Page view data
		$this->PageData['meta_title'] = config('Settings.SITE_TITLE') . ' :: Edit Store Member Profile';
		$this->PageData['JSFILES'] = ['moment.min.js', 'editprofile.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['Userdata'] = $Userdata;
		$store_user = Auth::guard('store')->user();
		$this->PageData['store_user_id'] =  $store_user->store_user_id;
		return view('pos.editprofile', ['Userdata' => $Userdata])->with($this->PageData);
	}

	public function POSComponents(Request $request)
	{
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: POS';
		$this->PageData['CSSFILES'] = ['myaccount.css', 'register.css'];
		return view('pos.pos-components')->with($this->PageData);
	}

	public function ChangePassword(Request $request)
	{
		if (isset($request['old_password'])) {
			$user = Auth::guard('store')->user();

			$validatedData = $request->validate([
				'old_password' => 'required',
				'new_password' => 'required|min:6|regex:/^\S*$/u',
				're_type_new_password' 	=> 'required|same:new_password',
			], [
				'old_password.required' => config('message.Validate.OldPassword'),
				'new_password.required' => config('message.Validate.RequiredNewPassword'),
				'new_password.min' => config('message.Validate.NewPassword'),
				're_type_new_password.required' => config('message.Validate.ConfirmPassword'),
				're_type_new_password.same' => config('message.Validate.DoesNotMatch'),
				'new_password.regex' => config('message.Validate.NewPassword'),
			]);
			/* At least one uppercase letter and one number */
			if (!preg_match('/[a-z]/', $request->new_password) || !preg_match('/[A-Z]/', $request->new_password) || !preg_match('/[0-9]/', $request->new_password)) {
				return redirect()->back()
					->withInput()
					->withErrors([
						'uppercase_number' => config('message.Validate.UpperCaseAndLetter')
					]);
			}
			$checkOldPassword = StoreUser::where('store_user_id', '=', $user->store_user_id)->where('store_user_password', '=', md5($request['old_password']))->get();

			if ($checkOldPassword->count() <= 0) {
				return redirect()->back()
					->withInput()
					->withErrors([
						'wrong_password' => config('message.ChangePassword.WrongOldPassword')
					]);
			} else {

				$UserData = array(
					'store_user_password' => md5($request['new_password']), //md5("qualdev123"), //
				);
				$StoreUser = StoreUser::find($user->store_user_id);
				$StoreUser->update($UserData);
				if ($StoreUser) {
					Session::flash('success', config('message.ChangePassword.Success'));
					return redirect()->back();
				}
			}
		}

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Member Change Password';
		$this->PageData['JSFILES'] = ['changepassword.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$store_user = Auth::guard('store')->user();
		$this->PageData['store_user_id'] =  $store_user->store_user_id;
		return view('pos.changepassword')->with($this->PageData);
	}

	public function ForgotPassword(Request $request)
	{
		if (isset($request->action) && $request->action == 'forgot_password') {
			$validatedData = $request->validate([
				'email' => 'required|email',
				//'g-recaptcha-response' => 'required'
			], [
				'email.required' => config('message.Forgot.ValidEmail'),
				'email.email' => config('message.Forgot.ValidEmail'),
				//'g-recaptcha-response.required' => config('message.Validate.Captcha')
			]);
			$ChkEmail = StoreUser::where('store_user_email', '=', $request['email'])->where('store_user_status', '=', '1')->get();
			// echo "<pre>";
			// print_r($ChkEmail);
			// echo "</pre>";
			// exit;
			if ($ChkEmail) {
				if (empty($ChkEmail[0]->store_user_password)) {
					return redirect()->back()
						->withInput()
						->withErrors([
							'NotExistEmail' => "Sorry, we could not find this email account",
						]);
				} else {
					$FreeShipping = "";
					if (config('Settings.FREESHIPPING_VALUE'))
						$FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders</span>';
					$Template = GetMailTemplate("FORGOT_PASSWORD");
					$EmailBody = $Template[0]->mail_body;
					$EmailBody = str_replace('{$Site_URL}', config('global.SITE_URL'), $EmailBody);
					$EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $EmailBody);
					$EmailBody = str_replace('{$freeshippinginfo}', $FreeShipping, $EmailBody);
					$EmailBody = str_replace('{$vemail}', $ChkEmail[0]->store_user_email, $EmailBody);
					$EmailBody = str_replace('{$password}', $ChkEmail[0]->password, $EmailBody);
					$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
					$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);

					$To = $ChkEmail[0]->store_user_email;
					$Subject = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $Template[0]->subject);
					$From = config('Settings.CONTACT_MAIL');

					if (config('global.OMNISEND_PROG') == false) {
						SendMail($Subject, $EmailBody, $To, $From);
					} else {
						/** OMANISEND **/
                        //OmanisendRequest('61e048930e8680001cd923aa',$ChkEmail[0]);
						/** OMANISEND **/
					}

					Session::flash('success', config('message.Forgot.Success'));
					return redirect()->back();
				}
			}
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Forgot Password';
		$this->PageData['JSFILES'] = ['forgotpassword.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		return view('pos.forgotpassword')->with($this->PageData);
	}

	public function StoreSecurity(Request $request)
	{
		if (isset($request->action) && $request->action == 'security_otp' && Session::has('sess_twofactoruserid') && Session::get('sess_twofactoruserid') != '') {
			$validatedData = $request->validate([
				'otp' => 'required',
				//'g-recaptcha-response' => 'required'
			], [
				'otp.required' => config('message.Forgot.ValidEmail'),
				//'g-recaptcha-response.required' => config('message.Validate.Captcha')
			]);
			$StoreUserSecurity = StoreUser::where('2fa_security_code', '=', $request['otp'])->where('store_user_status', '=', '1')->where('store_user_id', '=', Session::get('sess_twofactoruserid'))->first();

			if ($StoreUserSecurity) {
				// echo "<pre>";
				// print_r($StoreUserSecurity);
				// exit;
				Session::put('sess_storeuseremail', $StoreUserSecurity->store_user_email);
				Session::put('sess_storeusername', $StoreUserSecurity->first_name);
				Session::put('sess_storeuserid', $StoreUserSecurity->store_user_id);
				Session::put('sess_store_id', $StoreUserSecurity->store_id);

				$storeUserFname = '';
				if (isset($StoreUserSecurity->first_name) &&  $StoreUserSecurity->first_name != '') {
					$storeUserFname = $StoreUserSecurity->first_name;
				}
				$storeUserlname = '';
				if (isset($StoreUserSecurity->last_name) && $StoreUserSecurity->last_name != '') {
					$storeUserlname = $StoreUserSecurity->last_name;
				}
				Session::put('sess_storeusername', $storeUserFname . "|" . $storeUserlname);

				//Manage Admin Session Start

				$result = DB::table('pu_store_user as su')
					->join('pu_admin as a', 'su.store_user_email', '=', 'a.email')
					->join('pu_admin_roles as r', 'a.role_id', '=', 'r.role_id')
					->where('su.store_user_id', $StoreUserSecurity->store_user_id)
					->select(
						'su.store_user_id',
						'su.store_id',
						'su.first_name',
						'su.last_name',
						'a.admin_id',
						'a.email',
						'a.admin_type',
						'a.role_id',
						'r.name as role_name',
						'r.user_type'
					)->first();
				ini_set('session.gc_maxlifetime', 7200);

				session_start();
				$_SESSION['sess_admin_adminID'] 	= "";
				$_SESSION['sess_admin_email'] 		= "";
				$_SESSION['sess_admin_type'] 		= "";
				$_SESSION['sess_admin_roleId'] 		= "";
				$_SESSION['sess_admin_role'] 		= "";
				$_SESSION['sess_admin_user_type'] 	= "";
				$_SESSION['sess_store_userid'] = "";
				$_SESSION['sess_store_name'] = "";

				unset($_SESSION['sess_admin_adminID']);
				unset($_SESSION['sess_admin_email']);
				unset($_SESSION['sess_admin_type']);
				unset($_SESSION['sess_admin_roleId']);
				unset($_SESSION['sess_admin_role']);
				unset($_SESSION['sess_admin_user_type']);
				unset($_SESSION['sess_store_userid']);
				unset($_SESSION['sess_store_name']);

				$sess_admin_adminID = $result->admin_id ?? "";
				$sess_admin_email = $result->email ?? "";
				$sess_admin_type = $result->admin_type ?? "";
				$sess_admin_roleId = $result->role_id ?? "";
				$sess_admin_role = $result->role_name ?? "";
				$sess_admin_user_type = $result->user_type ?? "";

				$_SESSION['sess_store_userid'] 	= $StoreUserSecurity->store_user_id;
				$_SESSION['sess_store_id'] 	= $StoreUserSecurity->store_id;
				$_SESSION['sess_store_name'] 	= $result->first_name ?? "" . "|" . $result->last_name ?? "";

				$_SESSION['sess_admin_adminID'] 	= $sess_admin_adminID;
				$_SESSION['sess_admin_email'] 		= $sess_admin_email;
				$_SESSION['sess_admin_type'] 		= $sess_admin_type;
				$_SESSION['sess_admin_roleId'] 		= $sess_admin_roleId;
				$_SESSION['sess_admin_role'] 		= $sess_admin_role;
				$_SESSION['sess_admin_user_type'] 	= $sess_admin_user_type;
				$_SESSION['isStoreLogin'] = 'Y';

				$name = "pos_maxaroma";
				$value = "store_pos";
				$week = time() + (24 * 60 * 60);

				setcookie(
					"pos_maxaroma",
					$value,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);
				$pos_token = base64_encode($StoreUserSecurity->store_user_email);
				setcookie(
					"pos_maxaroma_token",
					$pos_token,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);

				//Manage Admin Session End

				return redirect('/store/store-dashboard.html');
			} else {
				return redirect()->back()
					->withInput()
					->withErrors([
						'NotExistEmail' => config('message.POS.InvalidSecurityCode'),
					]);
			}
		}
		if (!Session::has('sess_twofactoruserid')) {
			return redirect('store/login.html');
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Two Factor Security';
		$this->PageData['JSFILES'] = ['securitycode.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		return view('pos.storesecurity')->with($this->PageData);
	}

	public function LoginFromCookiee()
	{
		if (Cookie::has('pos_maxaroma_token') && !empty(Cookie::get('pos_maxaroma_token')))
		{
			$email = base64_decode(Cookie::get('pos_maxaroma_token'));
			$StoreUserSecurity = StoreUser::where('store_user_email', '=', $email)->where('store_user_status', '=', '1')->first();

			if ($StoreUserSecurity)
			{
				Auth::guard('store')->login($StoreUserSecurity, true);
				Session::regenerate();

				Session::put('sess_storeuseremail', $StoreUserSecurity->store_user_email);
				Session::put('sess_storeusername', $StoreUserSecurity->first_name);
				Session::put('sess_storeuserid', $StoreUserSecurity->store_user_id);
				Session::put('sess_store_id', $StoreUserSecurity->store_id);

				/*$isshow_dailyopen_pending = 2;
				$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $StoreUserSecurity->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
				if (empty($cashd_res)) {
					$isshow_dailyopen_pending = 1;
				}
				Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);*/

				$this->login_logout_log();
				$storeUserFname = '';
				if (isset($StoreUserSecurity->first_name) &&  $StoreUserSecurity->first_name != '') {
					$storeUserFname = $StoreUserSecurity->first_name;
				}
				$storeUserlname = '';
				if (isset($StoreUserSecurity->last_name) && $StoreUserSecurity->last_name != '') {
					$storeUserlname = $StoreUserSecurity->last_name;
				}
				Session::put('sess_storeusername', $storeUserFname . "|" . $storeUserlname);

				//Manage Admin Session Start

				$result = DB::table('pu_store_user as su')
					->join('pu_admin as a', 'su.store_user_email', '=', 'a.email')
					->join('pu_admin_roles as r', 'a.role_id', '=', 'r.role_id')
					->where('su.store_user_id', $StoreUserSecurity->store_user_id)
					->select(
						'su.store_user_id',
						'su.store_id',
						'su.first_name',
						'su.last_name',
						'a.admin_id',
						'a.email',
						'a.admin_type',
						'a.role_id',
						'r.name as role_name',
						'r.user_type'
					)->first();
				ini_set('session.gc_maxlifetime', 7200);

				session_start();
				$_SESSION['sess_admin_adminID'] 	= "";
				$_SESSION['sess_admin_email'] 		= "";
				$_SESSION['sess_admin_type'] 		= "";
				$_SESSION['sess_admin_roleId'] 		= "";
				$_SESSION['sess_admin_role'] 		= "";
				$_SESSION['sess_admin_user_type'] 	= "";
				$_SESSION['sess_store_userid'] = "";
				$_SESSION['sess_store_name'] = "";

				unset($_SESSION['sess_admin_adminID']);
				unset($_SESSION['sess_admin_email']);
				unset($_SESSION['sess_admin_type']);
				unset($_SESSION['sess_admin_roleId']);
				unset($_SESSION['sess_admin_role']);
				unset($_SESSION['sess_admin_user_type']);
				unset($_SESSION['sess_store_userid']);
				unset($_SESSION['sess_store_name']);

				$sess_admin_adminID = $result->admin_id ?? "";
				$sess_admin_email = $result->email ?? "";
				$sess_admin_type = $result->admin_type ?? "";
				$sess_admin_roleId = $result->role_id ?? "";
				$sess_admin_role = $result->role_name ?? "";
				$sess_admin_user_type = $result->user_type ?? "";

				$_SESSION['sess_store_userid'] 	= $StoreUserSecurity->store_user_id;
				$_SESSION['sess_store_id'] 	= $StoreUserSecurity->store_id;
				$_SESSION['sess_store_name'] 	= $result->first_name ?? "" . "|" . $result->last_name ?? "";

				$_SESSION['sess_admin_adminID'] 	= $sess_admin_adminID;
				$_SESSION['sess_admin_email'] 		= $sess_admin_email;
				$_SESSION['sess_admin_type'] 		= $sess_admin_type;
				$_SESSION['sess_admin_roleId'] 		= $sess_admin_roleId;
				$_SESSION['sess_admin_role'] 		= $sess_admin_role;
				$_SESSION['sess_admin_user_type'] 	= $sess_admin_user_type;
				$_SESSION['isStoreLogin'] = 'Y';

				$name = "pos_maxaroma";
				$value = "store_pos";
				$week = time() + (24 * 60 * 60);
				setcookie(
					"pos_maxaroma",
					$value,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);
				$pos_token = base64_encode($StoreUserSecurity->store_user_email);
				setcookie(
					"pos_maxaroma_token",
					$pos_token,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);

				$CheckStoreCardReader = DB::table('pu_store_card_reader')->where('store_id', $StoreUserSecurity->store_id)->where('status', '1')->first();
				if ($CheckStoreCardReader) {
					if (!empty($CheckStoreCardReader->reader_id) && !empty($CheckStoreCardReader->location)) {
						Session::put('ConnectedCardReaderId', $CheckStoreCardReader->reader_id);
						Session::put('ConnectedCardReaderName', $CheckStoreCardReader->reader_name);
						Session::put('ConnectedCardReaderLocationID', $CheckStoreCardReader->location);
					}
				}

				//Manage Admin Session End

				//return redirect('/store/store-dashboard.html');
			} else {
				return redirect()->back()
					->withInput()
					->withErrors([
						'NotExistEmail' => config('message.POS.InvalidSecurityCode'),
					]);
			}
		}
	}

	public function StoreDashboard()
	{
		// echo "Store Dashboard";
		// echo "<br>";
		// echo Session::get('sess_storeuserid');
		// echo "<br>";
		// echo Session::get('sess_storeuseremail');
		// echo "<br>";
		// echo Session::get('sess_storeusername');
		// echo "<br>";
		// echo Session::get('sess_store_id');
		// echo "<br>";
		// exit;
	}

	public function login_logout_log($is_logout = 'N')
	{
		if (Session::has('sess_store_id') && Session::get('sess_store_id') != '' && Session::has('sess_storeuserid') && Session::get('sess_storeuserid') != '') {

			if (Session::has('sess_store_logid') && Session::get('sess_store_logid') != '') {
				$LogArr = array(
					'store_user_id'		=> Session::get('sess_storeuserid'),
					'store_id' 			=> Session::get('sess_store_id'),
					'logout_time' 		=> $is_logout == 'Y' ? date("Y-m-d H:i:s") : ""
				);

				$CurrLog = StoreLoginLog::find(Session::get('sess_store_logid'));
				$udpRefer = $CurrLog->update($LogArr);
			} else {
				$LogArr = array(
					'store_user_id'		=> Session::get('sess_storeuserid'),
					'store_id' 			=> Session::get('sess_store_id'),
					'login_time' 		=> $is_logout == 'N' ? date("Y-m-d H:i:s") : ""
				);
				$LoginLog = StoreLoginLog::create($LogArr);
				$log_id = $LoginLog->log_id;
				Session::put('sess_store_logid', $log_id);
			}
			// $LoginLog = StoreLoginLog::create($LogInsert);
			// $log_id = $LoginLog->log_id;
			// Session::put('sess_store_logid',$log_id);
		}
	}

	public function searchProduct(Request $request)
	{
		$term = $request->get('term');
		$store_id = auth('store')->user()->store_id;

		$products = Products::select(
					'pi.current_stock as store_stock',
					'pu_products.*'
				)
				->join('pu_store_inventory as pi', 'pi.products_id', '=', 'pu_products.products_id')
				->where('pi.store_id', $store_id)
				////->where('pi.current_stock', '>', 0)
				->where('pu_products.status', '1')
				->where(function ($query) use ($term) {
					$query->where('pu_products.product_name', 'LIKE', "%{$term}%")
						->orWhere('pu_products.sku', 'LIKE', "%{$term}%")
						->orWhere('pu_products.UPC', 'LIKE', "%{$term}%");
				})
				->orderBy('pi.current_stock','DESC')
				->limit(50)
				->get();
		$totalResults = count($products);
		$result = [];

		foreach ($products as $product) {

			$ProductRs = $this->SetProduct($product);

			// Log::warning('Product Recordset: Product Recordset', [
			// 	'ProductRs' => json_encode($ProductRs)
			// ]);

			if(file_exists(config('global.PRD_LARGE_IMG_PATH').$product->image) && !empty($product->image)){
				$thumb_image = config('global.PRD_LARGE_IMG_URL').$product->image;
			}
			else{
				$thumb_image = config('global.NO_IMAGE_LARGE');
			}

			$chkSKUInCart = array();
			if(Session::has('ShoppingCart.Cart')){
				$Cart = Session::get('ShoppingCart.Cart');
				$TotalItems = count(Session::get('ShoppingCart.Cart'));
				for($c = 0; $c < $TotalItems; $c++){
					if(isset($Cart[$c]["SKU"]) && $Cart[$c]["SKU"]!=''){
						array_push($chkSKUInCart,$Cart[$c]["SKU"]);
					}
				}
			}

			$chkExists = 0; //$this->ProductCheckInCart($product->products_id, 1);
			if(in_array($product->sku,$chkSKUInCart)){
				$chkExists = 1;
			} else {
				$chkExists = 0;
			}
			$result[] = [
				'label' => $product->product_name . ' | SKU: ' . $product->sku,
				'value' => $product->product_name,
				'products_id' => $product->products_id,
				'short_description' => $product->short_description,
				'sku' => $product->sku,
				'upc' => $product->UPC,
				'size' => $product->size,
				'price' => $ProductRs->product_price, //$product->our_price,
				'stock' => $product->store_stock,
				'image' => $thumb_image,
				'totalResults' => $totalResults,
				'existsincart' => $chkExists ? 1 : 0
			];
		}
		return response()->json($result);
	}

	public function CreateOrder(Request $request)
	{
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Create Order';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		//$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'store_device_status.js', 'create-store-order.js'];
		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'store_device_status.js','jquery-ui1.12.1.js', 'create-store-order.js'];
		$store_user = Auth::guard('store')->user();
		$this->PageData['store_user_id'] =  $store_user->store_user_id;

		if(Session::has('ShoppingCart.sess_guest_icustomerid')) {
			Session::forget('ShoppingCart.sess_guest_icustomerid');
		}

		/*$isshow_dailyopen_pending = 0;
		$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_user->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
		//dd($cashd_res);*/
		if ($request->get('shared_isshow_dailyopen_pending') == 1) {
			//$isshow_dailyopen_pending = 1;

			return redirect('/store/cash-drawer-daily-open')->withErrors(['error' => 'Please submit the "Cash Drawer - Daily Open" to verify your cash amounts and open your store to create new order.']);
			//return redirect('/store/cash-drawer-daily-open');
		}

		return view('pos.createorder')->with($this->PageData);
	}
	public function Logout(Request $request)
	{
		$this->login_logout_log('Y');
		Auth::guard('store')->logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		Session::forget('sess_store_id');
		Session::forget('sess_storeuserid');
		Session::forget('sess_store_logid');
		Session::forget('sess_storeuseremail');
		Session::forget('sess_storeusername');
		//Session::forget('isshow_dailyopen_pending');
		Session::forget('sess_store_email');
		Session::forget('sess_store_prefix');

		session_start();
		$_SESSION['sess_admin_adminID'] 	= "";
		$_SESSION['sess_admin_email'] 		= "";
		$_SESSION['sess_admin_type'] 		= "";
		$_SESSION['sess_admin_roleId'] 		= "";
		$_SESSION['sess_admin_role'] 		= "";
		$_SESSION['sess_admin_user_type'] 	= "";
		$_SESSION['sess_store_userid'] = "";
		$_SESSION['sess_store_name'] = "";

		$_SESSION['isStoreLogin'] = '';

		unset($_SESSION['sess_admin_adminID']);
		unset($_SESSION['sess_admin_email']);
		unset($_SESSION['sess_admin_type']);
		unset($_SESSION['sess_admin_roleId']);
		unset($_SESSION['sess_admin_role']);
		unset($_SESSION['sess_admin_user_type']);
		unset($_SESSION['sess_store_userid']);
		unset($_SESSION['sess_store_name']);

		unset($_SESSION['isStoreLogin']);

		setcookie(
			"pos_maxaroma",
			"",
			[
				'expires' => time() - 3600,
				'path' => '/',
				'secure' => true,
				'httponly' => true,
				'samesite' => 'Strict'
			]
		);
		setcookie(
			"pos_maxaroma_token",
			"",
			[
				'expires' => time() - 3600,
				'path' => '/',
				'secure' => true,
				'httponly' => true,
				'samesite' => 'Strict'
			]
		);

		return redirect()->to('/store/login.html');
		//return redirect(config('global.SITE_URL'));
	}

	public function Login(Request $request)
	{
		if (Auth::guard('store')->check()) {
			return redirect('/store/store-dashboard.html');
		}

		// if(!Auth::guard('store')->check() && $request->cookie('pos_maxaroma_token') && !empty($request->cookie('pos_maxaroma_token')))
        // {
        //     return redirect('/store/login-from-cookiee');
        // }

		session_start();
		$_SESSION['sess_admin_adminID'] = '';
		$_SESSION['sess_admin_email'] = '';
		$_SESSION['sess_admin_type'] = '';
		$_SESSION['sess_admin_roleId'] = '';
		$_SESSION['sess_admin_user_type'] = '';
		$_SESSION['referePage'] = '';
		$_SESSION['referePage1'] = '';
		$_SESSION['sess_store_id'] = '';
		$_SESSION['sess_admin_role'] = '';
		$_SESSION['sess_store_name'] = '';
		$_SESSION['isStoreLogin'] = '';
		$_SESSION['ConnectedCardReaderId'] = "";
		$_SESSION['ConnectedCardReaderName'] = "";
		$_SESSION['ConnectedCardReaderLocationID'] = "";

		unset($_SESSION['sess_admin_adminID']);
		unset($_SESSION['sess_admin_email']);
		unset($_SESSION['sess_admin_type']);
		unset($_SESSION['sess_admin_roleId']);
		unset($_SESSION['sess_admin_user_type']);
		unset($_SESSION['referePage']);
		unset($_SESSION['referePage1']);
		unset($_SESSION['sess_store_id']);
		unset($_SESSION['sess_admin_role']);
		unset($_SESSION['sess_store_name']);
		unset($_SESSION['isStoreLogin']);
		unset($_SESSION['sess_store_userid']);
		unset($_SESSION['ConnectedCardReaderId']);
		unset($_SESSION['ConnectedCardReaderName']);
		unset($_SESSION['ConnectedCardReaderLocationID']);
		session_destroy();

		if ((isset($request->automated_login) && $request->automated_login == 'yes') || (isset($request->action) && ($request->action == 'signin' || $request->action == 'signin_with_pin')))
		{
			if(!$request->automated_login)
			{
				if($request->action == 'signin_with_pin')
				{
					$validatedData = $request->validate([
						'store_pin' => 'required|exists:pu_store_user,store_user_pin'
					], [
						'store_pin.required' => "Enter Your PIN",
						'store_pin.exists' => "Invalid PIN. Please try again."
					]);
				} else {
					$validatedData = $request->validate([
						'email' => 'required|email',
						'password' => 'required'
					], [
						'email.required' => config('message.Login.ValidEmail'),
						'email.email' => config('message.Login.ValidEmail'),
						'password.required' => config('message.Login.Password')
					]);
				}
			}
			if($request->automated_login == 'yes')
			{
				if (Session::has('sess_storeuserid') && Session::get('sess_storeuserid') > 0) {
					Auth::logout();
				}
				$request->email = $request->vemail;

				$StoreUserQry = StoreUser::where('store_user_email', $request->email)
					->where('store_user_status', '1')
					->where('registration_type', 'M');
				if ($request->vpassword != 'null' && $request->vpassword != null && $request->vpassword != '') {
					$StoreUserQry->where('password', md5($request->vpassword));
				}
			} elseif($request->action == 'signin_with_pin'){
				$StoreUserQry = StoreUser::where('store_user_pin', $request->store_pin)
					->where('store_user_status', '1');
			} else {
				$StoreUserQry = StoreUser::where('store_user_email', $request->email)
					->where('store_user_password', md5($request->password))
					->where('store_user_status', '1');
				//->where('registration_type','M');
			}

			$remember_me = $request->has('remember_me') ? true : false;
			$StoreUser = $StoreUserQry->first();

			$remember_me = $request->has('remember_me') ? true : false;
			// echo "<pre>";
			// print_r($request->all());

			//$remember_me = false;
			if ($StoreUser && $StoreUser->count() > 0) {
				Auth::guard('store')->login($StoreUser, $remember_me);
				$request->session()->regenerate();
				if (isset($StoreUser->{'2fa_security'}) && $StoreUser->{'2fa_security'} == "On") {
					$random_num = random_int(100000, 999999);

					$StoreUserData = array(
						'2fa_security_code' => $random_num,
					);
					$stUser = StoreUser::find($StoreUser->store_user_id);
					$stUser->update($StoreUserData);

					if (config('global.OMNISEND_PROG') == true) {
						//$Data = ['toMail' => $StoreUser->store_user_email, '2fa_code' => (string)$random_num];
						$Data = ['toMail' => 'tempchecknew@gmail.com', '2fa_code' => (string)$random_num];
						OmanisendRequest('68e62a6430300b38d35687a5', $Data);
					}

					Session::put('sess_twofactoruserid', $StoreUser->store_user_id);

					return redirect('/store/store-security.html');
				}

				Session::put('sess_storeuseremail', $StoreUser->store_user_email);
				Session::put('sess_storeusername', $StoreUser->first_name);
				Session::put('sess_storeuserid', $StoreUser->store_user_id);
				Session::put('sess_store_id', $StoreUser->store_id);

				$StoreInfo = DB::table('pu_stores')->select('store_email','stores_prefix')->where('store_id', $StoreUser->store_id)->first();
				Session::put('sess_store_email', $StoreInfo->store_email);
				Session::put('sess_store_prefix', $StoreInfo->stores_prefix);

				/*$isshow_dailyopen_pending = 2;
				$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $StoreUser->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
				//dd($cashd_res);
				if (empty($cashd_res)) {
					$isshow_dailyopen_pending = 1;
				}
				Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);*/

				$this->login_logout_log();
				$storeUserFname = '';
				if (isset($StoreUser->first_name) &&  $StoreUser->first_name != '') {
					$storeUserFname = $StoreUser->first_name;
				}
				$storeUserlname = '';
				if (isset($StoreUser->last_name) && $StoreUser->last_name != '') {
					$storeUserlname = $StoreUser->last_name;
				}
				Session::put('sess_storeusername', $storeUserFname . "|" . $storeUserlname);

				//Manage Admin Session Start

				$result = DB::table('pu_store_user as su')
					->join('pu_admin as a', 'su.store_user_email', '=', 'a.email')
					->join('pu_admin_roles as r', 'a.role_id', '=', 'r.role_id')
					->where('su.store_user_id', $StoreUser->store_user_id)
					->select(
						'su.store_user_id',
						'su.store_id',
						'su.first_name',
						'su.last_name',
						'a.admin_id',
						'a.email',
						'a.admin_type',
						'a.role_id',
						'r.name as role_name',
						'r.user_type'
					)->first();

				ini_set('session.gc_maxlifetime', 7200);
				session_start();

				$_SESSION['sess_admin_adminID'] 	= "";
				$_SESSION['sess_admin_email'] 		= "";
				$_SESSION['sess_admin_type'] 		= "";
				$_SESSION['sess_admin_roleId'] 		= "";
				$_SESSION['sess_admin_role'] 		= "";
				$_SESSION['sess_admin_user_type'] 	= "";
				$_SESSION['sess_store_userid'] = "";
				$_SESSION['sess_store_id'] = "";
				$_SESSION['sess_store_name'] = "";
				$_SESSION['ConnectedCardReaderId'] = "";
				$_SESSION['ConnectedCardReaderName'] = "";
				$_SESSION['ConnectedCardReaderLocationID'] = "";

				unset($_SESSION['sess_admin_adminID']);
				unset($_SESSION['sess_admin_email']);
				unset($_SESSION['sess_admin_type']);
				unset($_SESSION['sess_admin_roleId']);
				unset($_SESSION['sess_admin_role']);
				unset($_SESSION['sess_admin_user_type']);
				unset($_SESSION['sess_store_userid']);
				unset($_SESSION['sess_store_id']);
				unset($_SESSION['sess_store_name']);
				unset($_SESSION['ConnectedCardReaderId']);
				unset($_SESSION['ConnectedCardReaderName']);
				unset($_SESSION['ConnectedCardReaderLocationID']);

				$sess_admin_adminID = $result->admin_id ?? "";
				$sess_admin_email = $result->email ?? "";
				$sess_admin_type = $result->admin_type ?? "";
				$sess_admin_roleId = $result->role_id ?? "";
				$sess_admin_role = $result->role_name ?? "";
				$sess_admin_user_type = $result->user_type ?? "";

				$_SESSION['sess_store_userid'] 	= $StoreUser->store_user_id;
				$_SESSION['sess_store_id'] 	= $StoreUser->store_id;
				$_SESSION['sess_store_name'] 	= $result->first_name ?? "" . "|" . $result->last_name ?? "";

				$_SESSION['sess_admin_adminID'] 	= $sess_admin_adminID;
				$_SESSION['sess_admin_email'] 		= $sess_admin_email;
				$_SESSION['sess_admin_type'] 		= $sess_admin_type;
				$_SESSION['sess_admin_roleId'] 		= $sess_admin_roleId;
				$_SESSION['sess_admin_role'] 		= $sess_admin_role;
				$_SESSION['sess_admin_user_type'] 	= $sess_admin_user_type;
				$_SESSION['isStoreLogin'] = 'Y';

				$CheckStoreCardReader = DB::table('pu_store_card_reader')->where('store_id', $StoreUser->store_id)->where('status', '1')->first();
				if ($CheckStoreCardReader) {
					if (!empty($CheckStoreCardReader->reader_id) && !empty($CheckStoreCardReader->location)) {
						Session::put('ConnectedCardReaderId', $CheckStoreCardReader->reader_id);
						Session::put('ConnectedCardReaderName', $CheckStoreCardReader->reader_name);
						Session::put('ConnectedCardReaderLocationID', $CheckStoreCardReader->location);
					}
				}

				$name = "pos_maxaroma";
				$value = "store_pos";
				$week = time() + (24 * 60 * 60);
				setcookie(
					"pos_maxaroma",
					$value,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);

				$pos_token = base64_encode($StoreUser->store_user_email);
				setcookie(
					"pos_maxaroma_token",
					$pos_token,
					[
						'expires' => time() + (24 * 60 * 60),
						'path' => '/',
						'secure' => true,
						'httponly' => true,
						'samesite' => 'Strict'
					]
				);

				return redirect('/store/store-dashboard.html');
			} else {
				return redirect()->back()
					->withInput()
					->withErrors([
						'Failed' => config('message.Login.Failed'),
					]);
			}
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE') . ' :: Store Member Login';
		$this->PageData['JSFILES'] = ['login.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['FromCheckout'] = "";

		return view('pos.login')->with($this->PageData);
	}

	function ValidateSalesPerson(Request $request)
	{
		$email = $request->input('email');
		$store_id = 0;
		if (Session::has('sess_store_id') && Session::get('sess_store_id') != '') {
			$store_id = Session::get('sess_store_id');
		}

		$salesPerson = StoreSalesPerson::select('email', 'name')
			->where('status', '1')
			->where('store_id', $store_id)
			->where('email', $email)
			->first();

		if ($salesPerson) {
			return 1;
		} else {
			return 0;
		}
	}

	public function SearchSalesPerson(Request $request)
	{
		$searchval = $request->input('val');
		$no_email = $request->input('no_email');
		$store_id = 0;
		if (Session::has('sess_store_id') && Session::get('sess_store_id') != '') {
			$store_id = Session::get('sess_store_id');
		}

		$storeusers = StoreSalesPerson::select('sales_person_id', 'email', 'name', 'status')
			->where('status', '1')
			->where('store_id', $store_id)
			->where(function ($query) use ($searchval) {
				$query->where('email', 'like', "%{$searchval}%")
					->orWhere('name',  'like', "%{$searchval}%");
			});
		//->get();

		if ($no_email != '') {
			$exc_email = array_map('trim', explode(',', $no_email));
			$storeusers->whereNotIn('email', $exc_email);
		}
		$storeusers = $storeusers->get();
		if ($storeusers->count() > 0) {
			return response()->json(['storeusers' => $storeusers]);
		} else {
			return response()->json([]);
		}
	}

	public function AddSalesPerson(Request $request)
	{
		$totAgents = $request->input('totAgents');
		Session::forget('ShoppingCart.SalesAgent');
		$stusr = 0;
		for ($ms = 1; $ms <= $totAgents; $ms++) {
			$sales_person_id = $request->input('sales_person_id' . $ms);
			$sales_person_per = $request->input('sales_person_per' . $ms);

			$storeusers = DB::table('pu_store_sales_person')
				->select('email', 'name')
				->where('status', '=', '1')
				->where('store_id', '=', auth('store')->user()->store_id)
				->where('sales_person_id', '=', $sales_person_id)->first();

			if ($storeusers) {
				Session::put('ShoppingCart.SalesAgent.' . $stusr . '.sales_person_id', $sales_person_id);
				Session::put('ShoppingCart.SalesAgent.' . $stusr . '.sales_person_email', $storeusers->email);
				Session::put('ShoppingCart.SalesAgent.' . $stusr . '.sales_person_name', $storeusers->name);
				Session::put('ShoppingCart.SalesAgent.' . $stusr . '.sales_person_per', $sales_person_per);
				Session::save();
				$stusr++;
			}
		}

		return 1;
	}

	public function splitOrderPlace(Request $request, $SplitArray = array())
	{
		$StoreOrderArr = array();
		$WebsiteOrderArr = array();

		$CreditData = $this->GetCreditLimitAmount();
		$CreditAmt = $CreditData['CreditLimit'];
		$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
		$cust_current_credit_limit = $CreditAmt;
		$apply_credit = $CreditDiscount;
		$remaining_credit = $CreditData['RemainCreditLimit'];

		Session::put('ShoppingCart.WebsiteAutoDiscount', 0);
		Session::put('ShoppingCart.StoreAutoDiscount', 0);
		Session::put('ShoppingCart.WebsiteQuantityDiscount', 0);
		Session::put('ShoppingCart.StoreQuantityDiscount', 0);
		Session::put('ShoppingCart.PromoCoupon.WebsiteCouponDiscount', 0);
		Session::put('ShoppingCart.PromoCoupon.StoreCouponDiscount', 0);
		Session::put('ShoppingCart.WebsiteYotpoRewardDiscount', 0);
		Session::put('ShoppingCart.StoreYotpoRewardDiscount', 0);
		Session::put('ShoppingCart.WebsiteDogoDiscount', 0);
		Session::put('ShoppingCart.StoreDogoDiscount', 0);
		Session::put('ShoppingCart.WebsiteGiftCoupon.Value', 0);
		Session::put('ShoppingCart.StoreGiftCoupon.Value', 0);
		Session::put('ShoppingCart.StoreGiftWrapping', '');
		Session::put('ShoppingCart.WebsiteGiftWrapping', '');
		Session::put('ShoppingCart.StoreLoyaltyDiscount', 0);
		Session::put('ShoppingCart.WebsiteLoyaltyDiscount', 0);

		if ($CreditDiscount > 0) {
			$use_credit_limit = 'Yes';
		} else {
			$use_credit_limit = 'No';
		}
		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			$groupedOrders = collect(Session::get('ShoppingCart.Cart'))->groupBy('OrderType')->toArray();
			$StoreOrderArr = array_values($groupedOrders["Store"]);
			$WebsiteOrderArr = array_values($groupedOrders["Website"]);
			$StoreSubTotal = $this->SplitCalculateSubTotal($StoreOrderArr, "Store");
			$WebsiteSubTotal = $this->SplitCalculateSubTotal($WebsiteOrderArr, "Website");
			if (!empty($this->GetAllDiscounts('AutoDiscount')) && $this->GetAllDiscounts('AutoDiscount') > 0) {
				$this->ApplySplitAutoDiscountNew($StoreOrderArr, $WebsiteOrderArr);
			}

			if (!empty($this->GetAllDiscounts('QuantityDiscount')) && $this->GetAllDiscounts('QuantityDiscount') != '') {
				$this->ApplySplitQuantityDiscountNew($StoreOrderArr, $WebsiteOrderArr);
			}

			if (!empty($this->GetAllCoupons('CouponCode')) && $this->GetAllCoupons('CouponCode') != '') {
				$this->ApplySplitCouponDiscountNew($StoreOrderArr, $WebsiteOrderArr);
			}

			$yotporewardcode = '';

			if (Session::has('ShoppingCart.YotpoRewardCode') && Session::get('ShoppingCart.YotpoRewardCode') != '') {
				$yotporewardcode = Session::get('ShoppingCart.YotpoRewardCode');
			}
			if (!empty($yotporewardcode) && $yotporewardcode != '') {
				$this->ApplySplitRewardDiscountNew($StoreOrderArr, $WebsiteOrderArr);
			}

			if (!empty($this->GetAllDiscounts('DogoDiscount')) && $this->GetAllDiscounts('DogoDiscount') != '') {
				$this->ApplySplitBogoDiscount($StoreOrderArr, $WebsiteOrderArr);
			}

			Session::put('ShoppingCart.Websitecredit_limit_discount', 0);
			Session::put('ShoppingCart.Storecredit_limit_discount', 0);

			//$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
			if (!empty($CreditDiscount) && $CreditDiscount != '') {
				$this->ApplySplitCreditDiscount($StoreOrderArr, $WebsiteOrderArr);
			}

			Session::put('ShoppingCart.StoreGiftCoupon.Code', '');
			Session::put('ShoppingCart.StoreGiftCoupon.Value', 0.0);
			Session::put('ShoppingCart.StoreGiftCoupon.Applicable_Value', 0.0);

			Session::put('ShoppingCart.WebsiteGiftCoupon.Code', '');
			Session::put('ShoppingCart.WebsiteGiftCoupon.Value', 0.0);
			Session::put('ShoppingCart.WebsiteGiftCoupon.Applicable_Value', 0.0);
			if (Session::has('ShoppingCart.GiftCoupon.Code') && Session::get('ShoppingCart.GiftCoupon.Code') != '') {
				if (config('Settings.GIFTCERTIFICATEFLAG') == "Yes") {
					$this->ApplySplitGiftCouponsNew($StoreOrderArr, $WebsiteOrderArr);
				}
			}

			$Tax = $this->GetAllCharges('Tax');

			if (!empty($Tax) &&  $Tax > 0) {
				$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');
				$this->SplitTaxCalculation($Tax,"Store",$onlyGCPurchased);
				$this->SplitTaxCalculation($Tax,"Website",$onlyGCPurchased);
			}

			if (!empty($this->GetAllCharges('GiftWrappingCharge')) &&  $this->GetAllCharges('GiftWrappingCharge') > 0) {
				$this->ApplySplitGiftWrapping("Store", $StoreOrderArr);
				$this->ApplySplitGiftWrapping("Website", $WebsiteOrderArr);
			}
			/** Google Membership Pricing - Start */
			if (Session::has('ShoppingCart.LoyaltyDiscount') && Session::get('ShoppingCart.LoyaltyDiscount') > 0) {
				$this->ApplyLoyaltyDiscount($StoreOrderArr, $WebsiteOrderArr);
			}
			/** Google Membership Pricing - End */
		}

		if (isset($request->is_btm_ap_chkout) && $request->is_btm_ap_chkout == "Yes") {	//bottom afterpay checkout
			$request->PaymentMethod = "PAYMENT_PAYWITHAFTERPAY";
		}

		$is_amazon = 0;
		if ($this->GetNetTotal() <= 0 && $CreditDiscount > 0 && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && Session::get("etype") == "M" && Session::get("sess_icustomerid") > 0 && Session::get("is_dropshipper") != "Yes") {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod  = "PAYMENT_CL";
		}

		if ($this->GetNetTotal() <= 0 && $CreditDiscount <= 0) {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod = 'PAYMENT_GIFT_CERTIFICATE';
		}

		if ($this->GetNetTotal() <= 0)
		{
			$request->PaymentMethod = 'PAYMENT_FREEITEM';
		}

		if ($request->PaymentMethod != 'PAYMENT_PAYPALEC' && (!isset($request->is_paypal) || $request->is_paypal != "yes")) {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_PAYPALEC' && isset($request->is_paypal) && $request->is_paypal == "yes") {
			$this->setPaymentDetail($request);
		}
		if ($request->PaymentMethod == 'PAYMENT_PAYWITHAFTERPAY') {
			$this->setPaymentDetail($request);
		}
		if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_STRIPE_BUTTON') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_AUTHORIZENETCC' || $request->PaymentMethod == 'PAYMENT_PAYPALCC'  || $request->PaymentMethod == 'PAYMENT_BRAINTREECC') {
			if ($request->CCType == '' || $request->CCNumber == '' ||  $request->CCMonth == '' || $request->CCYear == '' || $request->CCholdername == '') {
				$err_msg = "Error while processing your order, Please try again.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError:' . $request->PaymentMethod . ' - Credit Card issue.');
				return redirect()->back();
			}
		}

		if ($this->GetNetTotal() <= 0) {
			$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
			$GCAmount = 0;
			$GCCode = "";
			$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');
			if ($GiftCoupon) {
				$GCAmount = $GiftCoupon['Value'];
				$GCCode = $GiftCoupon['Code'];
			}
			$log['GiftCoupon'] = json_encode($GiftCoupon);
			$log['arrPaymentDetail'] = json_encode($arrPaymentDetail);
			if (empty($GCCode) && $GCCode == '' && isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] != '' && $arrPaymentDetail['Payment_Type'] != 'PAYMENT_CL' && $arrPaymentDetail['Payment_Type'] != 'PAYMENT_FREEITEM') {
				$err_msg = "Order total is invalid, please try again.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				return redirect('/shoppingcart');
			}
		}

		$customer_id = (int)Session::get('sess_icustomerid');

		if (!Session::has('sess_icustomerid') || empty($customer_id)) {
			$err_msg = "Error while processing your order, Please try again.";
			$log['err_msg'] = $err_msg;
			addLog("PlaceOrder", $log);
			Session::flash('PlaceOrderError', $err_msg);
			Log::info('PlaceOrderError: Customer Not Found');
			return redirect()->back();
		}

		$Billing  = Session::get('ShoppingCart.BillingAddress');
		if (!isset($_REQUEST['AppleGPay'])) {
			if (($request->PaymentMethod != 'PAYMENT_STRIPE_BUTTON' && $request->PaymentMethod != 'STRIPE_GOOGLEPAY' && $request->PaymentMethod != 'STRIPE_APPLEPAY' && $request->PaymentMethod != 'PAYMENT_PAYWITHAMAZON' && $is_amazon != 1) && (trim($Billing['first_name']) == '' || trim($Billing['last_name']) == '' || trim($Billing['address1']) == '' || trim($Billing['city']) == '' ||
				trim($Billing['zip']) == '' || trim($Billing['state']) == '' || trim($Billing['country']) == '' || trim($Billing['phone']) == '' ||
				trim($Billing['email']) == '')) {
				$err_msg = "Please fill the required fields for billing information. ";
				$log['Billing'] = json_encode($Billing);
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: BillingAddress Validation');
				return redirect()->back();
			}
		}

		$Shipping = Session::get('ShoppingCart.ShippingAddress');
		$log['Shipping'] = $Shipping;
		addLog("PlaceOrder", $log);
		$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');

		if ($onlyGCPurchased == 0) {

			if ($request->PaymentMethod != 'PAYMENT_PAYWITHAMAZON' && $request->PaymentMethod != 'STRIPE_GOOGLEPAY' && $request->PaymentMethod != 'STRIPE_APPLEPAY' && ((isset($Shipping['first_name']) && trim($Shipping['first_name']) == '') || (isset($Shipping['last_name']) &&	trim($Shipping['last_name']) == '') || (isset($Shipping['address1']) && trim($Shipping['address1']) == '') || (isset($Shipping['city']) && trim($Shipping['city']) == '') || (isset($Shipping['zip']) && trim($Shipping['zip']) == '') || (isset($Shipping['state']) && trim($Shipping['state']) == '') || (isset($Shipping['country']) && trim($Shipping['country']) == ''))) {
				$err_msg = "Please fill the required fields for shipping information. ";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: ShippingAddress Validation');
				return redirect()->back();
			}
		}

		if ($onlyGCPurchased == 1) {
			Session::put('ShoppingCart.BillingAsShipping', 'Yes');
			$Billing['sameasbill'] = 'Yes';
			$this->SetShippingAddress($Billing);
			$Shipping = Session::get('ShoppingCart.ShippingAddress');
		}

		$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');
		if (Session::has('ShoppingCart.Payment_Detail')) {

			if (((isset($arrPaymentDetail['Payment_Type']) && trim($arrPaymentDetail['Payment_Type']) == '') || (isset($arrPaymentDetail['Payment_Type']) &&	$arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALEC')) && (!isset($request->is_paypal))) {
				$err_msg =  "Please choose payment method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Payment Method Not Selected');
				return redirect()->back();
			}

			//if(trim($arrPaymentDetail['Payment_Type']) == '' && isset($request->is_paypal) && $request->is_paypal == "yes")
			if (isset($arrPaymentDetail['Payment_Type']) && trim($arrPaymentDetail['Payment_Type']) == '' && isset($request->is_paypal) && $request->is_paypal == "yes") {
				$err_msg =  "Please choose payment method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Payment Method Not Selected');
				return redirect()->back();
			}

			if ($arrPaymentDetail['Payment_Type'] == 'PAYMENT_AUTHORIZENETCC' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALCC') {
				if ($arrPaymentDetail['CCType'] == '' || $arrPaymentDetail['CCNumber'] == '' || $arrPaymentDetail['CCMonth'] == '' || $arrPaymentDetail['CCYear'] == '' || $arrPaymentDetail['CCName'] == '') {
					$msg = "Please fill the credit card information.";
					$log['err_msg'] = $err_msg;
					addLog("PlaceOrder", $log);
					Session::flash('PlaceOrderError', $msg);
					Log::info('PlaceOrderError: ' . $arrPaymentDetail['Payment_Type'] . ' Credit Card issue.');
					return redirect()->back();
				}
			}
		}
		$ShippingInfo = Session::get('ShoppingCart.Shipping');
		if ($onlyGCPurchased == 0) {
			if (empty($ShippingInfo['ShippingMethodID']) || (int)$ShippingInfo['ShippingMethodID'] <= 0) {
				$err_msg =  "Please choose shipping method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Shipping Method Not Selected');
				return redirect()->back();
			}
		}

		$currency_info = Session::get('currency_code') . "#" . Session::get('currency_symbol') . "#" . Session::get('currency_rate');
		if (Session::has('etype') && Session::get('etype') == 'M')
			$checkout_type = 'M';
		else
			$checkout_type = 'G';

		$cc_info = "";
		if (Session::has('ShoppingCart.Payment_Detail')) {
			if ($arrPaymentDetail['Payment_Type'] == 'PAYMENT_AUTHORIZENETCC' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALCC') {
				$cc_info = $arrPaymentDetail['CCType'] . "," . substr(trim($arrPaymentDetail['CCNumber']), -4) . ",Exp." . $arrPaymentDetail['CCMonth'] . "/" . $arrPaymentDetail['CCYear'] . ",CSC." . $arrPaymentDetail['CSC'];
			}
		}

		$ShippingInfo	 = Session::get('ShoppingCart.Shipping');
		$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
		$CouponCode = $this->GetAllCoupons('CouponCode');

		$couponRS = array();
		$OneCouponCode = "";
		$coupon_id = '';
		$SecondCouponCode = "";
		$Second_coupon_id = "";

		$yotporewardcode = '';

		if (Session::has('ShoppingCart.YotpoRewardCode') && Session::get('ShoppingCart.YotpoRewardCode') != '') {
			$yotporewardcode = Session::get('ShoppingCart.YotpoRewardCode');
		}

		if (!empty($CouponCode)) {
			$couponRS = Coupon::where('coupon_number', '=', $CouponCode)->limit(1)->get();
			if ($couponRS && $couponRS->count() > 0) {
				$OneCouponCode = $couponRS[0]->coupon_number;
				$coupon_id = $couponRS[0]->coupon_id;
			}
		}

		## Wholesaler Field Start ###
		$w_user_type = Session::get('eusertype');
		$w_ilevelid  = (Session::has('ilevelid')) ? Session::get('ilevelid') : 0;
		## Wholesaler Field End ###

		$GiftValue = $this->FreeGiftValue($this->GetNetTotal());

		if (count($GiftValue) > 0) {
			$free_gift = Session::get('ShoppingCart.FreeGift');
			$gift_from = Session::get('ShoppingCart.GiftFrom');
			$gift_to   = Session::get('ShoppingCart.GiftTo');
			$gift_message_customer = Session::get('ShoppingCart.GiftMessageCustomer');
		} else {
			$free_gift = '';
			$gift_from = '';
			$gift_to   = '';
			$gift_message_customer = (isset($request->gift_message_customer)) ? $request->gift_message_customer : '';
			if (strtolower(trim($gift_message_customer ?? '')) == " *gift message") {
				$gift_message_customer = '';
			}
			Session::forget('ShoppingCart.FreeGift');
			Session::forget('ShoppingCart.GiftFrom');
			Session::forget('ShoppingCart.GiftTo');
			Session::forget('ShoppingCart.GiftMessageCustomer');
		}

		if (isset($request->is_stripe_applepay) && $request->is_stripe_applepay == "apple_pay") {
			$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayStripOrderPlaceLog.txt';
			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');
				$stringData = date("m/d/Y H:i:s") . " Eight Place Order " . json_encode(Session::get('ShoppingCart')) . " \n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
		}

		//Credit Limit Code End

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_DS')
			$is_dropship_order = 'Yes';
		else
			$is_dropship_order = 'No';

		if (isset($request->customer_comment) && strtolower(trim($request->customer_comment)) == "special request") {
			$request->customer_comment = "";
		}

		$referDiscountId = (Session::has('ShoppingCart.ReferDiscountId')) ? Session::get('ShoppingCart.ReferDiscountId') : 0;

		$merge_note = (Session::has('ShoppingCart.merge_note')) ? Session::get('ShoppingCart.merge_note') : "";
		$Payment_Type = (isset($arrPaymentDetail['Payment_Type'])) ? $arrPaymentDetail['Payment_Type'] : '';
		$Payment_Method = (isset($arrPaymentDetail['Payment_Method'])) ? $arrPaymentDetail['Payment_Method'] : '';

		$PaymentResponse = "";

		$customer_comment = (isset($request->customer_comment)) ? $request->customer_comment : '';
		$SubTotal = (float)Session::get('ShoppingCart.SubTotal');
		$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
		$GCAmount = 0;
		$GCCode = "";
		if ($GiftCoupon) {
			$GCAmount = $GiftCoupon['Value'];
			$GCCode = $GiftCoupon['Code'];
		}

		$paymentintentid = "";

		$ShippingSignatureFlag = 'No';
		if ($onlyGCPurchased == 0 && isset($request->shipsignatureflag) && $request->shipsignatureflag == 'Yes')
			$ShippingSignatureFlag = 'Yes';

		if ($onlyGCPurchased == 0 && isset($request->shipping_signature) && $request->shipping_signature == 'Yes')
			$ShippingSignatureFlag = 'Yes';

		for ($d = 0; $d < count($SplitArray); $d++) {
			$key = array_keys($SplitArray);
			$GroupArray = $SplitArray[$key[$d]];

			$this->SplitCalculateSubTotal($GroupArray, $key[$d]);

			$SubTotal = Session::get('ShoppingCart.' . $key[$d] . 'SubTotal');

			$fullShippingname = '';
			$is_maxtwoday = "No";
			$EstimatedDeliveryDate = '';
			$ShipMethodName = '';
			$ShipMethodCharge = 0;
			$OrdShipSignature = 0;
			$OrdShipInsurance = 0;

			/*$CreditData = $this->GetSplitCreditLimitAmount($key[$d]);
			$CreditAmt = $CreditData['CreditLimit'];
			$CreditDiscount = $this->GetSplitAllDiscounts('CreditLimitDiscount',array(),$key[$d]);
			$cust_current_credit_limit = $CreditAmt;
			$apply_credit = $CreditDiscount;
			$remaining_credit = $CreditData['RemainCreditLimit'];

			if($CreditDiscount>0){
				$use_credit_limit = 'Yes';
			}else{
				$use_credit_limit = 'No';
				}
			*/

			if ($key[$d] == "Website") {

				if ($onlyGCPurchased == 0) {
					if ($ShippingInfo["ShippingCharge"] > 0) {
						$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(" . Session::get('currency_symbol') . $ShippingInfo["ShippingCharge"] . ")</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
					} else {
						$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(Free)</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
					}
					if (strtolower($ShippingInfo['ShippingMethodName'] ?? '') == 'max2days') {
						$is_maxtwoday = "Yes";
					}
					$EstimatedDeliveryDate = Session::get("ShoppingCart.EstimatedDeliveryDate");
					$ShipMethodName = $ShippingInfo['ShippingMethodName'];
					$ShipMethodCharge = $this->GetShippingCharge();
					$OrdShipSignature = $this->GetAllCharges('ShippingSignature');
					$OrdShipInsurance = $this->GetAllCharges('ShippingInsurance');
				}

				$cur_date = date("Y-m-d");
				if ($EstimatedDeliveryDate == "" && strtotime($EstimatedDeliveryDate) < strtotime($cur_date)) {
					$EstimatedDeliveryDate = "0000-00-00 00:00:00";
				}
			}
			$CartArray = array();
			if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
				$CartArray = $GroupArray;
			}

			$OrderInsert = array(
				'customer_id'		=> $customer_id,
				'sub_total' 		=> $SubTotal,
				'shipping_amt' 		=> $ShipMethodCharge,
				'tax' 				=> $this->GetSplitAllCharges('Tax', $key[$d]),
				'gift_charge' 		=> $this->GetSplitAllCharges('GiftWrappingCharge', $key[$d]),
				'gift_message' 		=> '',
				'is_gift_order'		=> 'No',
				'handling_charge' 	=> '0.00',
				'wire_discount' 	=> '0.00',
				'auto_discount' 	=> $this->GetSplitAllDiscounts('AutoDiscount', $CartArray, $key[$d]),
				'quantity_discount'	=> $this->GetSplitAllDiscounts('QuantityDiscount', $CartArray, $key[$d]),
				'reward_discount'	=> $this->GetSplitAllDiscounts('YotpoRewardDiscount', $CartArray, $key[$d]),
				'coupon_amount' 	=> $this->GetSplitAllDiscounts('CouponDiscount', $CartArray, $key[$d]),
				'coupon_id' 		=> $coupon_id,
				'Second_coupon_id'	=> $yotporewardcode,
				'coupon_code' 		=> $CouponCode,
				'gc_amount' 		=> $this->GetSplitAllDiscounts('GiftCoupon', $CartArray, $key[$d]),
				'gc_code' 			=> $GCCode,
				'loyalty_discount'	=> Session::get('ShoppingCart.' . $key[$d] . 'LoyaltyDiscount') ?? 0,
				'order_total' 		=> $this->GetSplitNetTotal($key[$d]),
				'shipinfo' 			=> $ShipMethodName,
				'payment_type' 		=> $Payment_Type,
				'payment_method' 	=> $Payment_Method,
				'pay_status' 		=> 'Unpaid',
				'ccinfo' 			=> $cc_info,
				'customer_comment' 	=> $customer_comment,
				'status'			=> 'Pending',
				'currency_info'		=> $currency_info,
				'checkout_type' 	=> $checkout_type,
				'user_type' 		=> $w_user_type,
				'ilevelid' 			=> $w_ilevelid,
				//'level_price' 		=> $w_level_price,
				'ship_first_name' 	=> isset($Shipping['first_name']) ? $Shipping['first_name'] : '', //$Shipping['first_name'],
				'ship_last_name' 	=> isset($Shipping['last_name']) ? $Shipping['last_name'] : '', //$Shipping['last_name'],
				'ship_company' 		=> isset($Shipping['company']) ? $Shipping['company'] : '', //$Shipping['company'],
				'ship_email' 		=> isset($Shipping['email']) ? $Shipping['email'] : '', //$Shipping['email'],
				'ship_address1' 	=> isset($Shipping['address1']) ? $Shipping['address1'] : '', //$Shipping['address1'],
				'ship_address2' 	=> isset($Shipping['address2']) ? $Shipping['address2'] : '', //$Shipping['address2'],
				'ship_city' 		=> isset($Shipping['city']) ? $Shipping['city'] : '', //$Shipping['city'],
				'ship_zip' 			=> isset($Shipping['zip']) ? $Shipping['zip'] : '', //$Shipping['zip'],
				'ship_state' 		=> isset($Shipping['state']) ? $Shipping['state'] : '', //$Shipping['state'],
				'ship_country' 		=> isset($Shipping['country']) ? $Shipping['country'] : '', //$Shipping['country'],
				'ship_phone' 		=> isset($Shipping['phone']) ? $Shipping['phone'] : '', //$Shipping['phone'],
				'bill_first_name' 	=> isset($Billing['first_name']) ? $Billing['first_name'] : '', //$Billing['first_name'],
				'bill_last_name' 	=> isset($Billing['last_name']) ? $Billing['last_name'] : '', //$Billing['last_name'],
				'bill_company' 		=> isset($Billing['company']) ? $Billing['company'] : '', //$Billing['company'],
				'bill_email' 		=> isset($Billing['email']) ? $Billing['email'] : '', //$Billing['email'],
				'bill_address1' 	=> isset($Billing['address1']) ? $Billing['address1'] : '', //$Billing['address1'],
				'bill_address2' 	=> isset($Billing['address2']) ? $Billing['address2'] : '', //$Billing['address2'],
				'bill_city' 		=> isset($Billing['city']) ? $Billing['city'] : '', //$Billing['city'],
				'bill_zip' 			=> isset($Billing['zip']) ? $Billing['zip'] : '', //$Billing['zip'],
				'bill_state' 		=> isset($Billing['state']) ? $Billing['state'] : '', //$Billing['state'],
				'bill_country' 		=> isset($Billing['country']) ? $Billing['country'] : '', //$Billing['country'],
				'bill_phone' 		=> isset($Billing['phone']) ? $Billing['phone'] : '', ////$Billing['phone'],
				'customer_ip' 		=> $_SERVER['REMOTE_ADDR'],
				'customer_browser' 	=> $_SERVER['HTTP_USER_AGENT'],
				'is_only_gc'		=> (string)$onlyGCPurchased,
				'free_gift'			=> $free_gift,
				'gift_from'				=> $gift_from,
				'gift_to'				=> $gift_to,
				'gift_message_customer'	=> $gift_message_customer,
				'cust_current_credit_limit' => $cust_current_credit_limit,
				'apply_credit'          => $this->GetSplitAllDiscounts('CreditLimitDiscount', $CartArray, $key[$d]),
				'remaining_credit'      => $remaining_credit,
				'use_credit_limit'      => $use_credit_limit,
				'is_dropship_order'     => $is_dropship_order,
				'shipping_signature'	 => $OrdShipSignature,
				'is_shipping_signature' => ($key[$d] == "Website") ? $ShippingSignatureFlag : "No",
				'Is_GiftCertificatPurchase' => $this->GetCartAttribute('CheckGCPurchasedVal'),
				'EstimatedDeliveryDate' 	=> $EstimatedDeliveryDate,
				'fullshipping_info'		=> 	$fullShippingname,
				'merge_note'		=> 	$merge_note,
				'bogo_discount'	=> $this->GetSplitAllDiscounts('DogoDiscount', $CartArray, $key[$d]),
				'is_maxtwoday'	=> $is_maxtwoday,
				'route_shipping_insurance_charge' => $OrdShipInsurance,
				'vLang_flag' => Session::get('ShoppingCart.YotpoFreeGiftCoupon'),
				'paymentintentid' => $paymentintentid,
				'payment_gateway_response' => Session::get("PayMethodRes") ? Session::get("PayMethodRes") : '',
				'BraintreeResponse'        => Session::get('ShoppingCart.AfterPay.Checkout_Token') ? Session::get('ShoppingCart.AfterPay.Checkout_Token') : '',
				'order_type'			=> $key[$d],
			);

			if (Auth::guard('store')->check() && $key[$d] == "Store") {
				$store = Auth::guard('store')->user();
				$OrderInsert['store_id']   =  $store->store_id;
				$OrderInsert['order_type']   =  'Store';
			}

			$agent_email_id = '';
			$salesPersonId = 0;
			if (Auth::guard('store')->check() && $key[$d] == "Store") {
				$agent_email_id = Auth::guard('store')->user()->store_user_email;
				$salesPersonId = Auth::guard('store')->user()->store_user_id;
				$OrderInsert['sales_person_id'] = $salesPersonId;
			}

			$is_order_update = 'N';

			$log['PlaceOrderInsert'] = json_encode($OrderInsert);

			//echo "<pre>"; print_r($OrderInsert); exit;
			addLog("PlaceOrder", $log);
			$PlaceOrder = Order::create($OrderInsert);
			$OrderID = $PlaceOrder->orders_id;

			if ($key[$d] == "Store") {
				$aa = Session::put('ShoppingCart.StoreOrderID', $OrderID); // set order id in cart
			} else {
				$aa = Session::put('ShoppingCart.WebsiteOrderID', $OrderID);
			}
			if (isset($request->PaymentMethod) && $request->PaymentMethod == 'PAYMENT_PAYPALEC') {

				$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

				if (fopen($myFile, 'a+')) {
					$fh = fopen($myFile, 'a+');
					$stringData = date("m/d/Y H:i:s") . " PayPal Order Insert :" . json_encode($OrderInsert) . "\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}
			}

			$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

			if (fopen($myFile, 'a+')) {
				$stringData = '';
				$fh = fopen($myFile, 'a+');
				if (Session::has('ShoppingCart') && Session::get('ShoppingCart') != '') {
					$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Data : " . json_encode(Session::get('ShoppingCart')) . " :\n";
				}
				if (isset($OrderInsert) && !empty($OrderInsert)) {
					$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Order Data : " . json_encode($OrderInsert) . " :\n";
				}
				if (Session::has('sess_useremail') && Session::get('sess_useremail') != '') {
					$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Session User Email : " . Session::get('sess_useremail') . " :\n";
				}
				fwrite($fh, $stringData);
				fclose($fh);
			}
			if ($OrderID != "") {
				// To add 'OR' Change on :: 06-10-2015
				$CurrOrder = Order::find($OrderID);
				$Prefix = GetStorePrefix();
				if ($key[$d] == "Website") {
					$Prefix = "OR";
			    }

				$updateOrder = array('orders_no'	 => $Prefix . $OrderID);
				$udpRefer = $CurrOrder->update($updateOrder);

				//Update sf_acccountid in Customer
				if (Auth::guard('store')->check())
				{
					$storeUserInfo = Auth::guard('store')->user();
					$UpdateCustomer = ['sf_accountid' => $storeUserInfo->store_id];
					$cust_upd = Customer::where('customer_id', '=', $customer_id)->update($UpdateCustomer);
				}
				//Update sf_acccountid in Customer
			}

			$tempCart = $GroupArray;
			$cnt_row  = count($tempCart);

			$IsVender = "No";
			$IsAmazOR = 'No';

			$IsPerfumePWVendor = "No";
			$TaxValueNew = $this->GetAllCharges('TaxValue');
			if ($is_order_update == 'N') {
				for ($i = 0; $i < $cnt_row; $i++) {
					if (empty($this->GetAllDiscounts('CouponDiscount')) || $this->GetAllDiscounts('CouponDiscount') <= 0) {
						$tempCart[$i]['CouponDisItemWiseDiscout'] = 0;
					}
					if (empty($this->GetAllDiscounts('AutoDiscount')) || $this->GetAllDiscounts('AutoDiscount') <= 0) {
						$tempCart[$i]['AutoItemWiseDiscout'] = 0;
					}
					if (empty($this->GetAllDiscounts('QuantityDiscount')) || $this->GetAllDiscounts('QuantityDiscount') <= 0) {
						$tempCart[$i]['QuantityItemWiseDiscout'] = 0;
					}
					if (empty($this->GetAllDiscounts('YotpoRewardDiscount')) || $this->GetAllDiscounts('YotpoRewardDiscount') <= 0) {
						$tempCart[$i]['RewardItemWiseDiscout'] = 0;
					}
					if (empty($this->GetAllDiscounts('DogoDiscount')) || $this->GetAllDiscounts('DogoDiscount') <= 0) {
						$tempCart[$i]['BogoItemWiseDiscout'] = 0;
					}
					if (empty($this->GetAllDiscounts('GiftCoupon')) || $this->GetAllDiscounts('GiftCoupon') <= 0) {
						$tempCart[$i]['GiftCertificateItemWiseDiscout'] = 0;
					}
					/*if(empty($apply_credit) && $apply_credit<=0)
					{
						$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0;
					}*/
					$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0;
					$ActualPrice = 0;
					$CouponDisItemWiseDiscout = 0;
					$AutoItemWiseDiscout = 0;
					$QuantityItemWiseDiscout = 0;
					$RewardItemWiseDiscout = 0;
					$BogoItemWiseDiscout = 0;
					$GiftCertificateItemWiseDiscout = 0;
					$CreditLimitItemWiseDiscout = 0;

					if (!empty($tempCart[$i]['CouponDisItemWiseDiscout']) && $tempCart[$i]['CouponDisItemWiseDiscout'] > 0) {
						$CouponDisItemWiseDiscout = $tempCart[$i]['CouponDisItemWiseDiscout'];
					}
					if (!empty($tempCart[$i]['AutoItemWiseDiscout']) && $tempCart[$i]['AutoItemWiseDiscout'] > 0) {
						$AutoItemWiseDiscout = $tempCart[$i]['AutoItemWiseDiscout'];
					}
					if (!empty($tempCart[$i]['QuantityItemWiseDiscout']) && $tempCart[$i]['QuantityItemWiseDiscout'] > 0) {
						$QuantityItemWiseDiscout = $tempCart[$i]['QuantityItemWiseDiscout'];
					}
					if (!empty($tempCart[$i]['RewardItemWiseDiscout']) && $tempCart[$i]['RewardItemWiseDiscout'] > 0) {
						$RewardItemWiseDiscout = $tempCart[$i]['RewardItemWiseDiscout'];
					}
					if (!empty($tempCart[$i]['BogoItemWiseDiscout']) && $tempCart[$i]['BogoItemWiseDiscout'] > 0) {
						$BogoItemWiseDiscout = $tempCart[$i]['BogoItemWiseDiscout'];
					}
					if (!empty($tempCart[$i]['GiftCertificateItemWiseDiscout']) && $tempCart[$i]['GiftCertificateItemWiseDiscout'] > 0) {
						$GiftCertificateItemWiseDiscout = $tempCart[$i]['GiftCertificateItemWiseDiscout'];
					}
					/*if(!empty($tempCart[$i]['CreditLimitItemWiseDiscout']) && $tempCart[$i]['CreditLimitItemWiseDiscout'] > 0)
					{
						$CreditLimitItemWiseDiscout = $tempCart[$i]['CreditLimitItemWiseDiscout'];
					}*/

					$ActualPrice = $CouponDisItemWiseDiscout + $AutoItemWiseDiscout + $QuantityItemWiseDiscout + $RewardItemWiseDiscout + $BogoItemWiseDiscout + $GiftCertificateItemWiseDiscout + $CreditLimitItemWiseDiscout;

					if (!empty($ActualPrice) && $ActualPrice > 0) {
						$ActualPrice = $tempCart[$i]['TotPrice'] - $ActualPrice;
						$ActualPrice = NumberFormat($ActualPrice);
					} else {
						$ActualPrice = 0;
					}

					$ShoppingcartController = app()->make(ShoppingcartController::class);
					//$TotalItemTaxAmount = $tempCart[$i]['TotalItemTaxAmount']; //$ShoppingcartController->itemTaxAmount($ActualPrice,$tempCart[$i]['TotPrice']);
					$ItemWiseTaxVal = 0;
					if($TaxValueNew > 0)
					{
						$ItemWiseTaxVal = $ShoppingcartController->ItemWiseTax($tempCart[$i]['TotPrice'],$i);
					}

					if (!isset($tempCart[$i]['YotpoFreeGift']))
						$tempCart[$i]['YotpoFreeGift'] = '';

					$OrderDetailInsert = array(
						'orders_id'				=> $OrderID,
						'orders_no'				=> $Prefix . $OrderID, // To add 'OR' Change on :: 06-10-2015
						'products_id'			=> $tempCart[$i]['ProductID'],
						'sku' 					=> $tempCart[$i]['SKU'],
						'product_name'			=> $tempCart[$i]['ProductName'] . '<br>' . $tempCart[$i]['short_description'],
						'quantity' 				=> $tempCart[$i]['Qty'],
						'price' 				=> $tempCart[$i]['Price'],
						'total' 				=> $tempCart[$i]['TotPrice'],
						'status' 				=> '1',
						'item_price' 			=> (isset($tempCart[$i]['ItemPrice'])) ? $tempCart[$i]['ItemPrice'] : 0,
						'excluded_flag'  		=> (isset($tempCart[$i]['FinalSale'])) ? $tempCart[$i]['FinalSale'] : '',
						'is_gift_wrap'			=> (isset($tempCart[$i]['gift_wrap'])) ? $tempCart[$i]['gift_wrap'] : '',
						'is_free_gift_products' => (isset($tempCart[$i]['IS_Free_Gift'])) ? $tempCart[$i]['IS_Free_Gift'] : 'No',
						'VendorSKU'				=> $tempCart[$i]['VendorSKU'],
						'IsCosmo'				=> $tempCart[$i]['IsCosmo'],
						'IsNandansons'  		=> $tempCart[$i]['IsNandansons'],
						'IsPerfumePW'			=> $tempCart[$i]['IsPerfumePW'],
						'IsPCA'					=> $tempCart[$i]['IsPCA'],
						'IsND'					=> $tempCart[$i]['IsND'],
						'coupon_itemwise_discount' => $tempCart[$i]['ItemWiseCouponDiscount'],
						'handling_time_str'		=> (isset($tempCart[$i]['HandlingTimeStr'])) ? $tempCart[$i]['HandlingTimeStr'] : '',
						'attribute_info'        => (isset($tempCart[$i]['IsYotpoFreeProduct'])) ? $tempCart[$i]['IsYotpoFreeProduct'] : 'No',
						'actual_price'			=> $ActualPrice,
						'item_type'				=> $tempCart[$i]['OrderType'],
						'item_tax_amount'		=> $ItemWiseTaxVal //$TotalItemTaxAmount
					);
					$log['OrderDetailInsert'] = json_encode($OrderDetailInsert);
					addLog("PlaceOrder", $log);
					$OrdDetail = OrderDetail::create($OrderDetailInsert);
					$OrderDetailID = $OrdDetail->orders_detail_id;
					if (($tempCart[$i]['IsCosmo'] == "Yes" || $tempCart[$i]['IsNandansons'] == 'Yes' || $tempCart[$i]['IsPerfumePW'] == 'Yes' ||  $tempCart[$i]['IsND'] == 'Yes' || $tempCart[$i]['IsPCA'] == "Yes") && $tempCart[$i]['VendorSKU'] != '') {
						$IsVender = "Yes";
					}
					if ($tempCart[$i]['IsPerfumePW'] == 'Yes') {
						$IsPerfumePWVendor = "Yes";
					}

					$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem', $tempCart[$i]);

					if ($IsGiftCertificateItem == 'Yes') {
						$AddGC = $this->checkGiftCertificateItem('InsertGiftCertificateInDB', $tempCart[$i], 'Yes', $OrdDetail->orders_detail_id, $customer_id, $IsAmazOR);
					}
				}
			}

			$EstimateNewDate = '';

			$ShoppingcartController = app()->make(ShoppingcartController::class);
			if ($onlyGCPurchased == 0) {
				if ($request->has('ShipToStoreVal') && $request->input('ShipToStoreVal') == "Yes")
				{
					$EstimateNewDate = "";
				}
				else
				{
					$EstimateNewDate = $ShoppingcartController->SetEstimateDate($ShippingInfo['ShippingMethodID'], $IsVender, $IsPerfumePWVendor, $Shipping['zip'], $Shipping['state'], $Shipping['country']);
				}
				//echo $EstimateNewDate; exit;
			}

			if ($request->has('ShipToStoreVal') && $request->input('ShipToStoreVal') == "Yes")
			{
				$EstimateNewDate = "";
			}
			else
			{
				$cur_date = date("Y-m-d");
				if ($EstimateNewDate == "" && strtotime($EstimateNewDate) < strtotime($cur_date)) {
					$EstimateNewDate = "0000-00-00 00:00:00";
				}

				$updArayVal = array('EstimatedDeliveryDate' => $EstimateNewDate);
				$updOrder22 = $CurrOrder->update($updArayVal);
			}
			if ($OrderID != "" && $IsVender == "Yes") {
				// To add 'OR' Change on :: 06-10-2015
				$updateOrder1 = array('IsVender' => $IsVender);
				$udpRefer1 = $CurrOrder->update($updateOrder1);
			}

			$customerEmail = '';
			if (isset($Billing['email']) && $Billing['email'] != '') {
				//$customerEmail	= "naresh.qualdev@gmail.com";
				$customerEmail	= $Billing['email'];
			}

			$orderNo		= $Prefix . $OrderID;
			$customerName = '';
			if ((isset($Billing['first_name']) && $Billing['first_name'] != '')) {
				$customerName	= $Billing['first_name'] . " ";
			}

			if (isset($Billing['last_name']) && $Billing['last_name']) {
				$customerName = $customerName . $Billing['last_name'];
			}
			Log::info('Before Order Sales Person Entry 2054 '.$OrderID);
			//if (Auth::guard('store')->check() && $key[$d] == "Store") {
			if (Auth::guard('store')->check()) {

				$insertOrderStore = array(
					'orders_id'		=> $OrderID,
					'store_id'		=> Auth::guard('store')->user()->store_id,
					'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
					'added_datetime'	=> date("Y-m-d H:i:s")
				);
				$insertOSId = DB::table('pu_orders_store')->insertGetId($insertOrderStore);
				Log::info('After Order Store Insert - 2065 - '.json_encode($insertOrderStore));

				if (Session::has('ShoppingCart.SalesAgent')) {
					$orderSalesAgents = Session::get('ShoppingCart.SalesAgent');
					Log::info('After Order Store Insert - 2069 - '.json_encode($orderSalesAgents));
					for ($ms = 0; $ms < count($orderSalesAgents); $ms++) {
						$agent_email_id = $orderSalesAgents[$ms]['sales_person_email'];

						$salesPerson = StoreSalesPerson::where('email', $agent_email_id)->where('store_id', Auth::guard('store')->user()->store_id)->where('status', '=', '1')->first();

						if ($salesPerson) {
							$salesPersonId = $salesPerson->sales_person_id;
							$commisionper = ($orderSalesAgents[$ms]['sales_person_per']/2);

							$insertOrderSalesPerson = array(
								'orders_id'		=> $OrderID,
								'store_id'		=> Auth::guard('store')->user()->store_id,
								'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
								'sales_person_id'	=> $salesPersonId,
								'email' => $agent_email_id,
								'added_datetime'	=> date("Y-m-d H:i:s"),
								'commision_percentage'	=> $commisionper// $orderSalesAgents[$ms]['sales_person_per']
							);
							$insertOSPId = DB::table('pu_orders_sales_person')->insertGetId($insertOrderSalesPerson);
							Log::info('After Order Store Insert - 2088 - '.json_encode($insertOrderSalesPerson));
						}
					}
				}
			} else {
				Log::info('Order Sales Person Entry auth problem 2092 '.$OrderID);
			}

			$gc_remaining_value = 0;
			$GiftCouponInfo = $this->GetSplitAllCoupons('GiftCoupon', $key[$d]);

			if ($GiftCouponInfo && count($GiftCouponInfo) > 0 && isset($GiftCouponInfo["Code"]) && $GiftCouponInfo["Code"] != '') {

				$GiftCouponInfo['Value'] = ($GiftCouponInfo['Value'] != '') ? $GiftCouponInfo['Value'] : 0.00;
				$new_total = $this->GetNetTotal() + $GiftCouponInfo['Value'];
				if ($new_total <= $GiftCouponInfo['Applicable_Value']) {
					$gc_remaining_value = NumberFormat(($GiftCouponInfo['Applicable_Value'] - $new_total));
				}

				if ($GiftCouponInfo['Code'] != '' && $new_total <= $GiftCouponInfo['Applicable_Value']) {
					$str_info  = 'Gift Certificate discount value is greater than order total amount. \n\n';
					$str_info .= 'So net $' . $new_total . ' is deduct from gift certifiacte value. \n\n';
					$str_info .= 'Used Gift Certificate code is (' . $GiftCouponInfo['Code'] . ')';

					$updAray = array('pay_status' 	   => 'Paid', 'transaction_info' => $str_info);

					Order::where('orders_id', '=', Session::get('ShoppingCart' . $key[$d] . 'OrderID'))->update($updAray);
					if ($d == 1) {
						return redirect(config('global.SITE_URL') . "order-receipts");
					}
				}
			} else if ($this->GetNetTotal() == 0) {
				$updAray = array('pay_status' => 'Paid');
				Order::where('orders_id', '=', Session::get('ShoppingCart' . $key[$d] . 'OrderID'))->update($updAray);
				if ($d == 1) {
					//return redirect(config('global.SITE_URL')."order-receipts");
				}
			}
		}

		return true;
		//return redirect(config('global.SITE_URL')."order-receipts");
		//exit;

	}

	public function OrderReceipts(Request $request)
	{

		$OrderStr = "";
		$setOrderStr = "";

		if (Session::has('ShoppingCart.StoreOrderID') && Session::get('ShoppingCart.StoreOrderID') != '' && !empty(Session::get('ShoppingCart.StoreOrderID')) && Session::has('ShoppingCart.WebsiteOrderID') && Session::get('ShoppingCart.WebsiteOrderID') != '' && !empty(Session::get('ShoppingCart.WebsiteOrderID'))) {
			$OrderStr = Session::get('ShoppingCart.StoreOrderID') . "," . Session::get('ShoppingCart.WebsiteOrderID');
		} else {
			return redirect("/");
		}

		$this->PageData['CSSFILES'] = ['shoppingcart.css', 'checkout.css', 'pos-style.css'];
		//$this->PageData['JSFILES'] = ['billing.js','login.js','login_validate.js'];
		$this->PageData['JSFILES'] = ['bootstrap.min.js', 'jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js', 'order_receipt.js'];
		$wholesale_terms = "";
		$chkPaymentType = "";
		$chkPaidStatus = "";

		$IsGiftCertificateItem = $IsGiftCertificateItem1 = '';
		$hasCashPayment = 0;

		$order_receipt_image_path = config('global.SITE_URL') . 'images/';
		$order_receipt_image = generalsetting('ORDER_RECEIPT_IMAGE', 1);
		$order_receipt_image = $order_receipt_image_path . $order_receipt_image . '?ver=' . time();
		$this->PageData['order_receipt_image'] = $order_receipt_image;

		if (Session::get('eusertype') == 'Wholesaler')
			$wholesale_terms = "<br><strong style='font-size:14px'>Read our MaxAroma Wholesale <a class='viewlink' data-target='#myModalPopUp' data-toggle='modal' href='javascript:void(0);' onclick='DisplayWholesalerTerms();' class='normallink'>Policy</a></strong>";
		$this->PageData['wholesale_terms'] = $wholesale_terms;

		$IS_WEB_SITE_ORDER 	= 'Yes';
		$IS_GOOGLE_ORDER 	= 'No';
		$IS_AMAZON_ORDER    = 'No';

		$Template = 'order-receipt';
		//dd(Session::get('ShoppingCart.Payment_Detail'));

		if (Session::has('ShoppingCart.Payment_Detail')) {

			$arrPaymentDetail11 = (Session::has('ShoppingCart.Payment_Detail')) ? Session::get('ShoppingCart.Payment_Detail') : array();
			if (isset($arrPaymentDetail11) && isset($arrPaymentDetail11["Payment_Type"])) {
				$arrPaymentDetail11["Payment_Type"] = $arrPaymentDetail11["Payment_Type"];
			} else {
				$arrPaymentDetail11["Payment_Type"] = '';
			}

			if (isset($arrPaymentDetail11) && isset($arrPaymentDetail11["Payment_Type"])) {
				if ($arrPaymentDetail11["Payment_Type"] == 'PAYMENT_CASH' || $arrPaymentDetail11["Payment_Type"] == 'PAYMENT_SPLIT') {
					$hasCashPayment = 1;
				}
			}
			$this->PageData['hasCashPayment'] = $hasCashPayment;
			$this->PageData['orderCashAmount'] = $arrPaymentDetail11["orderCashAmount"];

			//$arrPaymentDetail11 = Session::get('ShoppingCart.Payment_Detail');

			$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');

			$GAShipping = "";
			$GAPayment 	=  "";
			if ($arrPaymentDetail11["Payment_Type"] == "PAYMENT_CL") {

				if (isset($arrPaymentDetail11["Payment_Method"]) && $arrPaymentDetail11["Payment_Method"] != '') {
					$GAPayment 	= googleAnalyticsGA4("PaymentMethods", Session::get('ShoppingCart.Cart'), $this->GetNetTotal(), $this->GetAllCoupons('CouponCode'), '', $arrPaymentDetail11["Payment_Method"]);
				}

				$this->PageData['GAShipping']  = $GAShipping;
				$this->PageData['GAPayment']  = $GAPayment;
			}
		}

		$topmenubar = '<a class="nav-one-third" style="color:#fff; text-decoration:none;" href="' . config('global.SITE_URL') . 'fragrances/cid/1" target="_blank">Fragrances</a>
						   <span class="hide">&nbsp;&nbsp; &nbsp;&nbsp;</span>
						   <a class="nav-one-third" style="color:#fff; text-decoration:none;" href="' . config('global.SITE_URL') . 'skincare/cid/18" target="_blank">Skincare</a>
						   <span class="hide">&nbsp;&nbsp; &nbsp;&nbsp;</span>
						   <a class="nav-one-third" style="color:#fff; text-decoration:none;" href="' . config('global.SITE_URL') . 'pocket-perfume/cid/68" target="_blank">Pocket Perfume</a>
						   <span class="hide">&nbsp;&nbsp; &nbsp;&nbsp;</span>
						   <a class="nav-one-third" style="color:#fff; text-decoration:none;" href="' . config('global.SITE_URL') . 'bath-body/cid/12" target="_blank">Bath &amp; Body</a>
						   <span class="hide">&nbsp;&nbsp; &nbsp;&nbsp;</span>
						   <a class="nav-one-third" style="color:#fff; text-decoration:none;" href="' . config('global.SITE_URL') . 'candles/cid/208" target="_blank">Candles</a>
						   <span class="hide">&nbsp;&nbsp; &nbsp;&nbsp;</span>
						   <a class="nav-one-third" style="color:#ff0000; text-decoration:none;" href="' . config('global.SITE_URL') . 'offers.html" target="_blank">SALES & OFFERS</a>
						   ';

		$CustomerID = Session::get('sess_icustomerid');
		//echo $OrderStr; exit;

		$OrderStr = explode(',', $OrderStr);

		$OrderRs = Order::whereIn('orders_id', $OrderStr)->get();

		$setOrderTot = 0;
		
		$dataBeacon = array();
		
		for ($r = 0; $r < count($OrderRs); $r++) {
			$OrderID = $OrderRs[$r]['orders_id'];

			if (isset($OrderRs[$r]["Is_GiftCertificatPurchase"]) && $OrderRs[$r]["Is_GiftCertificatPurchase"] == '1') {
				$mailTemplate = GetMailTemplate("GC_SEND_CODE");
			}

			$OrderDetailRs = OrderDetail::where('orders_id', '=', $OrderRs[$r]['orders_id'])->orderBy('orders_detail_id')->get();

			$GOOGLE_ORDER_TRACKING = '';
			$GOOGLE_ORDER_TRACKING_GTM = '';
			$gtm_purchase_prod_str = "";

			$RIO_EBAY_COMMERCE_CODE  = '';
			$RIO_EBAY_COMMERCE_CODE  .= "var _roi = _roi || [];
        _roi.push(['_setMerchantId', '532042']);
        _roi.push(['_setOrderId', '" . $OrderRs[$r]['orders_no'] . "']);
        _roi.push(['_setOrderAmount', '" . $OrderRs[$r]['sub_total'] . "']);
        _roi.push(['_setOrderNotes',  '" . $OrderRs[$r]['customer_comment'] . "']);";

			$trusted_code = '';
			$Bizrate_POS_Code = "var orderId  ='" . $OrderRs[$r]["orders_no"] . "';
                             var cartTotal='" . $OrderRs[$r]['order_total'] . "';
                             var billingZipCode='" . $OrderRs[$r]["bill_zip"] . "'; ";

			$Bizrate_POS_Code_NEW = "";
			$Bizrate_POS_Code_NEW .= "var productsPurchased='";

			$freeshippinginfo = '';
			if (config('Settings.FREESHIPPING_VALUE') != "" && config('Settings.FREESHIPPING_VALUE') > 0) {
				$freeshippinginfo .= '<p class="siteoffer" style="margin:0;padding:5px 0px; font-size:16px; font-weight:normal; font-family:Arial,  sans-serif; color:#666666;"><strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders</p>';
			}

			$critieostr = '';
			$ProdDetails = [];
			$roktstr = '';
			$couponCodeNo = "";
			$GA4_purchase_prod_str = "";
			$FBContent_arr =  array();
			if ($OrderRs[$r]['coupon_code'] != '') {
				$couponCodeNo .= "coupon: '" . $OrderRs[$r]['coupon_code'] . "',";
			}

			$Content_id_str = "";
			$Content_id_Brcket_str = "";

			if (isset($OrderRs[$r]['order_type']) && $OrderRs[$r]['order_type'] == "Store") {
				//$this->skuvault($OrderRs[$r]->toArray(),$OrderDetailRs->toArray());
			}

			$setOrderTot = $setOrderTot + $OrderRs[$r]['order_total'];

			if ($hasCashPayment == 1 && $r > 0) {

				//$setOrderTot = $setOrderTot + $this->GetNetTotal();

				$newrequest = new \Illuminate\Http\Request();
				$newrequest->setMethod('POST');

				$newrequest11 = new \Illuminate\Http\Request();
				$newrequest11->setMethod('POST');

				$setOrderStr = GetStorePrefix() . Session::get('ShoppingCart.StoreOrderID') . "(Store), OR" . Session::get('ShoppingCart.WebsiteOrderID') . "(Online)";

				$getDailyOpenForDay = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', Auth::guard('store')->user()->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->orderBy('added_datetime', 'desc')->first();

				$action_notes = array(
					config('global.STORE_LOGS_ORDER_ID') => ($setOrderStr ?? ''),
					config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $getDailyOpenForDay->current_balance,
					config('global.STORE_LOGS_ORDER_AMOUNT') => ($setOrderTot ?? 0.00),
					config('global.STORE_LOGS_CD_AMOUNT') => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0),
					config('global.STORE_LOGS_UPDATED_AMOUNT') => ((float)$getDailyOpenForDay->current_balance + (float)(Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0)),
					config('global.STORE_LOGS_ADJUSTMENT_TYPE') => 'increase',
				);

				//$newrequest->request->add(['action' => 'Order', 'action_type' => 'Open', 'actionNotes' => '{"orderId":' . ($OrderRs[$r]['orders_id'] ?? 0) . ', "orderAmount":' . ($OrderRs[$r]['order_total'] ?? 0.00) . ', "cd_amount":' . (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0) . '}', 'cd_amount' => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0), 'is_device_event' => 0]);

				//$newrequest->request->add(['action' => 'Order', 'action_type' => 'Open', 'actionNotes' => '{"orderId":"' . ($setOrderStr ?? "") . '", "orderAmount":' . ($setOrderTot ?? 0.00) . ', "cd_amount":' . (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0) . '}', 'cd_amount' => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0), 'is_device_event' => 0]);
				$newrequest->request->add(['action' => 'Order', 'action_type' => 'Open', 'actionNotes' =>  json_encode($action_notes), 'cd_amount' => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0), 'is_device_event' => 0]);

				//$newrequest11->request->add(['action' => 'Order', 'action_type' => 'Close', 'actionNotes' => '{"orderId":"' . ($setOrderStr ?? "") . '", "orderAmount":' . ($setOrderTot ?? 0.00) . ', "cd_amount":' . (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0) . '}', 'cd_amount' => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0), 'is_device_event' => 0]);
				$newrequest11->request->add(['action' => 'Order', 'action_type' => 'Close', 'actionNotes' => json_encode($action_notes), 'cd_amount' => (Session::get('ShoppingCart.Payment_Detail.orderCashAmount') ?? 0), 'is_device_event' => 0]);
				//dd($newrequest);

				$this->cashDrawerLog($newrequest);
				$this->cashDrawerLog($newrequest11);
			}
			
			
			$resultProducts = array();
			
			for ($p = 0; $p < $OrderDetailRs->count(); $p++) {
				if ($OrderRs[$r]["payment_type"] != "PAYMENT_STRIPE" && ($OrderRs[$r]["pay_status"] == "Paid" || $OrderRs[$r]["payment_type"] == "PAYMENT_DS")) {
					$GiftCardRes = GiftCertificate::where('status', '=', '0')->where('orders_detail_id', '=', $OrderDetailRs[$p]["orders_detail_id"])->get();

					if ($GiftCardRes && $GiftCardRes->count() > 0) {
						$CurrentDate =  date("Y-m-d");

						if ($GiftCardRes[0]['deliverydate'] == $CurrentDate) {
							$updGiftAray = array('status' => '1');
							$uporderresgift = GiftCertificate::where('gc_id', '=', $GiftCardRes[0]['gc_id'])->update($updGiftAray);
						}
					}
				}
				$critieostr .=  '{ id: "' . $OrderDetailRs[$p]['sku'] . '", price: "' . $OrderDetailRs[$p]['price'] . '", quantity: "' . $OrderDetailRs[$p]['quantity'] . '" } ,';
				$roktstr .= "{sku:'" . $OrderDetailRs[$p]['sku'] . "',quantity:'" . $OrderDetailRs[$p]['quantity'] . "',productname:'" . addslashes($OrderDetailRs[$p]['product_name']) . "',price:'" . $OrderDetailRs[$p]['price'] . "',majorcat:'',minorcat:'',currency:'USD'},";
				$order_items_pixel[] = array(
					'id' => $OrderDetailRs[$p]["sku"],
					'quantity' => $OrderDetailRs[$p]["quantity"],
					'price' => $OrderDetailRs[$p]["price"]
				);
				
				/*Beacon Tracking(results object) Starts*/
				$resultProducts[] = [
					"parentId"  => "0",
					"uid" => "'".$OrderDetailRs[$p]['products_id']."'",
					"sku" => $OrderDetailRs[$p]['sku'],
					"qty" => $OrderDetailRs[$p]['quantity'],
					"price" => (float)number_format($OrderDetailRs[$p]['price'], 2, '.', '')
				];
				/*Beacon Tracking(results object) Ends*/
				
				$giftcertificateItem = "No";

				$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem', $OrderDetailRs[$p], 'No');

				if ($IsGiftCertificateItem == 'Yes') {
					$GC_IMAGE_URL = "";

					$GC_IMAGE_URL = $this->checkGiftCertificateItem('SetGiftCertificateImageURL', $OrderDetailRs[$p], 'No');

					$GCRs = GiftCertificate::where('orders_detail_id', '=', $OrderDetailRs[$p]['orders_detail_id'])->where('customer_id', '=', $CustomerID)->get();
					if ($GCRs && $GCRs->count($GCRs) > 0) {
						$OrderDetailRs[$p]['RecipientName']  = $GCRs[0]['recipient_name'];
						$OrderDetailRs[$p]['RecipientEmail'] = $GCRs[0]['recipient_email'];
						$OrderDetailRs[$p]['SenderName']  	 = $GCRs[0]['your_name'];
						$OrderDetailRs[$p]['SenderEmail'] 	 = $GCRs[0]['your_email'];
						$OrderDetailRs[$p]['Image']			 = $GC_IMAGE_URL;
						$OrderDetailRs[$p]['DeliveryDate']	 = $GCRs[0]['deliverydate'];
						$giftcertificateItem = "Yes";
						$GA4_purchase_prod_str .= "{
                                    item_id : '" . $OrderDetailRs[$p]["sku"] . "',
                                    item_name : '" . stripcslashes($OrderDetailRs[$p]["product_name"]) . "',
                                    affiliation : 'Google Merchandise Store',";
						$GA4_purchase_prod_str .= "
                                     index: " . $p . ",
                                     item_list_id: 'related_products',
									 item_list_name: 'Related Products',
									 price: " . $OrderDetailRs[$p]['price'] . ",
									 quantity: " . $OrderDetailRs[$p]["quantity"] . "
                                   },";
					}
				} else {
					$FreeGiftSku = '';
					if ($OrderDetailRs[$p]["is_free_gift_products"] == "Yes") {
						$FreeGiftSku = 	$OrderDetailRs[$p]['sku'];
						$OrderDetailRs[$p]['sku'] = str_replace("GIFT-", "", $OrderDetailRs[$p]['sku']);
					}
					$prod_res = DB::table('pu_products as p')
						->join('pu_products_category as pc', 'p.products_id', '=', 'pc.products_id')
						->select('p.products_id', 'p.UPC', 'p.image', 'p.product_name', 'p.vtype', 'p.short_description', 'p.product_description', 'p.sku', 'p.current_stock', 'p.cosmo_current_stock', 'p.nandansons_current_stock', 'p.perfumeworldwide_currentstock', 'p.perfumeworldwide_currentstock', 'p.pca_current_stock', 'p.pca_current_stock', 'p.nd_current_stock', 'pc.category_id')
						->where(DB::raw('LOWER(TRIM(sku))'), '=', strtolower(trim($OrderDetailRs[$p]['sku'])))
						->limit(1)->get();

					if ($IsGiftCertificateItem == 'Yes') {
						$thumb_image = $this->checkGiftCertificateItem('SetGiftCertificateImageURL', $OrderDetailRs[$p], 'No');
					} else {
						if (file_exists(config('global.PRD_LARGE_IMG_PATH') . $prod_res[0]->image) && !empty($prod_res[0]->image)) {
							$thumb_image = config('global.PRD_LARGE_IMG_URL') . $prod_res[0]->image;
						} else {
							$thumb_image = config('global.NO_IMAGE_LARGE');
						}
					}

					$OrderDetailRs[$p]['Image'] = $thumb_image;

					$vlink_name = SetProductURL($prod_res[0]->products_id, $prod_res[0]->product_name, $prod_res[0]->category_id);
					$OrderDetailRs[$p]['ProdLink'] = $vlink_name;
					if ($p < 5) {
						$Bizrate_POS_Code_NEW .= "" . $vlink_name . "=^" . addslashes($prod_res[0]->sku) . "=^" . addslashes($prod_res[0]->UPC) . "=^" . $OrderDetailRs[$p]['total'] . "=|";
					}
				}
				$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');

				if ($giftcertificateItem == "Yes" && $GCRs->count() > 0 && $OrderDetailRs[$p]['DeliveryDate'] == date("Y-m-d") && ($OrderRs[$r]["pay_status"] == "Paid" || $OrderRs[0]["payment_type"] == "PAYMENT_DS") && $OrderRs[$r]["payment_type"] != "PAYMENT_STRIPE") {

					$message_back = $mailTemplate[0]['mail_body'];
					$subject_back = $mailTemplate[0]['subject'];
					$subject_back = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $subject_back);
					$subject_back = str_replace('{$recipient_name}', $GCRs[0]['recipient_name'], $subject_back);
					$subject_back = str_replace('{$sender_name}', $GCRs[0]['your_name'], $subject_back);

					$this->PageData["CONTACT_MAIL"] = config('Settings.CONTACT_MAIL');
					$this->PageData["topmenubar"] = $topmenubar;
					$this->PageData["freeshippinginfo"] = $freeshippinginfo;
					$this->PageData["recipient_name"] = $GCRs[0]['recipient_name'];
					$this->PageData["sender_name"] = $GCRs[0]['your_name'];
					$this->PageData["SITE_NAME"] = config('global.SITE_TITLE');
					$this->PageData["Site_URL"] = config('global.SITE_URL');
					$this->PageData["remaining_value"] = $GCRs[0]["remaining_value"];
					$this->PageData["gc_code"] = $GCRs[0]["gc_code"];
					$this->PageData["freeshippinginfo"] = $freeshippinginfo;
					$this->PageData["message"] = $GCRs[0]["messae"];
					$this->PageData["TOLL_FREE_NO"] = config('global.TOLL_FREE_NO');
					$this->PageData["GiftCard"] = $GCRs[0]['giftimage'];

					$GCRs[0]["gc_card"] = $GC_IMAGE_URL;
					$this->PageData["gc_card"] = $GCRs[0]["gc_card"];

					$message_back = str_replace('{$freeshippinginfo}', $freeshippinginfo, $message_back);
					$message_back = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $message_back);
					$message_back = str_replace('{$recipient_name}', $GCRs[0]['recipient_name'], $message_back);
					$message_back = str_replace('{$remaining_value}', $GCRs[0]['remaining_value'], $message_back);
					$message_back = str_replace('{$sender_name}', $GCRs[0]['your_name'], $message_back);
					$message_back = str_replace('{$gc_code}', $GCRs[0]["gc_code"], $message_back);
					$message_back = str_replace('{$gc_card}', $GCRs[0]["gc_card"], $message_back);
					if (isset($GiftCouponInfo['Value']))
						$message_back = str_replace('{$gc_amount}', $GiftCouponInfo['Value'], $message_back);
					$message_back = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_PHONE_NO'), $message_back);

					$vtoemail   = $GCRs[0]["recipient_email"];
					$vfromemail = $GCRs[0]["your_email"];

					if (config('global.OMNISEND_PROG') == false) {
						SendMail($subject_back, $message_back, $vtoemail, $vfromemail);
					} else {
						/** OMANISEND **/
						//OmanisendRequest('6209ffc44fa101001e950228',$GCRs[0]);
						OmanisendRequest('New_Gift_Cards_Email', $GCRs[0]);
						/** OMANISEND **/
					}

					//$cust_res 	= Customer::where('customer_id','=',Session::get('sess_icustomerid'))->get();
					$cust_res 	= Customer::where('customer_id', '=', $CustomerID)->get();

					$reward_point = 0;
					if (config('global.YOTPO_PROG') == false) {
						$reward_point = $cust_res[0]['iRewardpoint'] + 100;
					}
					$giftdata = array();
					$giftdata['is_email'] = "Yes";
					GiftCertificate::where("gc_id", "=", $GCRs[0]['gc_id'])->update($giftdata);
				}
				//========GOOGLE ITEM TRACK CODE For Google Trusted on 20June2014=======
				if ($OrderDetailRs[$p]["products_id"] > 0) {
					$iproductid = $OrderDetailRs[$p]["products_id"];
					$prores = Products::select('our_price', 'products_id', 'product_name', 'short_description', 'sale_price', 'sku', 'product_description', 'vtype', 'gender', 'UPC', 'imanufactureid')
						->where('products_id', '=', $iproductid)->get();

					$g_unit_price = $OrderDetailRs[$p]["price"] / $OrderDetailRs[$p]["quantity"];

					$trusted_code .= '{"gtin":"' . $prores[0]["UPC"] . '"},';
					//========GOOGLE ITEM TRACK CODE For Google Trusted on 20June2014=======

					//=======GOOGLE ITEM TRACK CODE START HERE===========//
					$fetch_brand = DB::table('pu_manufacture')
						->where('imanufactureid', '=', $prores[0]["imanufactureid"])->get();

					$fetch_category = array();
					$gcat = '';
					$fetch_category = DB::table('pu_category as c')
						->join('pu_products_category as pc', 'c.category_id', '=', 'pc.category_id')
						->join('pu_products as p', 'pc.products_id', '=', 'p.products_id')
						->where('p.products_id', '=', $OrderDetailRs[$p]['products_id'])->get();
					$StrCatValue = "";
					if ($fetch_category && $fetch_category->count() > 0) {
						$gcat = stripcslashes($fetch_category[0]->category_name);
						$CatInfo = config('CATEGORY_INFO');
						$breadcrumbs = $CatInfo['CatForProd'][$fetch_category[0]->category_id]['subcatbredcrum'];
						$CategoryArray = explode("-", $breadcrumbs);

						if (count($CategoryArray) > 0) {
							$q = 2;
							for ($d = 0; $d < count($CategoryArray); $d++) {
								if ($d == 0) {
									$StrCatValue .= "item_category :'" . $CategoryArray[$d] . "',";
								} else {
									$StrCatValue .= "item_category" . $q . " :'" . $CategoryArray[$d] . "',";
								}
							}
						}
						$gcatid = $fetch_category[0]->category_id;
					}
					$RIO_EBAY_COMMERCE_CODE  .= "_roi.push(['_addItem',
                                                    '" . $prores[0]["sku"] . "',
                                                    '" . $prores[0]["product_name"] . "',
                                                    '" . $gcatid . "',
                                                    '" . $gcat . "',
                                                    '" . $OrderDetailRs[$p]["price"] . "',
                                                    '" . $OrderDetailRs[$p]["quantity"] . "'
                                                    ]);";
					// Enanced Google Purchase tracking						* 				*/
					$gtm_purchase_prod_str .= "{
                                    'id': '" . $prores[0]["sku"] . "',
                                    'name': '" . $prores[0]["product_name"] . "',
                                    'category': '" . $gcat . "',
                                    'price': '" . $OrderDetailRs[$p]['price'] . "',
                                    'quantity': " . $OrderDetailRs[$p]['quantity'] . "
                                   },";

					$GA4_purchase_prod_str .= "{
                                    item_id : '" . $prores[0]["sku"] . "',
                                    item_name : '" . $prores[0]["product_name"] . "',
                                    affiliation : 'Maxaroma',";
					$GA4_purchase_prod_str .= "
                                     index: " . $p . ",
                                     item_brand: '" . $fetch_brand[0]->vmanufacture . "',
                                     " . $StrCatValue . "
                                     item_list_id: 'related_products',
									 item_list_name: 'Related Products',
									 price: " . $OrderDetailRs[$p]['price'] . ",
									 quantity: " . $OrderDetailRs[$p]["quantity"] . "
                                   },";

					$GOOGLE_ORDER_TRACKING .= "ga('ec:addProduct', {
                                         id: '" . $prores[0]["sku"] . "',         // Product SKU
                                        name: '" . $prores[0]["product_name"] . "',   // Product Name*
                                    category: '" . $gcat . "',      // Product Category
                                       price: '" . $OrderDetailRs[$p]['price'] . "',              // Price
                                    quantity: '" . $OrderDetailRs[$p]['quantity'] . "'                   // Quantity
                                });";

					//=======GOOGLE ITEM TRACK CODE END HERE===========//

					if ($OrderDetailRs->count() > 1) {
						$Content_id_str .= "'" . $OrderDetailRs[$p]["sku"] . "',";
						$FBContent_arr[$p]["id"] = $OrderDetailRs[$p]["sku"];
						$FBContent_arr[$p]["quantity"] = $OrderDetailRs[$p]['quantity'];
					} else {
						$Content_id_str .= "'" . $OrderDetailRs[$p]["sku"] . "'";
						$FBContent_arr["id"] = $OrderDetailRs[$p]["sku"];
						$FBContent_arr["quantity"] = $OrderDetailRs[$p]['quantity'];
					}
				}

				$ProdDetails[] = $OrderDetailRs[$p];
			}

			if (isset($Content_id_str) && $Content_id_str != '') {
				if ($OrderDetailRs->count() > 1) {
					$Content_id_str = substr($Content_id_str, 0, -1);
				}
				$Content_id_Brcket_str = "[" . $Content_id_str . "]";
			}

			if ($critieostr != '') {
				$critieostr = substr($critieostr, 0, -1);
			}
			$this->PageData["critieostr"] = $critieostr;

			if ($roktstr != '') {
				$roktstr = substr($roktstr, 0, -1);
			}

			$this->PageData["roktstr"] = $roktstr;

			if (Cookie::has("PEPPERJAM") && Cookie::get("PEPPERJAM") == "YES" && Session::get('eusertype') != "Wholesaler") {
				$customArr = Order::where('customer_id', '=', $OrderRs[$r]["customer_id"])->get();
				$new_to_file = 1;
				if ($customArr->count() > 1) {
					$new_to_file = 0;
				}

				$order_id = $OrderRs[$r]["orders_no"];
				$integration = 'DYNAMIC';
				$program_id = '8716';
				$coupons = $OrderRs[$r]["coupon_code"];
				$couponsArr = explode(":", $coupons);
				$TotalCouponsArr = count($couponsArr);
				if (count($couponsArr) > 0) {
					if ($TotalCouponsArr == 2) {
						$OtherCouponArr1 = explode("#", $couponsArr[0]);
						$OtherCouponArr2 = explode("#", $couponsArr[1]);
						$coupons = $OtherCouponArr1[0] . ',' . $OtherCouponArr2[0];
					} else {
						$coupons = $OrderRs[$r]["coupon_code"];
					}
				}
				$pixel_html = '';

				if (count($order_items_pixel) > 0) {
					$AllDiscounts = $this->$this->GetSplitAllDiscounts('', array(), $OrderRs[$r]["order_type"]);
					$TotalDiscount = $AllDiscounts['TotalDiscount'];
					$SubTotal = NumberFormat(Session::get('ShoppingCart.' . $OrderRs[$r]["order_type"] . 'SubTotal'));
					$affiliateDiscount = 0;
					if ($TotalDiscount > 0) {
						$affiliateDiscount = 1 - ($TotalDiscount / $SubTotal);
						$affiliateDiscount =  number_format($affiliateDiscount, 2, '.', '');
					}
					$x = 1;
					foreach ($order_items_pixel as $order_item) {
						if ($affiliateDiscount > 0 && $affiliateDiscount != '') {
							$affiliateItemAmount = $order_item['price'] * $affiliateDiscount;
							$affiliateItemAmount =  number_format($affiliateItemAmount, 2, '.', '');
						} else {
							$affiliateItemAmount = $order_item['price'];
						}

						$pixel_html .=
							'&' . 'ITEM_ID' . $x . '=' . $order_item['id'] .
							'&' . 'ITEM_PRICE' . $x . '=' . $affiliateItemAmount .
							'&' . 'QUANTITY' . $x . '=' . $order_item['quantity'];
						$x++;
					}
					if ($pixel_html != '' && $coupons != '') {
						$pixel_html = '<iframe src="https://t.pepperjamnetwork.com/track?' . 'INT=' . $integration . '&PROGRAM_ID=' . $program_id . '&ORDER_ID=' . $order_id . '&COUPON=' . $coupons . '&NEW_TO_FILE=' . $new_to_file . $pixel_html . '" width="1" height="1" frameborder="0"></iframe>';
					} else {
						$pixel_html = '<iframe src="https://t.pepperjamnetwork.com/track?' . 'INT=' . $integration . '&PROGRAM_ID=' . $program_id . '&ORDER_ID=' . $order_id . '&NEW_TO_FILE=' . $new_to_file . $pixel_html . '" width="1" height="1" frameborder="0"></iframe>';
					}
					$updateOrdernewArr = array('is_pepperjam'	 => "Yes");
					//Order::where('orders_id','=',$OrderID)->update($updateOrdernewArr);
					$this->PageData["pixel_html"] = $pixel_html;
				}
			}

			$FBContent_str = '';
			if (is_array($FBContent_arr)) {
				$FBContent_str = json_encode($FBContent_arr);
			}

			// Enanced Google Purchase tracking
			$GOOGLE_ORDER_TRACKING .= "ga('ec:setAction', 'purchase', {
                                    id: '" . $OrderRs[$r]['orders_id'] . "',     		// Transaction ID*
                                    affiliation: 'MaxAroma', 						// Store Name
                                    revenue: '" . $OrderRs[$r]['order_total'] . "',    // Total
                                    tax: '" . $OrderRs[$r]['tax'] . "',         		// Tax
                                    shipping: '" . $OrderRs[$r]['shipping_amt'] . "' // Shipping
                                });";

			$GOOGLE_ORDER_TRACKING_GTM .= "dataLayer.push(
                                         {
                                          'ecommerce': {
                                            'purchase': {
                                              'actionField': {
                                                'id': '" . $OrderRs[$r]['orders_id'] . "',
                                                'affiliation': 'MaxAroma',
                                                'revenue': '" . $OrderRs[$r]['order_total'] . "',
                                                'tax':'" . $OrderRs[$r]['tax'] . "',
                                                'shipping': '" . $OrderRs[$r]['shipping_amt'] . "'
                                              },
                                              'products': [" . rtrim($gtm_purchase_prod_str, ',') . "]
                                            }
                                          }
                                        });";
			$CurrencySymbol = 'USD';
			if (Session::has('currency_code') && Session::get('currency_code') != '')
				$CurrencySymbol = Session::get('currency_code');
			$bill_phone = "";
			if (isset($OrderRs[$r]['bill_phone']) && $OrderRs[$r]['bill_phone'] != '') {
				$bill_phone = $OrderRs[$r]['bill_phone'];
			}
			$RequestURLGTM = "";
			if (Session::has('RequestURLGTM') && Session::get('RequestURLGTM') != '') {
				$RequestURLGTM = Session::get('RequestURLGTM');
			}

			$GA4_purchase_prod_str = substr($GA4_purchase_prod_str, 0, -1);
			$GA4 = "";
			$GA4 .= "dataLayer.push({ ecommerce: null });";
			$GA4 .= "dataLayer.push({
								  event: 'purchase',
								  page_location : '" . $RequestURLGTM . "',
								  user_data :{
										address :
											{
												first_name : '" . $OrderRs[$r]['bill_first_name'] . "',
												last_name : '" . $OrderRs[$r]['bill_last_name'] . "',
												email_address: '" . $OrderRs[$r]['bill_email'] . "',
												phone_number : '" . $bill_phone . "',
											}
									},
								  contents : " . $FBContent_str . ",
								  content_ids : " . $Content_id_Brcket_str . ",
								  content_type: 'product',
								  ecommerce:{
								  transaction_id: '" . $OrderRs[$r]['orders_no'] . "',
								  value: '" . $OrderRs[$r]['order_total'] . "',
								  tax: " . $OrderRs[$r]['tax'] . ",
								  shipping: " . $OrderRs[$r]['shipping_amt'] . ",
								  currency: '" . $CurrencySymbol . "',
								  " . $couponCodeNo . "
								  items: [
											" . $GA4_purchase_prod_str . "
										 ]
										}
									});
								";

			$this->PageData[$OrderRs[$r]['order_type'] . 'GA4'] = $GA4;

			$Bizrate_POS_Code_NEW = substr($Bizrate_POS_Code_NEW, 0, -1);
			$Bizrate_POS_Code_NEW .= "'";
			$Bizrate_POS_Code = $Bizrate_POS_Code . $Bizrate_POS_Code_NEW;

			$this->PageData[$OrderRs[$r]['order_type'] . 'Bizrate_POS_Code'] = '<script type="text/javascript">' . $Bizrate_POS_Code . '</script>';
			$this->PageData[$OrderRs[$r]['order_type'] . 'trusted_code'] = trim($trusted_code, ",");

			$ShippingInsurance = $OrderRs[$r]['route_shipping_insurance_charge'];
			/*if($ShippingInsurance > 0){
            $this->RouteShippingInsuranceOrderProcess($OrderRs[0],$OrderDetailRs);

        }*/
			$chkPaymentType = $OrderRs[$r]['payment_type'];
			$chkPaidStatus = $OrderRs[$r]['pay_status'];
			#### Deduct product stock Start #####
			if ($OrderRs[$r]['pay_status'] == 'Paid') {
				for ($n = 0; $n < count($OrderDetailRs); $n++) {
					$this->ProductDeductStock($OrderDetailRs[$n]["sku"], $OrderDetailRs[$n]["quantity"], $OrderDetailRs[$n]["IsCosmo"], $OrderDetailRs[$n]["IsNandansons"], $OrderDetailRs[$n]["IsPerfumePW"], $OrderDetailRs[$n]["IsPCA"], $OrderDetailRs[$n]["IsND"], $OrderDetailRs[$n]["VendorSKU"], $OrderDetailRs[$n]["item_type"]);
				}
			} else if ($OrderRs[$r]['payment_type'] == "PAYMENT_STRIPE"  || $OrderRs[$r]['payment_type'] == "PAYMENT_WT" || $OrderRs[$r]['payment_type'] == "PAYMENT_DS" ||  $OrderRs[$r]['payment_type'] == "PAYMENT_CL" || $OrderRs[$r]['payment_type'] == "PAYMENT_GIFT_CERTIFICATE") {
				for ($n = 0; $n < count($OrderDetailRs); $n++) {
					$this->ProductDeductStock($OrderDetailRs[$n]["sku"], $OrderDetailRs[$n]["quantity"], $OrderDetailRs[$n]["IsCosmo"], $OrderDetailRs[$n]["IsNandansons"], $OrderDetailRs[$n]["IsPerfumePW"], $OrderDetailRs[$n]["IsPCA"], $OrderDetailRs[$n]["IsND"], $OrderDetailRs[$n]["VendorSKU"], $OrderDetailRs[$n]["item_type"]);
				}
			}
			#### Deduct product stock End #####

			### Code for payment message Start #######

			$PaymentRs = PaymentMethod::where('pm_group_name', '=', $OrderRs[$r]['payment_type'])->limit(1)->get();

			$Payment_Method_Message = '';
			if ($PaymentRs && $PaymentRs->count() > 0 && trim($PaymentRs[0]['pm_short_desc']) != '') {
				$Payment_Method_Message = stripslashes(trim($PaymentRs[0]['pm_short_desc']));
			}

			## Msg for status "Pending Review" of AuthNet Start ##
			if ($OrderRs[$r]['payment_gateway_response'] != '') {
				$arr_gateway_response = explode(",", $OrderRs[$r]['payment_gateway_response']);
				if ($arr_gateway_response[0] == 4) {
					$Payment_Method_Message = "<h5>Thank you! Your order will be processed pending a standard transaction review.</h5>
                <p>We hope you enjoyed shopping with us. Your order will be processed as soon as possible. We will contact you with updates. <br />Please allow 24hrs to process the payment. An E-mail Confirmation will be sent upon payment received.</p>";
				}
			}
			$this->PageData['Payment_Method_Message'] = str_replace('>rn<', '><', $Payment_Method_Message);

			$STR_EMAIL_ITEM = '';
			/*$STR_EMAIL_ITEM .= '<table cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr align="center" valign="top">
                    <td style="background-color:#e5e5e5; padding:5px;"><strong>Gift Wrap</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;"><strong>Images</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;" align="left"><strong>Your Order Summary</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;"><strong>Quantity</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;" align="right"><strong>Price</strong></td>
                </tr>';*/
			$STR_EMAIL_ITEM .= '<table cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr><td style="width:100%; overflow-x:auto;">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" style="border-collapse:collapse; table-layout:fixed; min-width:620px;">
                <colgroup>
                    <col style="width:75px;">
                    <col style="width:130px;">
                    <col style="width:260px;">
                    <col style="width:80px;">
                    <col style="width:75px;">
                </colgroup>
                <tr align="center" valign="top">
                    <td style="background-color:#e5e5e5; padding:5px;"><strong>Gift Wrap</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;"><strong>Images</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px;" align="left"><strong>Your Order Summary</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px; width:67px;"><strong>Quantity</strong></td>
                    <td style="background-color:#e5e5e5; padding:5px; width:75px;" align="right"><strong>Price</strong></td>
                </tr>';		
			$STR_EMAIL_ITEM_STORE = '<div style="padding:28px 20px;">';
        	$STR_EMAIL_ITEM_STORE .= '<div style="font-size:10px;color:#999;text-transform:uppercase;letter-spacing:2px;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #eee;">Your Selections</div>';
			$TotalProducts = 0;
			for ($n = 0; $n < count($OrderDetailRs); $n++) {
				$checked = $iimage = '';
				if ($OrderDetailRs[$n]['is_gift_wrap'] == 'Yes') {
					$checked = 'checked="checked" ';
				}

				$IsGiftCertificateItem1 = $this->checkGiftCertificateItem('IsGiftCertificateItem', $OrderDetailRs[$n], 'No');

				if ($IsGiftCertificateItem1 == 'Yes') {
					$iimage = $this->checkGiftCertificateItem('SetGiftCertificateImageURL', $OrderDetailRs[$n], 'No');
					$OrderDetailRs[$n]['Image'] = $iimage;
				} else {
					$OrderDetailRs[$n]['Image'] = $OrderDetailRs[$n]['Image'];
				}

				$productName_store = $OrderDetailRs[$n]['product_name'];
				$sku_store         = $OrderDetailRs[$n]['sku'];
				$price_store       = Price($OrderDetailRs[$n]['price']);
				$quantity_store    = $OrderDetailRs[$n]['quantity'];

				$STR_EMAIL_ITEM_STORE .= '<div style="padding:12px 0;border-bottom:1px solid #f5f5f3;">';

				$STR_EMAIL_ITEM_STORE .= '<table width="100%" cellpadding="0" cellspacing="0">';
				$STR_EMAIL_ITEM_STORE .= '<tr>';

				$STR_EMAIL_ITEM_STORE .= '<td style="font-size:14px;color:#1a1a2e;">';
				$STR_EMAIL_ITEM_STORE .= '<div style="font-weight:500;">'.$productName_store.'</div>';
				$STR_EMAIL_ITEM_STORE .= '<div style="font-size:12px;color:#999;margin-top:2px;">'.$sku_store.'</div>';
				$STR_EMAIL_ITEM_STORE .= '</td>';

				$STR_EMAIL_ITEM_STORE .= '<td align="right" style="min-width:80px;">';
				$STR_EMAIL_ITEM_STORE .= '<div style="font-size:14px;font-weight:600;color:#1a1a2e;">'.$price_store.'</div>';
				$STR_EMAIL_ITEM_STORE .= '<div style="font-size:12px;color:#999;">Qty: '.$quantity_store.'</div>';
				$STR_EMAIL_ITEM_STORE .= '</td>';

				$STR_EMAIL_ITEM_STORE .= '</tr>';
				$STR_EMAIL_ITEM_STORE .= '</table>';

				$STR_EMAIL_ITEM_STORE .= '</div>';

				//$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td valign="middle" style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;"><input type="checkbox"  disabled="disabled" ' . $checked . ' /></td><td style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;"><img src="' . $OrderDetailRs[$n]['Image'] . '" border="0" width="125" border="0" class="img-resp-75" /></td><td style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="left"><p style="color:#000; margin:0px;"><strong>' . $OrderDetailRs[$n]['product_name'] . '</strong></p><p>SKU: ' . $OrderDetailRs[$n]['sku'] . '</p>';

				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td valign="middle" style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;"><input type="checkbox"  disabled="disabled" '.$checked.' /></td><td style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;"><img src="'.$OrderDetailRs[$n]['Image'].'" border="0" width="125" border="0" class="img-resp-75" /></td><td style="padding:10px 8px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8; width:260px; word-break:break-word; overflow-wrap:break-word; white-space:normal;" align="left"><p style="color:#000; margin:0px;"><strong>'.$OrderDetailRs[$n]['product_name'].'</strong></p><p>SKU: '.$OrderDetailRs[$n]['sku'].'</p>';	

				//if($OrderDetailRs[$n]["sku"] == config('global.GIFT_CERTIFICATE_SKU') || $OrderDetailRs[$n]["sku"] == config('global.GIFT_CERTIFICATE_SKU1') || $OrderDetailRs[$n]["sku"] == config('global.GIFT_CERTIFICATE_SKU2'))
				if ($IsGiftCertificateItem1 == 'Yes') {
					$STR_EMAIL_ITEM .= '<p><strong>Sender Name : </strong> ' . $OrderDetailRs[$n]['SenderName'] . '</p>
                                   <p><strong>Sender Email : </strong> ' . $OrderDetailRs[$n]['SenderEmail'] . '</p>';
					$STR_EMAIL_ITEM .= '<p><strong>Recipient Name : </strong> ' . $OrderDetailRs[$n]['RecipientName'] . '</p>
                                   <p><strong>Recipient Email : </strong> ' . $OrderDetailRs[$n]['RecipientEmail'] . '</p>';
				}
				if ($OrderDetailRs[$n]["handling_time_str"] != '') {
					$STR_EMAIL_ITEM .= '<p>' . $OrderDetailRs[$n]['handling_time_str'] . '</p>';
				}
				$STR_EMAIL_ITEM .= '</td>';
				$STR_EMAIL_ITEM .= '<td style="padding:10px 5px; border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;"><strong>' . $OrderDetailRs[$n]['quantity'] . '</strong></td><td style="padding:10px 5px; border-bottom:1px solid #e8e8e8;" align="right"><strong>' . Price($OrderDetailRs[$n]['price']) . '</strong></td></tr>';

				$TotalProducts = (int)$TotalProducts + (int)$OrderDetailRs[$n]['quantity'];
			}
			$STR_EMAIL_ITEM_STORE .= '</div>';

			if (isset($OrderDetailRs[$n]['is_gift_wrap']) && $OrderDetailRs[$n]['is_gift_wrap'] == 'Yes') {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right"><strong>Gift Wrap:</strong></td><td align="left" style="padding:5px;border-bottom:1px solid #e8e8e8;">Yes</td></tr>';
			}

			$STR_EMAIL_ITEM .= '<tr align="center" valign="top">
            <td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right"><strong> Total item purchased:</strong></td>
            <td align="right" style="padding:5px;border-bottom:1px solid #e8e8e8;">' . $TotalProducts . '</td>
        </tr>';

			$STR_EMAIL_ITEM .= '<tr align="center" valign="top">
            <td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Subtotal:</td>
            <td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['sub_total']) . '</td>
        </tr>';

			if (!empty($OrderRs[$r]["shipping_amt"]) && $OrderRs[$r]["shipping_amt"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Shipping Charge:</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['shipping_amt']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["tax"]) && $OrderRs[$r]["tax"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Sales Tax:</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['tax']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["gift_charge"]) && $OrderRs[$r]["gift_charge"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Gift Wrap Charge :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['gift_charge']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["auto_discount"]) && $OrderRs[$r]["auto_discount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Auto Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['auto_discount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["quantity_discount"]) && $OrderRs[$r]["quantity_discount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Quantity Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['quantity_discount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["reward_discount"]) && $OrderRs[$r]["reward_discount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Yotpo Reward Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['reward_discount']) . '</td></tr>';
			}

			$shipping_signature = '';
			$shipping_sign = '';
			if (isset($OrderRs[$r]["is_shipping_signature"]) && $OrderRs[$r]["is_shipping_signature"] != '') {
				$shipping_signature = 'ON';
				$shipping_sign = 'Y';
				if ($OrderRs[$r]["is_shipping_signature"] == 'No') {
					$shipping_signature = 'OFF';
					$shipping_sign = 'N';
				}
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Shipping Signature (' . $shipping_signature . ') :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['shipping_signature']) . '</td></tr>';
			}

			$shipping_insurance = 'N';
			if (!empty($OrderRs[$r]["route_shipping_insurance_charge"]) && $OrderRs[$r]["route_shipping_insurance_charge"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Shipping Insurance* :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['route_shipping_insurance_charge']) . '</td></tr>';
				$shipping_insurance = 'Y';
			}

			if (!empty($OrderRs[$r]["coupon_amount"]) && $OrderRs[$r]["coupon_amount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Coupon Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['coupon_amount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["gc_amount"]) && $OrderRs[$r]["gc_amount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Gift Certificate Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['gc_amount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["bogo_discount"]) && $OrderRs[$r]["bogo_discount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Bogo Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['bogo_discount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["reward_discount"]) && $OrderRs[$r]["reward_discount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Reward Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['reward_discount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["refer_amount"]) && $OrderRs[$r]["refer_amount"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Refer Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['refer_amount']) . '</td></tr>';
			}

			if (!empty($OrderRs[$r]["apply_credit"]) && $OrderRs[$r]["apply_credit"] > 0) {
				$STR_EMAIL_ITEM .= '<tr align="center" valign="top"><td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right">Credit Discount :</td><td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right">' . Price($OrderRs[$r]['apply_credit']) . '</td></tr>';
			}

			$STR_EMAIL_ITEM .= '<tr align="center" valign="top">
            <td colspan="4" style="padding:5px;border-bottom:1px solid #e8e8e8; border-right:1px solid #e8e8e8;" align="right"><strong>Order Total:</strong></td>
            <td style="padding:5px;border-bottom:1px solid #e8e8e8;" align="right"><strong>' . Price($OrderRs[$r]['order_total']) . '</strong></td>
        </tr>';

			$STR_EMAIL_ITEM .= '</table>';

			if (isset($OrderRs[$r]["free_gift"]) && $OrderRs[$r]["free_gift"] != "") {
				$OrderRs[$r]["free_gift"] = "Got a Free Gift Of " . $OrderRs[$r]["free_gift"];
			}
			$OrdDate = date('Y-m-d h:i:s', $OrderRs[$r]["order_datetime"]);
			$estimated_ship_date = date('Y-m-d', strtotime($OrdDate . ' + 23 day'));
			$estimated_date = date('m-d-Y', strtotime($OrderRs[$r]["EstimatedDeliveryDate"]));
			$this->PageData[$OrderRs[$r]["order_type"] . "Estimated_Ship_Date"] = $estimated_ship_date;
			// $this->PageData["GOOGLE_ORDER_TRACKING"] = $GOOGLE_ORDER_TRACKING;
			$this->PageData[$OrderRs[$r]["order_type"] . "GOOGLE_ORDER_TRACKING"] = "";
			$mailTemplate = GetMailTemplate("ORDER_RECEIPT_NEW");
			$this->PageData["topmenubar"] = $topmenubar;
			$this->PageData["freeshippinginfo"] = $freeshippinginfo;

			$MailBanners = MailBanner::where('status', '=', '1');
			$Addblock = '';

			if ($MailBanners && $MailBanners->count() > 0) {
				$Addblock .= ' <td class="flex" valign="top" width="27%"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tbody>';
				foreach ($MailBanners as $MailBanner) {
					$banner_img = config('global.MAIL_BANNERS_URL') . $MailBanner->mail_banner_image . ".jpg";
					$banner_link = $MailBanner->mail_banner_link;
					$Addblock .= ' <tr class="halftd">
                                    <td style="padding:5px;border:1px solid #e8e8e8" align="center"><a href="' . $banner_link . '"  target="_blank"><img src="' . $banner_img . '" alt="" class="img-responsive"/></a>
                                </td></tr>';
				}
				$Addblock .= '</tbody></table></td>';
			}
			$this->PageData["Addblock"] = $Addblock;

			##Send Email TO Customer
			$to_email =  $OrderRs[0]["bill_email"];
			$bcc = 'b1ff7c3c82@invite.trustpilot.com';
			$ReceiptMailBody = $mailTemplate[0]['mail_body'];
			$ReceiptMailBody = str_replace('{$Site_URL}', config('global.SITE_URL'), $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$TOPMENUBAR}', $topmenubar, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$topmenubar}', $topmenubar, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$freeshippinginfo}', $freeshippinginfo, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$Addblock}', $Addblock, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$orders_no}', $OrderRs[$r]['orders_no'], $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$order_total}', Price($OrderRs[$r]['order_total']), $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$order_datetime}', date('M d, Y', $OrderRs[$r]['order_datetime']), $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$shipinfo}', $OrderRs[$r]['shipinfo'], $ReceiptMailBody);

			$BillAddress = $OrderRs[0]['bill_first_name'] . ' ' . $OrderRs[$r]['bill_last_name'] . "<br>";
			if ($OrderRs[$r]['bill_address2'] != '')
				$BillAddress .= $OrderRs[$r]['bill_address1'] . ', ' . $OrderRs[$r]['bill_address2'] . "<br>";
			else
				$BillAddress .= $OrderRs[$r]['bill_address1'] . ',<br>';
			$BillAddress .= $OrderRs[$r]['bill_city'] . ', ' . $OrderRs[$r]['bill_state'] . "<br>";
			$BillAddress .= $OrderRs[$r]['bill_zip'] . ' - ' . $OrderRs[$r]['bill_country'];

			$ReceiptMailBody = str_replace('{$bill_address}', $BillAddress, $ReceiptMailBody);

			$ShipAddress = $OrderRs[$r]['ship_first_name'] . ' ' . $OrderRs[$r]['ship_last_name'] . "<br>";
			if ($OrderRs[$r]['ship_address2'] != '')
				$ShipAddress .= $OrderRs[$r]['ship_address1'] . ', ' . $OrderRs[$r]['ship_address2'] . "<br>";
			else
				$ShipAddress .= $OrderRs[$r]['ship_address1'] . ',<br>';
			$ShipAddress .= $OrderRs[$r]['ship_city'] . ', ' . $OrderRs[$r]['ship_state'] . "<br>";
			$ShipAddress .= $OrderRs[$r]['ship_zip'] . ' - ' . $OrderRs[$r]['ship_country'];

			$ReceiptMailBody = str_replace('{$ship_address}', $ShipAddress, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$STR_EMAIL_ITEM}', $STR_EMAIL_ITEM, $ReceiptMailBody);
			$ReceiptMailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $ReceiptMailBody);

			$MailSubject = $mailTemplate[0]['subject'];
			$MailSubject = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $MailSubject);
			$MailSubject = str_replace('{$OrderRs.orders_no}', $OrderRs[$r]['orders_no'], $MailSubject);
			$customer_comment = (isset($OrderRs[$r]['customer_comment'])) ? $OrderRs[$r]['customer_comment'] : '';
			$to_email = "tempchecknew@gmail.com";
			if ($OrderRs[$r]["store_id"] > 0) {
				$estimated_date = "";
			}
			if (config('global.OMNISEND_PROG') == false) {
				SendMail($MailSubject, $ReceiptMailBody, config('Settings.ADMIN_MAIL'), config('Settings.CONTACT_MAIL'));
			} else {
				/** OMANISEND **/
				if ($chkPaymentType == "PAYMENT_WT" || $chkPaymentType == "PAYMENT_DS" ||  $chkPaymentType == "PAYMENT_CL" || $chkPaymentType == "PAYMENT_GIFT_CERTIFICATE") {
					if (isset($OrderRs[$r]["store_id"]) && $OrderRs[$r]["store_id"] > 0) {
						// $bill_phone = $OrderRs[0]["bill_phone"];
						// $OtherData = ['toMail' => $to_email, 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'estimated_ship_date' => $estimated_date, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign,'phone'=>$bill_phone];
						// OmanisendRequest('6942bff04b2a3e1daf65c63d', $OrderRs[$r], $OtherData);
						$storeData = DB::table('pu_stores as s')->where('s.store_id','=',$OrderRs[$r]['store_id'])->select('store_city','store_state')->first();
						$store_address = $storeData->store_city." ".$storeData->store_state;
						$bill_phone = $OrderRs[$r]["bill_phone"];
						$OtherData = ['toMail' => $to_email, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM_STORE,'phone'=>$bill_phone,'OrdDate'=>$OrdDate,'store_address'=>$store_address];
						OmanisendRequest('69f8b45dbe5da69308eeb38d',$OrderRs[0],$OtherData);
					} else {
						$OtherData = ['toMail' => $to_email, 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'estimated_ship_date' => $estimated_date, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign];
						OmanisendRequest('61fb93a4b86552001e976b3c', $OrderRs[$r], $OtherData);

						$OtherData = ['toMail' => 'orders@maxaroma.com', 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign];
						OmanisendRequest('61fb93a4b86552001e976b3c', $OrderRs[$r], $OtherData);
					}
				} else if ($chkPaidStatus == 'Paid' && $chkPaymentType != "PAYMENT_STRIPE") {

					if (isset($OrderRs[$r]["store_id"]) && $OrderRs[$r]["store_id"] > 0) {
						// $bill_phone = $OrderRs[0]["bill_phone"];
						// $OtherData = ['toMail' => $to_email, 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'estimated_ship_date' => $estimated_date, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign,'phone'=>$bill_phone];
						// OmanisendRequest('6942bff04b2a3e1daf65c63d', $OrderRs[$r], $OtherData);
						$storeData = DB::table('pu_stores as s')->where('s.store_id','=',$OrderRs[$r]['store_id'])->select('store_city','store_state')->first();
						$store_address = $storeData->store_city." ".$storeData->store_state;
						$bill_phone = $OrderRs[$r]["bill_phone"];
						$OtherData = ['toMail' => $to_email, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM_STORE,'phone'=>$bill_phone,'OrdDate'=>$OrdDate,'store_address'=>$store_address];
						OmanisendRequest('69f8b45dbe5da69308eeb38d',$OrderRs[0],$OtherData);
					} else {
						//$OtherData = ['toMail' => $to_email, 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'estimated_ship_date' => $estimated_date];
						$OtherData = ['toMail' => $to_email, 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'estimated_ship_date' => $estimated_date, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign];
						OmanisendRequest('61fb93a4b86552001e976b3c', $OrderRs[$r], $OtherData);

						//$OtherData = ['toMail' => config('Settings.ADMIN_MAIL'), 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment];
						$OtherData = ['toMail' => 'orders@maxaroma.com', 'addblock' => $Addblock, 'BillAddress' => $BillAddress, 'ShipAddress' => $ShipAddress, 'STR_EMAIL_ITEM' => $STR_EMAIL_ITEM, 'customernote' => $customer_comment, 'customer_ip' => $OrderRs[$r]["customer_ip"], 'shipping_insurance' => $shipping_insurance, 'shipping_signature' => $shipping_sign];
						OmanisendRequest('61fb93a4b86552001e976b3c', $OrderRs[$r], $OtherData);
					}
				}
			}

			Log::channel('order_receipt')->info('Order Receipt Email Sent for Order - ' . $OrderID);

			$this->PageData[$OrderRs[$r]["order_type"] . "GOOGLE_ORDER_TRACKING"] = "";
			$this->PageData[$OrderRs[$r]["order_type"] . "GOOGLE_ORDER_TRACKING_GTM"] = "";

			$RIO_EBAY_COMMERCE_CODE .= "_roi.push(['_trackTrans']);";
			$this->PageData['RIO_EBAY_COMMERCE_CODE'] = '<script type="text/javascript">' . $RIO_EBAY_COMMERCE_CODE . '</script>';

			$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
			if (config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && $CreditDiscount > 0 && Session::get('etype') == "M" && Session::get('is_dropshipper') != 'Yes' && Session::has('sess_icustomerid')) {
				$CustomerRemainingCreditAmount = Session::get('ShoppingCart.customer_remaining_credit_amount');
				$upgCustomer = array('credit_limit' => $CustomerRemainingCreditAmount);
				$udpCL = Customer::where('customer_id', '=', Session::get('sess_icustomerid'))->update($upgCustomer);

				$cpay_status = 'Paid';
				if ($OrderRs[0]['payment_type'] == 'PAYMENT_MOC' || $OrderRs[$r]['payment_type'] == 'PAYMENT_WT' || $OrderRs[$r]['payment_type'] == 'PAYMENT_PAYWITHAMAZON') {
					$cpay_status = 'Unpaid';
				}

				$upgOrder = array('pay_status' => $cpay_status);
				Order::where('orders_id', '=', $OrderRs[$r]['orders_id'])->update($upgOrder);

				$log_insert = array(
					'orderid' => $OrderRs[$r]['orders_id'],
					'custid' => Session::get('sess_icustomerid'),
					'current_credit_limit' => $OrderRs[$r]['cust_current_credit_limit'],
					'apply_credit' => $OrderRs[$r]['apply_credit'],
					'remaining_credit' => $OrderRs[$r]['remaining_credit']
				);
				CustomerCreditLimitLogs::create($log_insert);
				Session::put('ShoppingCart.' . $OrderRs[$r]["order_type"] . '.credit_limit_discount', 0.00);
				Session::put('ShoppingCart.' . $OrderRs[$r]["order_type"] . 'customer_remaining_credit_amount', 0.00);
			}

			if (Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') == 'Wholesaler') {
				$ds_res = Customer::where('customer_id', '=', Session::get('sess_icustomerid'))->get();

				$DropshipperAccountDetails = $this->GetDropshipperAccountDetails();
				if ($ds_res[0]['available_funds'] > 0 && $DropshipperAccountDetails['fund_available'] == 'Yes') {
					if ($OrderRs[0]['payment_type'] == 'PAYMENT_DS') {
						$remaining_fund = $DropshipperAccountDetails['remaining_fund'];
						$upgCustomer = array('available_funds' => $remaining_fund);
						$udpDS = Customer::where('customer_id', '=', Session::get('sess_icustomerid'))->update($upgCustomer);
					}
					$fpay_status = 'Paid';
					if ($OrderRs[$r]['payment_type'] == 'PAYMENT_MOC' || $OrderRs[$r]['payment_type'] == 'PAYMENT_WT' ||  $OrderRs[$r]['payment_type'] == 'PAYMENT_PAYWITHAMAZON') {
						$fpay_status = 'Unpaid';
					}
					$upgOrder2 = array('pay_status' => $fpay_status);
					Order::where('orders_id', '=', $OrderRs[$r]['orders_id'])->update($upgOrder2);
				}
			}

			$cnt_row = 0;
			$tempCart = array();

			$CartItem = Session::get('ShoppingCart.Cart');
			$groupedOrders = collect(Session::get('ShoppingCart.Cart'))->groupBy('OrderType')->toArray();
			$tempCart = array_values($groupedOrders[$OrderRs[$r]["order_type"]]);
			$cnt_row  = count($tempCart);
			$Rewardchk_arr = array();

			if ($cnt_row > 0) {
				$DealTotalprice = 0;
				for ($dl = 0; $dl < $cnt_row; $dl++) {
					$dealofdayRS = Dealofweek::where('status', '=', '1')
						->where('start_date', '<=', date('Y-m-d H:i'))->where('end_date', '>=', date('Y-m-d H:i'))
						->where('product_sku', '=', $tempCart[$dl]['SKU'])
						->limit(1)->get();

					if ($dealofdayRS && $dealofdayRS->count() > 0) {
						$DealTotalprice = $DealTotalprice + $tempCart[$dl]['TotPrice'];
					} else {
						$Rewardchk_arr[] = $tempCart[$dl]['SKU'];
					}
				}
			}

			//$cust_res1 	= Order::where('customer_id','=',Session::get('sess_icustomerid'))->get();
			$cust_res1 	= Order::where('customer_id', '=', $CustomerID)->get();

			if (Session::has('ShoppingCart.' . $OrderRs[$r]["order_type"] . 'Cart') && count(Session::get('ShoppingCart.' . $OrderRs[$r]["order_type"] . 'Cart')) > 0) {
				Shoppingcart::where('customer_id', '=', Session::get('sess_icustomerid'))->delete();
			}

			$GTMDATA = ['page' => 'order_receipt', 'pagetype' => 'purchase'];
			$this->PageData['GTMDATA'] = $this->SplitGoogleTagManager($GTMDATA, $OrderRs[$r]["order_type"], $tempCart);

			Log::channel('order_receipt')->info('Session data expired for Order - ' . $OrderID);

			$this->PageData[$OrderRs[$r]["order_type"] . 'MainOrder'] = $OrderRs[$r];
			$Charges = [];
			$Charges['shipping'] = ['field' => 'shipping_amt', 'label' => 'Shipping Charge'];
			$Charges['amazon_shipping'] = ['field' => 'OrderShippingCharge', 'label' => 'Shipping Charge'];
			$Charges['tax'] = ['field' => 'tax', 'label' => 'Sales Tax'];
			$Charges['amazon_tax'] = ['field' => 'OrderSalesTax', 'label' => 'Sales Tax'];
			$Charges['gift_charge'] = ['field' => 'gift_charge', 'label' => 'Gift Wrapping Charge'];
			$Charges['amazon_gift_charge'] = ['field' => 'GiftWrappingCharge', 'label' => 'Gift Wrapping Charge'];
			$Charges['shipping_insurance'] = ['field' => 'route_shipping_insurance_charge', 'label' => 'Shipping Insurance Charge'];
			$Charges['shipping_signature'] = ['field' => 'shipping_signature', 'label' => 'Shipping Signature'];

			$Discounts = [];
			$AutoDiscountLabel = (config('Settings.AUTO_DISCOUNT_FLAG') != '' ? config('Settings.AUTO_DISCOUNT_FLAG') : 'Auto Discount');
			$QuantityDiscountLabel = (config('Settings.QUANTITY_DISCOUNT_FLAG') != '' ? config('Settings.QUANTITY_DISCOUNT_FLAG') : 'Quantity Discount');
			$BogoDiscountLabel = (config('Settings.BOGO_DISCOUNT_FLAG') != '' ? config('Settings.BOGO_DISCOUNT_FLAG') : 'Bogo Discount');
			$Discounts['auto_discount'] = ['field' => 'auto_discount', 'label' => $AutoDiscountLabel];
			$Discounts['quantity_discount'] = ['field' => 'quantity_discount', 'label' => $QuantityDiscountLabel];
			$Discounts['coupon_amount'] = ['field' => 'coupon_amount', 'label' => 'Coupon Discount'];
			$Discounts['gc_amount'] = ['field' => 'gc_amount', 'label' => 'Gift Certificate Discount'];
			$Discounts['refer_amount'] = ['field' => 'refer_amount', 'label' => 'Refer Discount'];
			$Discounts['bogo_discount'] = ['field' => 'bogo_discount', 'label' => $BogoDiscountLabel];
			$Discounts['apply_credit'] = ['field' => 'apply_credit', 'label' => 'Credit Discount'];
			$Discounts['reward_discount'] = ['field' => 'reward_discount', 'label' => 'Yotpo Reward Discount'];

			$this->PageData[$OrderRs[$r]["order_type"] . 'AllCharges'] = $Charges;
			$this->PageData[$OrderRs[$r]["order_type"] . 'AllDiscounts'] = $Discounts;
			$this->PageData[$OrderRs[$r]["order_type"] . 'OrderDetails'] = $ProdDetails;
			
			/*Beacon Tracking Starts*/
			$dataBeacon = [
				"orderId" => $OrderRs[$r]['orders_no'],
				"transactionTotal" => (float)number_format($OrderRs[$r]['sub_total'], 2, '.', ''),
				"total" => (float)number_format($OrderRs[$r]['order_total'], 2, '.', ''),
				"vat" => (float)number_format($OrderRs[$r]['tax'], 2, '.', ''),
				"city" => $OrderRs[$r]['ship_city'],
				"country" => $OrderRs[$r]['ship_country'],
				"state" => $OrderRs[$r]['ship_state'],
				"results" => $resultProducts
			];
			$this->newBeaconTracking('order',$dataBeacon);
			/*Beacon Tracking Ends*/
			
		}

		$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
		if ($GiftCouponInfo && count($GiftCouponInfo) > 0 && $GiftCouponInfo['Code'] != '' && $GiftCouponInfo['Value'] > 0) {
			$gcRES = GiftCertificate::where('gc_code', '=', $GiftCouponInfo['Code'])->where('status', '=', '1')->get();
			if ($gcRES && $gcRES->count() > 0) {
				$gc_remaining_value = NumberFormat($GiftCouponInfo["Remaining_Value"]);

				if ($GiftCouponInfo['Code'] != '' && $GiftCouponInfo['Value'] > 0) {
					$upgGif = array(
						'remaining_value' => $gc_remaining_value,
						'last_used_date'  => date('Y-m-d H:i:s')
					);
					$udpGift = GiftCertificate::where('gc_code', '=', $GiftCouponInfo['Code'])->update($upgGif);
				}
				$freeshippinginfo = '';
				if (config('Settings.FREESHIPPING_VALUE') != "" && config('Settings.FREESHIPPING_VALUE') > 0) {
					$freeshippinginfo .= '<strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders';
				}
				$gcRESNew = GiftCertificate::where('gc_code', '=', $GiftCouponInfo['Code'])->where('status', '=', '1')->get();

				$res_mail = GetMailTemplate("GC_USAGE");
				$to_recipient = $gcRES[0]['recipient_email'];
				$GC_Subject = $res_mail[0]['subject'];
				$GC_Subject = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $GC_Subject);

				$GCMailBody = $res_mail[0]['mail_body'];
				$GCMailBody = str_replace('{$freeshippinginfo}', $freeshippinginfo, $GCMailBody);
				$GCMailBody = str_replace('{$recipient_name}', $gcRES[0]['recipient_name'], $GCMailBody);
				$GCMailBody = str_replace('{$gc_code}', $GiftCouponInfo['Code'], $GCMailBody);
				$GCMailBody = str_replace('{$gc_amount}', $GiftCouponInfo['Value'], $GCMailBody);
				$GCMailBody = str_replace('{$remaining_value}', $gcRESNew[0]['remaining_value'], $GCMailBody);
				$GCMailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.CONTACT_PHONE_NO'), $GCMailBody);
				$GCMailBody = str_replace('{$Site_URL}', config('global.SITE_URL'), $GCMailBody);
				$GCMailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $GCMailBody);
				$GCMailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $GCMailBody);

				$this->PageData["recipient_name"] = $gcRES[0]['recipient_name'];
				$this->PageData["gc_code"] = $GiftCouponInfo['Code'];
				$this->PageData["gc_amount"] = $GiftCouponInfo['Value'];
				$this->PageData["remaining_value"] = $gcRESNew[0]['remaining_value'];
				$this->PageData["TOLL_FREE_NO"] = config('global.CONTACT_PHONE_NO');
				$this->PageData['Site_URL'] = config('global.SITE_URL');
				$this->PageData["freeshippinginfo"] = $freeshippinginfo;
				if (config('global.OMNISEND_PROG') == false) {
					SendMail($GC_Subject,  $GCMailBody, $to_recipient, config('Settings.ADMIN_MAIL'));
				} else {
					/** OMANISEND **/
					$OtherData = ['gc_code' => $GiftCouponInfo['Code'], 'gc_amount' => $GiftCouponInfo['Value'], 'remaining_value' => $gcRESNew[0]['remaining_value']];
					OmanisendRequest('61fbcf88bf58ef001efc0243', $gcRES[0], $OtherData);
					/** OMANISEND **/
				}
			}
		}

		/*Session::forget('TOKEN');
		Session::forget('PAYPAL_PAYER_ID');
		Session::forget('PayPalToken');
		Session::forget('token');
		Session::forget('nvpReqArray');
		Session::forget('shipping_insurance');
		Session::forget('shipping_insurance_charge');
		Session::forget('ShoppingCart');
		Session::forget('AMAZON_ACCESS_TOKEN');
		Session::forget('StripePaymentType');
		Session::forget('PaypalEmail');
		Session::forget('Paypalcity');
		Session::forget('Paypalcountry');
		Session::forget('Paypalstate');
		Session::forget('Paypalzipcode');
		Cookie::forget('MY_SHOP_CART_COOKIE');
		Session::forget('ShoppingCart.StoreOrderID');
		Session::forget('ShoppingCart.StoreCashSplitPaymentId');
		Session::forget('ShoppingCart.WebsiteCashSplitPaymentId');
		Session::forget('ShoppingCart.StoreCashPaymentId');*/

		/** OMANISEND **/
			//OmanisendRequest('removeCart');
		/** OMANISEND **/

		Log::channel('order_receipt')->info('Order Receipt Ended for Order - ' . $OrderID);
		Log::channel('order_receipt')->info('--------------------------------');
		//return view('checkout.'.$Template)->with($this->PageData);
		return view('pos.order-receipts')->with($this->PageData);
	}
	public function ProcessPayment(Request $request)
	{
		$amount = floatval($request->input('amount'));

		$CartItem = Session::get('ShoppingCart.Cart');
		$grouped = collect($CartItem)->groupBy('OrderType');
		$storeCount = $grouped->get('Store', collect())->count();
		$websiteCount = $grouped->get('Website', collect())->count();

		$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;

		$WebsiteOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;

		$NormalOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;

		$StoreCashSplitPaymentId = Session::has('ShoppingCart.StoreCashSplitPaymentId') ? Session::get('ShoppingCart.StoreCashSplitPaymentId') : null;

		$WebsiteCashSplitPaymentId = Session::has('ShoppingCart.WebsiteCashSplitPaymentId') ? Session::get('ShoppingCart.WebsiteCashSplitPaymentId') : null;

		$StoreCashPaymentId = Session::has('ShoppingCart.StoreCashPaymentId') ? Session::get('ShoppingCart.StoreCashPaymentId') : null;

		if (null !== $request->input('PaymentType') && $request->input('PaymentType') == "Cash") {

			if ($storeCount > 0 && $websiteCount > 0 && $StoreOrderId > 0 && $WebsiteOrderId > 0 && $amount > 0) {
				if (empty($StoreCashSplitPaymentId)) {
					$StoreInsert = array(
						'status' => 'Completed',
						'amount' => NumberFormat($amount),
						'Payment_type' => $request->input('PaymentType'),
						'orders_id'	 => Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null
					);

					$PlaceStore = StorePayment::create($StoreInsert);

					Session::put('ShoppingCart.StoreCashSplitPaymentId', $PlaceStore->payment_id);
				} else {
					$StoreUpdate = array(
						'amount' => NumberFormat($amount)
					);

					$CurrOrder = StorePayment::where('payment_id', $StoreCashSplitPaymentId)
						->where('orders_id', Session::get('ShoppingCart.StoreOrderID'))
						->first();

					if ($CurrOrder) {
						$CurrOrder->update($StoreUpdate);
					}
				}

				if (empty($WebsiteCashSplitPaymentId)) {
					$StoreInsert = array(
						'status' => 'Completed',
						'amount' => NumberFormat($amount),
						'Payment_type' => $request->input('PaymentType'),
						'orders_id'	 => Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null
					);

					$PlaceStore = StorePayment::create($StoreInsert);
					Session::put('ShoppingCart.WebsiteCashSplitPaymentId', $PlaceStore->payment_id);
				} else {
					$StoreUpdate = array(
						'amount' => NumberFormat($amount)
					);

					$CurrOrder = StorePayment::where('payment_id', $WebsiteCashSplitPaymentId)
						->where('orders_id', Session::get('ShoppingCart.WebsiteOrderID'))
						->first();

					if ($CurrOrder) {
						$CurrOrder->update($StoreUpdate);
					}
				}

				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' received.'
				]);
			} else if ($NormalOrderId > 0 && $amount > 0) {

				if (empty($StoreCashPaymentId)) {
					$StoreInsert = array(
						'status' => 'Completed',
						'amount' => NumberFormat($amount),
						'Payment_type' => $request->input('PaymentType'),
						'orders_id'	 => $NormalOrderId
					);

					$PlaceStore = StorePayment::create($StoreInsert);

					Session::put('ShoppingCart.StoreCashPaymentId', $PlaceStore->payment_id);
				} else {

					$StoreUpdate = array(
						'amount' => NumberFormat($amount)
					);

					$CurrOrder = StorePayment::where('payment_id', Session::get('ShoppingCart.StoreCashPaymentId'))
						->where('orders_id', $NormalOrderId)
						->first();

					if ($CurrOrder) {
						$CurrOrder->update($StoreUpdate);
					}
				}

				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' received.'
				]);
			} else {

				return response()->json([
					'status' => 'error',
					'message' => 'Order not found,please try again.'
				]);
			}
		}
	}
	public function PlaceOrder(Request $request)
	{

		addLog('PlaceOrderStart');

		$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

		if (fopen($myFile, 'a+')) {
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			if (Session::has('ShoppingCart') && Session::get('ShoppingCart') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order start Session Data : " . json_encode(Session::get('ShoppingCart')) . " :\n";
			}
			if (Session::has('sess_useremail') && Session::get('sess_useremail') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order start Session User Email : " . Session::get('sess_useremail') . " :\n";
			}
			fwrite($fh, $stringData);
			fclose($fh);
		}
		$this->SetupCart();

		$skrSKU = $this->OutOfStockItemsRemove();

		if (count($skrSKU) > 0) {
			Session::put('SKUListArrayVal', $skrSKU);
			$err_msg = "Sorry some of your cart items are out of stock. Please update your cart and then proceed for payment.";
			$log['err_msg'] = $err_msg;
			addLog('PlaceOrder', $log);
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/shoppingcart');
		}
		$IsGiftCertificateItem = '';

		if (Session::get('sess_useremail') == 'gequaldev@gmail.com') {
			config(['app.debug' => true]);
		}
		if (config('global.SHOPP_STATUS') == 'Close') {
			Session::forget('ShoppingCart');
			$err_msg = "Shop status is close.";
			$log['err_msg'] = $err_msg;
			addLog('PlaceOrder', $log);
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/');
		}
		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) <= 0) {
			Session::forget('ShoppingCart');
			$err_msg = "Shoping cart total is not valid.";
			$log['err_msg'] = $err_msg;
			addLog('PlaceOrder', $log);
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/shoppingcart');
		}
		if ($this->Is_WholeSaler_Allow() == false) {
			$err_msg = "Wholesaler is not allowed.";
			$log['err_msg'] = $err_msg;
			addLog('PlaceOrder', $log);
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/shoppingcart');
		}

		if (isset($request->is_stripe_wallet) && $request->is_stripe_wallet == "google_pay") {
			$log['is_stripe_wallet'] = $request->is_stripe_wallet;
			addLog('PlaceOrder', $log);
			$request->PaymentMethod = 'STRIPE_GOOGLEPAY';
			$Payment_Method = Session::get("StripePaymentType");
			$PaymentResponse = Session::get("PayMethodRes");
		}

		if (isset($request->is_stripe_applepay) && $request->is_stripe_applepay == "apple_pay") {
			$log['is_stripe_applepay'] = $request->is_stripe_applepay;
			addLog('PlaceOrder', $log);
			$request->PaymentMethod = 'STRIPE_APPLEPAY';
			$Payment_Method = Session::get("StripePaymentType");
			$PaymentResponse = Session::get("PayMethodRes");
		}

		if (isset($request->is_btm_ap_chkout) && $request->is_btm_ap_chkout == "Yes") {	//bottom afterpay checkout
			$request->PaymentMethod = "PAYMENT_PAYWITHAFTERPAY";
		}

		$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
		$is_amazon = 0;
		if ($this->GetNetTotal() <= 0 && $CreditDiscount > 0 && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && Session::get("etype") == "M" && Session::get("sess_icustomerid") > 0 && Session::get("is_dropshipper") != "Yes") {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod  = "PAYMENT_CL";
		}

		if ($this->GetNetTotal() <= 0 && $CreditDiscount <= 0) {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod = 'PAYMENT_GIFT_CERTIFICATE';
		}

		if ($request->PaymentMethod != 'PAYMENT_PAYPALEC' && (!isset($request->is_paypal) || $request->is_paypal != "yes")) {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_PAYPALEC' && isset($request->is_paypal) && $request->is_paypal == "yes") {
			$this->setPaymentDetail($request);
		}
		if ($request->PaymentMethod == 'PAYMENT_PAYWITHAFTERPAY') {
			$this->setPaymentDetail($request);
		}
		if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_STRIPE_BUTTON') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'STRIPE_GOOGLEPAY') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'STRIPE_APPLEPAY') {
			$this->setPaymentDetail($request);
		}

		if ($request->PaymentMethod == 'PAYMENT_AUTHORIZENETCC' || $request->PaymentMethod == 'PAYMENT_PAYPALCC'  || $request->PaymentMethod == 'PAYMENT_BRAINTREECC') {
			if ($request->CCType == '' || $request->CCNumber == '' ||  $request->CCMonth == '' || $request->CCYear == '' || $request->CCholdername == '') {
				$err_msg = "Error while processing your order, Please try again.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError:' . $request->PaymentMethod . ' - Credit Card issue.');
				return redirect()->back();
			}
		}

		if ($this->GetNetTotal() <= 0) {
			$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
			$GCAmount = 0;
			$GCCode = "";
			$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');
			if ($GiftCoupon) {
				$GCAmount = $GiftCoupon['Value'];
				$GCCode = $GiftCoupon['Code'];
			}
			$log['GiftCoupon'] = json_encode($GiftCoupon);
			$log['arrPaymentDetail'] = json_encode($arrPaymentDetail);
			if (empty($GCCode) && $GCCode == '' && isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] != '' && $arrPaymentDetail['Payment_Type'] != 'PAYMENT_CL') {
				$err_msg = "Order total is invalid, please try again.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				return redirect('/shoppingcart');
			}
		}

		$customer_id = (int)Session::get('sess_icustomerid');

		if (!Session::has('sess_icustomerid') || empty($customer_id)) {
			$err_msg = "Error while processing your order, Please try again.";
			$log['err_msg'] = $err_msg;
			addLog("PlaceOrder", $log);
			Session::flash('PlaceOrderError', $err_msg);
			Log::info('PlaceOrderError: Customer Not Found');
			return redirect()->back();
		}

		$Billing  = Session::get('ShoppingCart.BillingAddress');
		if (!isset($_REQUEST['AppleGPay'])) {
			if (($request->PaymentMethod != 'PAYMENT_STRIPE_BUTTON' && $request->PaymentMethod != 'STRIPE_GOOGLEPAY' && $request->PaymentMethod != 'STRIPE_APPLEPAY' && $request->PaymentMethod != 'PAYMENT_PAYWITHAMAZON' && $is_amazon != 1) && (trim($Billing['first_name']) == '' || trim($Billing['last_name']) == '' || trim($Billing['address1']) == '' || trim($Billing['city']) == '' ||
				trim($Billing['zip']) == '' || trim($Billing['state']) == '' || trim($Billing['country']) == '' || trim($Billing['phone']) == '' ||
				trim($Billing['email']) == '')) {
				$err_msg = "Please fill the required fields for billing information1. ";
				$log['Billing'] = json_encode($Billing);
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: BillingAddress Validation');
				return redirect()->back();
			}
		}

		$Shipping = Session::get('ShoppingCart.ShippingAddress');
		$log['Shipping'] = $Shipping;
		addLog("PlaceOrder", $log);
		$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');
		//$onlyGCPurchased = $request->onlyGCPurchased;
		/*if(!$onlyGCPurchased || $onlyGCPurchased == "")
			$onlyGCPurchased = 0;*/
		if ($onlyGCPurchased == 0) {
			//if($request->PaymentMethod != 'PAYMENT_PAYWITHAMAZON' && $request->PaymentMethod != 'STRIPE_GOOGLEPAY' && $request->PaymentMethod != 'STRIPE_APPLEPAY' && (trim($Shipping['first_name']) == '' || trim($Shipping['last_name']) == '' || trim($Shipping['address1']) == '' || trim($Shipping['city']) == '' || trim($Shipping['zip'])== '' || trim($Shipping['state'])== '' || trim($Shipping['country'])== '' ))
			if ($request->PaymentMethod != 'PAYMENT_PAYWITHAMAZON' && $request->PaymentMethod != 'STRIPE_GOOGLEPAY' && $request->PaymentMethod != 'STRIPE_APPLEPAY' && ((isset($Shipping['first_name']) && trim($Shipping['first_name']) == '') || (isset($Shipping['last_name']) &&	trim($Shipping['last_name']) == '') || (isset($Shipping['address1']) && trim($Shipping['address1']) == '') || (isset($Shipping['city']) && trim($Shipping['city']) == '') || (isset($Shipping['zip']) && trim($Shipping['zip']) == '') || (isset($Shipping['state']) && trim($Shipping['state']) == '') || (isset($Shipping['country']) && trim($Shipping['country']) == ''))) {
				$err_msg = "Please fill the required fields for shipping information. ";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: ShippingAddress Validation');
				return redirect()->back();
			}
		}

		if ($onlyGCPurchased == 1) {
			Session::put('ShoppingCart.BillingAsShipping', 'Yes');
			$Billing['sameasbill'] = 'Yes';
			$this->SetShippingAddress($Billing);
			$Shipping = Session::get('ShoppingCart.ShippingAddress');
		}

		$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');
		if (Session::has('ShoppingCart.Payment_Detail')) {
			//if((trim($arrPaymentDetail['Payment_Type']) == '' || $arrPaymentDetail['Payment_Type'] =='PAYMENT_PAYPALEC') && (!isset($request->is_paypal)))
			if (((isset($arrPaymentDetail['Payment_Type']) && trim($arrPaymentDetail['Payment_Type']) == '') || (isset($arrPaymentDetail['Payment_Type']) &&	$arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALEC')) && (!isset($request->is_paypal))) {
				$err_msg =  "Please choose payment method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Payment Method Not Selected');
				return redirect()->back();
			}

			//if(trim($arrPaymentDetail['Payment_Type']) == '' && isset($request->is_paypal) && $request->is_paypal == "yes")
			if (isset($arrPaymentDetail['Payment_Type']) && trim($arrPaymentDetail['Payment_Type']) == '' && isset($request->is_paypal) && $request->is_paypal == "yes") {
				$err_msg =  "Please choose payment method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Payment Method Not Selected');
				return redirect()->back();
			}

			if ($arrPaymentDetail['Payment_Type'] == 'PAYMENT_AUTHORIZENETCC' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALCC') {
				if ($arrPaymentDetail['CCType'] == '' || $arrPaymentDetail['CCNumber'] == '' || $arrPaymentDetail['CCMonth'] == '' || $arrPaymentDetail['CCYear'] == '' || $arrPaymentDetail['CCName'] == '') {
					$msg = "Please fill the credit card information.";
					$log['err_msg'] = $err_msg;
					addLog("PlaceOrder", $log);
					Session::flash('PlaceOrderError', $msg);
					Log::info('PlaceOrderError: ' . $arrPaymentDetail['Payment_Type'] . ' Credit Card issue.');
					return redirect()->back();
				}
			}
		}
		$ShippingInfo = Session::get('ShoppingCart.Shipping');
		if ($onlyGCPurchased == 0) {
			if (empty($ShippingInfo['ShippingMethodID']) || (int)$ShippingInfo['ShippingMethodID'] <= 0) {
				$err_msg =  "Please choose shipping method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Shipping Method Not Selected');
				return redirect()->back();
			}
		}

		$currency_info = Session::get('currency_code') . "#" . Session::get('currency_symbol') . "#" . Session::get('currency_rate');
		if (Session::has('etype') && Session::get('etype') == 'M')
			$checkout_type = 'M';
		else
			$checkout_type = 'G';

		$cc_info = "";
		if (Session::has('ShoppingCart.Payment_Detail')) {
			if ($arrPaymentDetail['Payment_Type'] == 'PAYMENT_AUTHORIZENETCC' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALCC') {
				$cc_info = $arrPaymentDetail['CCType'] . "," . substr(trim($arrPaymentDetail['CCNumber']), -4) . ",Exp." . $arrPaymentDetail['CCMonth'] . "/" . $arrPaymentDetail['CCYear'] . ",CSC." . $arrPaymentDetail['CSC'];
			}
		}

		$ShippingInfo	 = Session::get('ShoppingCart.Shipping');
		$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
		$CouponCode = $this->GetAllCoupons('CouponCode');
		$couponRS = array();
		$OneCouponCode = "";
		$coupon_id = '';
		$SecondCouponCode = "";
		$Second_coupon_id = "";

		$yotporewardcode = '';

		if (Session::has('ShoppingCart.YotpoRewardCode') && Session::get('ShoppingCart.YotpoRewardCode') != '') {
			$yotporewardcode = Session::get('ShoppingCart.YotpoRewardCode');
		}

		if (!empty($CouponCode)) {
			$couponRS = Coupon::where('coupon_number', '=', $CouponCode)->limit(1)->get();
			if ($couponRS && $couponRS->count() > 0) {
				$OneCouponCode = $couponRS[0]->coupon_number;
				$coupon_id = $couponRS[0]->coupon_id;
			}

			$Temp_Second_Coupon_Code =  $this->GetAllCoupons('SecondCouponCode');
			if ($Temp_Second_Coupon_Code != '') {
				$couponRS = Coupon::where('coupon_number', '=', $Temp_Second_Coupon_Code)->limit(1)->get();

				if (count($couponRS) > 0) {
					$SecondCouponCode = $couponRS[0]->coupon_number;
					$Second_coupon_id = $couponRS[0]->coupon_id;
				}
				if ($SecondCouponCode != '') {
					$OneCouponCode = $OneCouponCode . "#" . Session::get('ShoppingCart.PromoCoupon.FirstCouponDiscount') . ":" . $SecondCouponCode . "#" . Session::get('ShoppingCart.PromoCoupon.SecondCouponDiscount');
				}
			}
		}

		## Wholesaler Field Start ###
		$w_user_type = Session::get('eusertype');
		$w_ilevelid  = (Session::has('ilevelid')) ? Session::get('ilevelid') : 0;
		## Wholesaler Field End ###

		$GiftValue = $this->FreeGiftValue($this->GetNetTotal());

		if (count($GiftValue) > 0) {
			$free_gift = Session::get('ShoppingCart.FreeGift');
			$gift_from = Session::get('ShoppingCart.GiftFrom');
			$gift_to   = Session::get('ShoppingCart.GiftTo');
			$gift_message_customer = Session::get('ShoppingCart.GiftMessageCustomer');
		} else {
			$free_gift = '';
			$gift_from = '';
			$gift_to   = '';
			$gift_message_customer = (isset($request->gift_message_customer)) ? $request->gift_message_customer : '';
			if (strtolower(trim($gift_message_customer ?? '')) == " *gift message") {
				$gift_message_customer = '';
			}
			Session::forget('ShoppingCart.FreeGift');
			Session::forget('ShoppingCart.GiftFrom');
			Session::forget('ShoppingCart.GiftTo');
			Session::forget('ShoppingCart.GiftMessageCustomer');
		}

		if (isset($request->is_stripe_applepay) && $request->is_stripe_applepay == "apple_pay") {
			$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayStripOrderPlaceLog.txt';
			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');
				$stringData = date("m/d/Y H:i:s") . " Eight Place Order " . json_encode(Session::get('ShoppingCart')) . " \n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
		}

		$CreditData = $this->GetCreditLimitAmount();
		$CreditAmt = $CreditData['CreditLimit'];
		$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
		$cust_current_credit_limit = $CreditAmt;
		$apply_credit = $CreditDiscount;
		$remaining_credit = $CreditData['RemainCreditLimit'];

		if ($CreditDiscount > 0) {
			$use_credit_limit = 'Yes';
		} else {
			$use_credit_limit = 'No';
		}
		//Credit Limit Code End

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_DS')
			$is_dropship_order = 'Yes';
		else
			$is_dropship_order = 'No';

		if (isset($request->customer_comment) && strtolower(trim($request->customer_comment)) == "special request") {
			$request->customer_comment = "";
		}

		$fullShippingname = '';
		$is_maxtwoday = "No";
		$EstimatedDeliveryDate = '';
		$ShipMethodName = '';
		$ShipMethodCharge = 0;
		$OrdShipSignature = 0;
		$OrdShipInsurance = 0;
		if ($onlyGCPurchased == 0) {
			if ($ShippingInfo["ShippingCharge"] > 0) {
				$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(" . Session::get('currency_symbol') . $ShippingInfo["ShippingCharge"] . ")</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
			} else {
				$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(Free)</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
			}
			if (strtolower($ShippingInfo['ShippingMethodName'] ?? '') == 'max2days') {
				$is_maxtwoday = "Yes";
			}
			$EstimatedDeliveryDate = Session::get("ShoppingCart.EstimatedDeliveryDate");
			$ShipMethodName = $ShippingInfo['ShippingMethodName'];
			$ShipMethodCharge = $this->GetShippingCharge();
			$OrdShipSignature = $this->GetAllCharges('ShippingSignature');
			$OrdShipInsurance = $this->GetAllCharges('ShippingInsurance');
		}

		$cur_date = date("Y-m-d");
		if ($EstimatedDeliveryDate == "" && strtotime($EstimatedDeliveryDate) < strtotime($cur_date)) {
			$EstimatedDeliveryDate = "0000-00-00 00:00:00";
		}

		$referDiscountId = (Session::has('ShoppingCart.ReferDiscountId')) ? Session::get('ShoppingCart.ReferDiscountId') : 0;

		$merge_note = (Session::has('ShoppingCart.merge_note')) ? Session::get('ShoppingCart.merge_note') : "";
		$Payment_Type = (isset($arrPaymentDetail['Payment_Type'])) ? $arrPaymentDetail['Payment_Type'] : '';
		$Payment_Method = (isset($arrPaymentDetail['Payment_Method'])) ? $arrPaymentDetail['Payment_Method'] : '';

		$PaymentResponse = "";
		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == "PAYMENT_STRIPE_BUTTON") {
			$Payment_Type = "PAYMENT_STRIPE";
			$Payment_Method = Session::get("StripePaymentType");
			$PaymentResponse = Session::get("PayMethodRes");
		}

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == "STRIPE_GOOGLEPAY") {
			$PaymentResponse = Session::get("PayMethodRes");
		}

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == "STRIPE_APPLEPAY") {
			$PaymentResponse = Session::get("PayMethodRes");
		}

		$customer_comment = (isset($request->customer_comment)) ? $request->customer_comment : '';
		$SubTotal = (float)Session::get('ShoppingCart.SubTotal');
		$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
		$GCAmount = 0;
		$GCCode = "";
		if ($GiftCoupon) {
			$GCAmount = $GiftCoupon['Value'];
			$GCCode = $GiftCoupon['Code'];
		}

		$paymentintentid = "";
		if (Session::get("StripePaymentType") == "Google Pay" && isset($request->is_stripe_wallet)) {
			$Payment_Method = Session::get("StripePaymentType");
			$Payment_Type = "STRIPE_GOOGLEPAY";
			$paymentintentid = Session::has("ShoppingCart.apple_google_paymentintentid") ? Session::get("ShoppingCart.apple_google_paymentintentid") : '';
		}

		if (Session::get("StripePaymentType") == "Apple Pay" && isset($request->is_stripe_wallet)) {
			$Payment_Method = Session::get("StripePaymentType");
			$Payment_Type = "STRIPE_APPLEPAY";
			$paymentintentid = Session::has("ShoppingCart.apple_google_paymentintentid") ? Session::get("ShoppingCart.apple_google_paymentintentid") : '';
		}
		/*if($request->is_stripe_wallet == "google_pay")
		{
			$Payment_Method = Session::get("StripePaymentType");
			$Payment_Type = "STRIPE_GOOGLEPAY";
		}

		if($request->is_stripe_wallet == "apple_pay")
		{
			$Payment_Method = Session::get("StripePaymentType");
			$Payment_Type = "STRIPE_APPLEPAY";
		}*/

		$ShippingSignatureFlag = 'No';
		if ($onlyGCPurchased == 0 && isset($request->shipsignatureflag) && $request->shipsignatureflag == 'Yes')
			$ShippingSignatureFlag = 'Yes';

		if ($onlyGCPurchased == 0 && isset($request->shipping_signature) && $request->shipping_signature == 'Yes')
			$ShippingSignatureFlag = 'Yes';

		$OrderInsert = array(
			'customer_id'		=> $customer_id,
			'sub_total' 		=> $SubTotal,
			'shipping_amt' 		=> $ShipMethodCharge,
			'tax' 				=> $this->GetAllCharges('Tax'),
			'gift_charge' 		=> $this->GetAllCharges('GiftWrappingCharge'),
			'gift_message' 		=> '',
			'is_gift_order'		=> 'No',
			'handling_charge' 	=> '0.00',
			'wire_discount' 	=> '0.00',
			'auto_discount' 	=> $this->GetAllDiscounts('AutoDiscount'),
			'quantity_discount'	=> $this->GetAllDiscounts('QuantityDiscount'),
			'reward_discount'	=> $this->GetAllDiscounts('YotpoRewardDiscount'),
			'coupon_amount' 	=> $this->GetAllDiscounts('CouponDiscount'),
			'coupon_id' 		=> $coupon_id,
			'Second_coupon_id'	=> $yotporewardcode,
			'coupon_code' 		=> $OneCouponCode,
			'gc_amount' 		=> $this->GetAllDiscounts('GiftCoupon'),
			'gc_code' 			=> $GCCode,
			'refer_id'			=> $referDiscountId,
			'refer_amount' 		=> $this->GetAllDiscounts('AutoReferDiscount'),
			'order_total' 		=> $this->GetNetTotal(),
			'shipinfo' 			=> $ShipMethodName,
			'payment_type' 		=> $Payment_Type,
			'payment_method' 	=> $Payment_Method,
			'pay_status' 		=> 'Unpaid',
			'ccinfo' 			=> $cc_info,
			'customer_comment' 	=> $customer_comment,
			'status'			=> 'Pending',
			'currency_info'		=> $currency_info,
			'checkout_type' 	=> $checkout_type,
			'user_type' 		=> $w_user_type,
			'ilevelid' 			=> $w_ilevelid,
			//'level_price' 		=> $w_level_price,
			'ship_first_name' 	=> isset($Shipping['first_name']) ? $Shipping['first_name'] : '', //$Shipping['first_name'],
			'ship_last_name' 	=> isset($Shipping['last_name']) ? $Shipping['last_name'] : '', //$Shipping['last_name'],
			'ship_company' 		=> isset($Shipping['company']) ? $Shipping['company'] : '', //$Shipping['company'],
			'ship_email' 		=> isset($Shipping['email']) ? $Shipping['email'] : '', //$Shipping['email'],
			'ship_address1' 	=> isset($Shipping['address1']) ? $Shipping['address1'] : '', //$Shipping['address1'],
			'ship_address2' 	=> isset($Shipping['address2']) ? $Shipping['address2'] : '', //$Shipping['address2'],
			'ship_city' 		=> isset($Shipping['city']) ? $Shipping['city'] : '', //$Shipping['city'],
			'ship_zip' 			=> isset($Shipping['zip']) ? $Shipping['zip'] : '', //$Shipping['zip'],
			'ship_state' 		=> isset($Shipping['state']) ? $Shipping['state'] : '', //$Shipping['state'],
			'ship_country' 		=> isset($Shipping['country']) ? $Shipping['country'] : '', //$Shipping['country'],
			'ship_phone' 		=> isset($Shipping['phone']) ? $Shipping['phone'] : '', //$Shipping['phone'],
			'bill_first_name' 	=> isset($Billing['first_name']) ? $Billing['first_name'] : '', //$Billing['first_name'],
			'bill_last_name' 	=> isset($Billing['last_name']) ? $Billing['last_name'] : '', //$Billing['last_name'],
			'bill_company' 		=> isset($Billing['company']) ? $Billing['company'] : '', //$Billing['company'],
			'bill_email' 		=> isset($Billing['email']) ? $Billing['email'] : '', //$Billing['email'],
			'bill_address1' 	=> isset($Billing['address1']) ? $Billing['address1'] : '', //$Billing['address1'],
			'bill_address2' 	=> isset($Billing['address2']) ? $Billing['address2'] : '', //$Billing['address2'],
			'bill_city' 		=> isset($Billing['city']) ? $Billing['city'] : '', //$Billing['city'],
			'bill_zip' 			=> isset($Billing['zip']) ? $Billing['zip'] : '', //$Billing['zip'],
			'bill_state' 		=> isset($Billing['state']) ? $Billing['state'] : '', //$Billing['state'],
			'bill_country' 		=> isset($Billing['country']) ? $Billing['country'] : '', //$Billing['country'],
			'bill_phone' 		=> isset($Billing['phone']) ? $Billing['phone'] : '', ////$Billing['phone'],
			'customer_ip' 		=> $_SERVER['REMOTE_ADDR'],
			'customer_browser' 	=> $_SERVER['HTTP_USER_AGENT'],
			'is_only_gc'		=> (string)$onlyGCPurchased,
			'free_gift'			=> $free_gift,
			//======= Added Code Date 09/02/2015 Start Here ===//
			'gift_from'				=> $gift_from,
			'gift_to'				=> $gift_to,
			'gift_message_customer'	=> $gift_message_customer,
			//======= Added Code Date 09/02/2015 End Here ===//
			//Credit Limit Code Start
			'cust_current_credit_limit' => $cust_current_credit_limit,
			'apply_credit'          => $apply_credit,
			'remaining_credit'      => $remaining_credit,
			'use_credit_limit'      => $use_credit_limit,
			//Credit Limit Code End
			'is_dropship_order'     => $is_dropship_order,
			'shipping_signature'	 => $OrdShipSignature,
			'is_shipping_signature' => $ShippingSignatureFlag,
			'Is_GiftCertificatPurchase' => $this->GetCartAttribute('CheckGCPurchasedVal'),
			'EstimatedDeliveryDate' 	=> $EstimatedDeliveryDate,
			'fullshipping_info'		=> 	$fullShippingname,
			'merge_note'		=> 	$merge_note,
			'bogo_discount'	=> $this->GetAllDiscounts('DogoDiscount'),
			'is_maxtwoday'	=> $is_maxtwoday,
			'route_shipping_insurance_charge' => $OrdShipInsurance,
			'vLang_flag' => Session::get('ShoppingCart.YotpoFreeGiftCoupon'),
			'paymentintentid' => $paymentintentid,
			'payment_gateway_response' => Session::get("PayMethodRes") ? Session::get("PayMethodRes") : '',
			'BraintreeResponse'        => Session::get('ShoppingCart.AfterPay.Checkout_Token') ? Session::get('ShoppingCart.AfterPay.Checkout_Token') : '',
			'order_type'			=> 'Website',
		);

		if (Auth::guard('store')->check()) {
			//echo "<pre>";
			//print_r(Auth::guard('store')->user());
			$store = Auth::guard('store')->user();
			$OrderInsert['store_id']   =  $store->store_id;
			$OrderInsert['order_type']   =  'In-Store';
		}

		// if(Auth::guard('store')->check()){

		// 			$store = auth('store')->user()->store;
		// 			$OrderInsert['store_id']   =  $store->store_id;
		// 			$OrderInsert['order_type']   =  'In-Store';
		// }

		$agent_email_id = '';
		$salesPersonId = 0;
		if (Auth::guard('store')->check()) {
			$agent_email_id = Auth::guard('store')->user()->store_user_email;
			$salesPersonId = Auth::guard('store')->user()->store_user_id;
			$OrderInsert['sales_person_id'] = $salesPersonId;
		}

		//echo "<pre>"; print_r($OrderInsert); exit;
		// $PlaceOrder = Order::create($OrderInsert);
		// $OrderID = $PlaceOrder->orders_id;
		// $aa = Session::put('ShoppingCart.OrderID',$OrderID); // set order id in cart

		//if(isset($_REQUEST['AppleGPay']) && Session::has('ShoppingCart.OrderID') && Session::get('ShoppingCart.OrderID')!=''){
		$is_order_update = 'N';
		/*if(isset($request->is_step_gpay) && $request->is_step_gpay!='' && Session::has('ShoppingCart.OrderID') && Session::get('ShoppingCart.OrderID')!=''){
			$OrderID = Session::get('ShoppingCart.OrderID');
			//Log::info('Order Table Order Id '.$_REQUEST['AppleGPay']."--".$OrderID);
			$CurrOrder = Order::find($OrderID);
			$udpRefer = $CurrOrder->update($OrderInsert);
			$is_order_update = 'Y';
		} else {
			$PlaceOrder = Order::create($OrderInsert);
			$OrderID = $PlaceOrder->orders_id;
			$aa = Session::put('ShoppingCart.OrderID',$OrderID); // set order id in cart
		}*/
		$log['PlaceOrderInsert'] = json_encode($OrderInsert);
		addLog("PlaceOrder", $log);
		$PlaceOrder = Order::create($OrderInsert);
		$OrderID = $PlaceOrder->orders_id;
		$aa = Session::put('ShoppingCart.OrderID', $OrderID); // set order id in cart

		//Update sf_accountid for Customer
		$UpdateCustomer = ['sf_accountid' => $store->store_id];
		$cust_upd = Customer::where('customer_id', '=', $customer_id)->update($UpdateCustomer);
		//Update sf_accountid for Customer

		if (isset($request->PaymentMethod) && $request->PaymentMethod == 'PAYMENT_PAYPALEC') {

			$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');
				$stringData = date("m/d/Y H:i:s") . " PayPal Order Insert :" . json_encode($OrderInsert) . "\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
		}

		$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

		if (fopen($myFile, 'a+')) {
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			if (Session::has('ShoppingCart') && Session::get('ShoppingCart') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Data : " . json_encode(Session::get('ShoppingCart')) . " :\n";
			}
			if (isset($OrderInsert) && !empty($OrderInsert)) {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Order Data : " . json_encode($OrderInsert) . " :\n";
			}
			if (Session::has('sess_useremail') && Session::get('sess_useremail') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Session User Email : " . Session::get('sess_useremail') . " :\n";
			}
			fwrite($fh, $stringData);
			fclose($fh);
		}

		if ($OrderID != "") {
			// To add 'OR' Change on :: 06-10-2015
			$CurrOrder = Order::find($OrderID);
			//$updateOrder = array('orders_no'	 => "OR" . $OrderID);
			$updateOrder = array('orders_no'	 => GetStorePrefix() . $OrderID);
			$udpRefer = $CurrOrder->update($updateOrder);
		}

		$tempCart = Session::get('ShoppingCart.Cart');
		$cnt_row  = count($tempCart);

		$IsVender = "No";
		$IsAmazOR = 'No';
		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYWITHAMAZON') {
			$IsAmazOR = 'Yes';
		}

		$IsPerfumePWVendor = "No";
		$TaxValueNew = $this->GetAllCharges('TaxValue');
		//echo "<pre>"; print_r($tempCart); exit;

		//if(Session::has('sess_useremail') && Session::get('sess_useremail') == 'gequaldev@gmail.com'){
		if ($is_order_update == 'N') {
			for ($i = 0; $i < $cnt_row; $i++) {
				if (empty($this->GetAllDiscounts('CouponDiscount')) || $this->GetAllDiscounts('CouponDiscount') <= 0) {
					$tempCart[$i]['CouponDisItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('AutoDiscount')) || $this->GetAllDiscounts('AutoDiscount') <= 0) {
					$tempCart[$i]['AutoItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('QuantityDiscount')) || $this->GetAllDiscounts('QuantityDiscount') <= 0) {
					$tempCart[$i]['QuantityItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('YotpoRewardDiscount')) || $this->GetAllDiscounts('YotpoRewardDiscount') <= 0) {
					$tempCart[$i]['RewardItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('DogoDiscount')) || $this->GetAllDiscounts('DogoDiscount') <= 0) {
					$tempCart[$i]['BogoItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('GiftCoupon')) || $this->GetAllDiscounts('GiftCoupon') <= 0) {
					$tempCart[$i]['GiftCertificateItemWiseDiscout'] = 0;
				}
				if (empty($apply_credit) && $apply_credit <= 0) {
					$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0;
				}

				$ActualPrice = 0;
				$CouponDisItemWiseDiscout = 0;
				$AutoItemWiseDiscout = 0;
				$QuantityItemWiseDiscout = 0;
				$RewardItemWiseDiscout = 0;
				$BogoItemWiseDiscout = 0;
				$GiftCertificateItemWiseDiscout = 0;
				$CreditLimitItemWiseDiscout = 0;

				if (!empty($tempCart[$i]['CouponDisItemWiseDiscout']) && $tempCart[$i]['CouponDisItemWiseDiscout'] > 0) {
					$CouponDisItemWiseDiscout = $tempCart[$i]['CouponDisItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['AutoItemWiseDiscout']) && $tempCart[$i]['AutoItemWiseDiscout'] > 0) {
					$AutoItemWiseDiscout = $tempCart[$i]['AutoItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['QuantityItemWiseDiscout']) && $tempCart[$i]['QuantityItemWiseDiscout'] > 0) {
					$QuantityItemWiseDiscout = $tempCart[$i]['QuantityItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['RewardItemWiseDiscout']) && $tempCart[$i]['RewardItemWiseDiscout'] > 0) {
					$RewardItemWiseDiscout = $tempCart[$i]['RewardItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['BogoItemWiseDiscout']) && $tempCart[$i]['BogoItemWiseDiscout'] > 0) {
					$BogoItemWiseDiscout = $tempCart[$i]['BogoItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['GiftCertificateItemWiseDiscout']) && $tempCart[$i]['GiftCertificateItemWiseDiscout'] > 0) {
					$GiftCertificateItemWiseDiscout = $tempCart[$i]['GiftCertificateItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['CreditLimitItemWiseDiscout']) && $tempCart[$i]['CreditLimitItemWiseDiscout'] > 0) {
					$CreditLimitItemWiseDiscout = $tempCart[$i]['CreditLimitItemWiseDiscout'];
				}

				$ActualPrice = $CouponDisItemWiseDiscout + $AutoItemWiseDiscout + $QuantityItemWiseDiscout + $RewardItemWiseDiscout + $BogoItemWiseDiscout + $GiftCertificateItemWiseDiscout + $CreditLimitItemWiseDiscout;

				if (!empty($ActualPrice) && $ActualPrice > 0) {
					$ActualPrice = $tempCart[$i]['TotPrice'] - $ActualPrice;
					$ActualPrice = NumberFormat($ActualPrice);
				} else {
					$ActualPrice = 0;
				}

				$ShoppingcartController = app()->make(ShoppingcartController::class);
				//$TotalItemTaxAmount = $tempCart[$i]['TotalItemTaxAmount']; //$ShoppingcartController->itemTaxAmount($ActualPrice,$tempCart[$i]['TotPrice']);
				$ItemWiseTaxVal = 0;
				if($TaxValueNew > 0)
				{
					$ItemWiseTaxVal = $ShoppingcartController->ItemWiseTax($tempCart[$i]['TotPrice'],$i);
				}

				if (!isset($tempCart[$i]['YotpoFreeGift']))
					$tempCart[$i]['YotpoFreeGift'] = '';

				$OrderDetailInsert = array(
					'orders_id'				=> $OrderID,
					'orders_no'				=> GetStorePrefix() . $OrderID, // To add 'OR' Change on :: 06-10-2015
					'products_id'			=> $tempCart[$i]['ProductID'],
					'sku' 					=> $tempCart[$i]['SKU'],
					'product_name'			=> $tempCart[$i]['ProductName'] . '<br>' . $tempCart[$i]['short_description'],
					'quantity' 				=> $tempCart[$i]['Qty'],
					'price' 				=> $tempCart[$i]['Price'],
					'total' 				=> $tempCart[$i]['TotPrice'],
					'status' 				=> '1',
					'item_price' 			=> (isset($tempCart[$i]['ItemPrice'])) ? $tempCart[$i]['ItemPrice'] : 0,
					'excluded_flag'  		=> (isset($tempCart[$i]['FinalSale'])) ? $tempCart[$i]['FinalSale'] : '',
					'is_gift_wrap'			=> (isset($tempCart[$i]['gift_wrap'])) ? $tempCart[$i]['gift_wrap'] : '',
					'is_free_gift_products' => (isset($tempCart[$i]['IS_Free_Gift'])) ? $tempCart[$i]['IS_Free_Gift'] : 'No',
					'VendorSKU'				=> $tempCart[$i]['VendorSKU'],
					'IsCosmo'				=> $tempCart[$i]['IsCosmo'],
					'IsNandansons'  		=> $tempCart[$i]['IsNandansons'],
					'IsPerfumePW'			=> $tempCart[$i]['IsPerfumePW'],
					'IsPCA'					=> $tempCart[$i]['IsPCA'],
					'IsND'					=> $tempCart[$i]['IsND'],
					'coupon_itemwise_discount' => $tempCart[$i]['ItemWiseCouponDiscount'],
					'handling_time_str'		=> (isset($tempCart[$i]['HandlingTimeStr'])) ? $tempCart[$i]['HandlingTimeStr'] : '',
					'attribute_info'        => (isset($tempCart[$i]['IsYotpoFreeProduct'])) ? $tempCart[$i]['IsYotpoFreeProduct'] : 'No',
					'actual_price'			=> $ActualPrice,
					'item_type'				=>	$tempCart[$i]['OrderType'],
					'item_tax_amount'		=> $ItemWiseTaxVal
				);
				$log['OrderDetailInsert'] = json_encode($OrderDetailInsert);
				addLog("PlaceOrder", $log);
				$OrdDetail = OrderDetail::create($OrderDetailInsert);
				$OrderDetailID = $OrdDetail->orders_detail_id;
				if (($tempCart[$i]['IsCosmo'] == "Yes" || $tempCart[$i]['IsNandansons'] == 'Yes' || $tempCart[$i]['IsPerfumePW'] == 'Yes' || $tempCart[$i]['IsPCA'] == "Yes" || $tempCart[$i]['IsND'] == "Yes") && $tempCart[$i]['VendorSKU'] != '') {
					$IsVender = "Yes";
				}
				if ($tempCart[$i]['IsPerfumePW'] == 'Yes') {
					$IsPerfumePWVendor = "Yes";
				}

				## Insert purchased GC

				$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem', $tempCart[$i]);

				//if($tempCart[$i]['SKU'] == config('global.GIFT_CERTIFICATE_SKU') || $tempCart[$i]['SKU'] == config('global.GIFT_CERTIFICATE_SKU1'))
				if ($IsGiftCertificateItem == 'Yes') {
					//$AddGC = $this->InsertGiftCertificateDB($tempCart[$i], $OrdDetail->orders_detail_id, $customer_id,$IsAmazOR);
					$AddGC = $this->checkGiftCertificateItem('InsertGiftCertificateInDB', $tempCart[$i], 'Yes', $OrdDetail->orders_detail_id, $customer_id, $IsAmazOR);
				}
			}
		}
		/*} else {
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
				if(empty($apply_credit) && $apply_credit<=0)
				{
					$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0;
				}

				$ActualPrice = 0;
				$CouponDisItemWiseDiscout = 0;
				$AutoItemWiseDiscout = 0;
				$QuantityItemWiseDiscout = 0;
				$RewardItemWiseDiscout = 0;
				$BogoItemWiseDiscout = 0;
				$GiftCertificateItemWiseDiscout = 0;
				$CreditLimitItemWiseDiscout = 0;

				if(!empty($tempCart[$i]['CouponDisItemWiseDiscout']) && $tempCart[$i]['CouponDisItemWiseDiscout'] > 0)
				{
					$CouponDisItemWiseDiscout = $tempCart[$i]['CouponDisItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['AutoItemWiseDiscout']) && $tempCart[$i]['AutoItemWiseDiscout'] > 0)
				{
					$AutoItemWiseDiscout = $tempCart[$i]['AutoItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['QuantityItemWiseDiscout']) && $tempCart[$i]['QuantityItemWiseDiscout'] > 0)
				{
					$QuantityItemWiseDiscout = $tempCart[$i]['QuantityItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['RewardItemWiseDiscout']) && $tempCart[$i]['RewardItemWiseDiscout'] > 0)
				{
					$RewardItemWiseDiscout = $tempCart[$i]['RewardItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['BogoItemWiseDiscout']) && $tempCart[$i]['BogoItemWiseDiscout'] > 0)
				{
					$BogoItemWiseDiscout = $tempCart[$i]['BogoItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['GiftCertificateItemWiseDiscout']) && $tempCart[$i]['GiftCertificateItemWiseDiscout'] > 0)
				{
					$GiftCertificateItemWiseDiscout = $tempCart[$i]['GiftCertificateItemWiseDiscout'];
				}
				if(!empty($tempCart[$i]['CreditLimitItemWiseDiscout']) && $tempCart[$i]['CreditLimitItemWiseDiscout'] > 0)
				{
					$CreditLimitItemWiseDiscout = $tempCart[$i]['CreditLimitItemWiseDiscout'];
				}

				$ActualPrice = $CouponDisItemWiseDiscout + $AutoItemWiseDiscout + $QuantityItemWiseDiscout + $RewardItemWiseDiscout + $BogoItemWiseDiscout+ $GiftCertificateItemWiseDiscout + $CreditLimitItemWiseDiscout;

				if(!empty($ActualPrice) && $ActualPrice > 0)
				{
					$ActualPrice = $tempCart[$i]['TotPrice'] - $ActualPrice;
					$ActualPrice = NumberFormat($ActualPrice);
				}
				else
				{
					$ActualPrice = 0;
				}

				if(!isset($tempCart[$i]['YotpoFreeGift']))
					$tempCart[$i]['YotpoFreeGift'] = '';

				$OrderDetailInsert = array (
					'orders_id'				=> $OrderID,
					'orders_no'				=> "OR".$OrderID, // To add 'OR' Change on :: 06-10-2015
					'products_id'			=> $tempCart[$i]['ProductID'],
					'sku' 					=> $tempCart[$i]['SKU'],
					'product_name'			=> $tempCart[$i]['ProductName'].'<br>'.$tempCart[$i]['short_description'],
					'quantity' 				=> $tempCart[$i]['Qty'],
					'price' 				=> $tempCart[$i]['Price'],
					'total' 				=> $tempCart[$i]['TotPrice'],
					'status' 				=> '1',
					'item_price' 			=> (isset($tempCart[$i]['ItemPrice']))?$tempCart[$i]['ItemPrice']:0,
					'excluded_flag'  		=> (isset($tempCart[$i]['FinalSale']))?$tempCart[$i]['FinalSale']:'',
					'is_gift_wrap'			=> (isset($tempCart[$i]['gift_wrap']))?$tempCart[$i]['gift_wrap']:'',
					'is_free_gift_products' => (isset($tempCart[$i]['IS_Free_Gift']))?$tempCart[$i]['IS_Free_Gift']:'No',
					'VendorSKU'				=> $tempCart[$i]['VendorSKU'],
					'IsCosmo'				=> $tempCart[$i]['IsCosmo'],
					'IsNandansons'  		=> $tempCart[$i]['IsNandansons'],
					'IsPerfumePW'			=> $tempCart[$i]['IsPerfumePW'],
					'IsPCA'					=> $tempCart[$i]['IsPCA'],
					'coupon_itemwise_discount' => $tempCart[$i]['ItemWiseCouponDiscount'],
					'handling_time_str'		=> 	(isset($tempCart[$i]['HandlingTimeStr']))?$tempCart[$i]['HandlingTimeStr']:'',
					'attribute_info'        => (isset($tempCart[$i]['IsYotpoFreeProduct']))?$tempCart[$i]['IsYotpoFreeProduct']:'No',
					'actual_price'			=> $ActualPrice
				);

				$OrdDetail = OrderDetail::create($OrderDetailInsert);
				$OrderDetailID = $OrdDetail->orders_detail_id;
				if(($tempCart[$i]['IsCosmo']=="Yes" || $tempCart[$i]['IsNandansons']=='Yes' || $tempCart[$i]['IsPerfumePW']=='Yes' || $tempCart[$i]['IsPCA']=="Yes") && $tempCart[$i]['VendorSKU']!='' )
				{
					$IsVender = "Yes";
				}
				if($tempCart[$i]['IsPerfumePW']=='Yes' )
				{
					$IsPerfumePWVendor = "Yes";
				}

				## Insert purchased GC

				$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem',$tempCart[$i]);

				//if($tempCart[$i]['SKU'] == config('global.GIFT_CERTIFICATE_SKU') || $tempCart[$i]['SKU'] == config('global.GIFT_CERTIFICATE_SKU1'))
				if($IsGiftCertificateItem == 'Yes')
				{
					//$AddGC = $this->InsertGiftCertificateDB($tempCart[$i], $OrdDetail->orders_detail_id, $customer_id,$IsAmazOR);
					$AddGC = $this->checkGiftCertificateItem('InsertGiftCertificateInDB', $tempCart[$i], 'Yes', $OrdDetail->orders_detail_id, $customer_id,$IsAmazOR);
				}
			}
		}*/

		$EstimateNewDate = '';

		if ($onlyGCPurchased == 0) {
			$EstimateNewDate = $this->SetEstimateDate($ShippingInfo['ShippingMethodID'], $IsVender, $IsPerfumePWVendor, $Shipping['zip'], $Shipping['state'], $Shipping['country']);
			//echo $EstimateNewDate; exit;
		}
		$cur_date = date("Y-m-d");
		if ($EstimateNewDate == "" && strtotime($EstimateNewDate) < strtotime($cur_date)) {
			$EstimateNewDate = "0000-00-00 00:00:00";
		}

		$updArayVal = array('EstimatedDeliveryDate' => $EstimateNewDate);
		$updOrder22 = $CurrOrder->update($updArayVal);

		if ($OrderID != "" && $IsVender == "Yes") {
			// To add 'OR' Change on :: 06-10-2015
			$updateOrder1 = array('IsVender' => $IsVender);
			$udpRefer1 = $CurrOrder->update($updateOrder1);
		}

		$customerEmail = '';
		if (isset($Billing['email']) && $Billing['email'] != '') {
			//$customerEmail	= "naresh.qualdev@gmail.com";
			$customerEmail	= $Billing['email'];
		}

		$orderNo		= 'OR' . $OrderID;
		$customerName = '';
		if ((isset($Billing['first_name']) && $Billing['first_name'] != '')) {
			$customerName	= $Billing['first_name'] . " ";
		}

		if (isset($Billing['last_name']) && $Billing['last_name']) {
			$customerName = $customerName . $Billing['last_name'];
		}
		Log::info('Before Order Sales Person Entry 4322 '.$OrderID);
		if (Auth::guard('store')->check()) {

			$insertOrderStore = array(
				'orders_id'		=> $OrderID,
				'store_id'		=> Auth::guard('store')->user()->store_id,
				'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
				'added_datetime'	=> date("Y-m-d H:i:s")
			);
			$insertOSId = DB::table('pu_orders_store')->insertGetId($insertOrderStore);
			Log::info('After Order Store Insert - 4332 - '.json_encode($insertOrderStore));

			if (Session::has('ShoppingCart.SalesAgent')) {
				$orderSalesAgents = Session::get('ShoppingCart.SalesAgent');
				Log::info('After Order Store Insert - 4336 - '.json_encode($orderSalesAgents));
				for ($ms = 0; $ms < count($orderSalesAgents); $ms++) {
					$agent_email_id = $orderSalesAgents[$ms]['sales_person_email'];

					$salesPerson = StoreSalesPerson::where('email', $agent_email_id)->where('store_id', Auth::guard('store')->user()->store_id)->where('status', '=', '1')->first();

					if ($salesPerson) {
						$salesPersonId = $salesPerson->sales_person_id;

						$insertOrderSalesPerson = array(
							'orders_id'		=> $OrderID,
							'store_id'		=> Auth::guard('store')->user()->store_id,
							'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
							'sales_person_id'	=> $salesPersonId,
							'email' => $agent_email_id,
							'added_datetime'	=> date("Y-m-d H:i:s"),
							'commision_percentage'	=> $orderSalesAgents[$ms]['sales_person_per']
						);
						$insertOSPId = DB::table('pu_orders_sales_person')->insertGetId($insertOrderSalesPerson);
						Log::info('After Order Store Insert - 4355 - '.json_encode($insertOrderSalesPerson));
					}
				}
			}
		}
		 else {
				Log::info('Order Sales Person Entry auth problem 4361 '.$OrderID);
			}

		$gc_remaining_value = 0;
		if ($GiftCouponInfo && count($GiftCouponInfo) > 0 && isset($GiftCouponInfo["Code"]) && $GiftCouponInfo["Code"] != '') {
			$GiftCouponInfo['Value'] = ($GiftCouponInfo['Value'] != '') ? $GiftCouponInfo['Value'] : 0.00;
			$new_total = $this->GetNetTotal() + $GiftCouponInfo['Value'];
			if ($new_total <= $GiftCouponInfo['Applicable_Value']) {
				$gc_remaining_value = NumberFormat(($GiftCouponInfo['Applicable_Value'] - $new_total));
			}

			if ($GiftCouponInfo['Code'] != '' && $new_total <= $GiftCouponInfo['Applicable_Value']) {
				$str_info  = 'Gift Certificate discount value is greater than order total amount. \n\n';
				$str_info .= 'So net $' . $new_total . ' is deduct from gift certifiacte value. \n\n';
				$str_info .= 'Used Gift Certificate code is (' . $GiftCouponInfo['Code'] . ')';

				$updAray = array('pay_status' 	   => 'Paid', 'transaction_info' => $str_info);
				$updOrder = $CurrOrder->update($updAray);
				return redirect(config('global.SITE_URL') . "order-receipt");
			}
		} else if ($this->GetNetTotal() == 0) {
			$updAray = array('pay_status' => 'Paid');
			$updOrder = $CurrOrder->update($updAray);
			return redirect(config('global.SITE_URL') . "order-receipt");
		}

		if ($request->PaymentMethod == 'STRIPE_GOOGLEPAY') {
			$myFile = env('LOG_BASE_PATH') . 'Logs/Walmart/ApplePayLog.txt';

			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');

				$stringData = date("m/d/Y H:i:s") . " And " . json_encode($OrderInsert) . " : In Shoppingcart Controller.\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
			$StepValue = '';
			if (isset($request->is_step_gpay) && $request->is_step_gpay != '') {
				$StepValue = $request->is_step_gpay;
			}

			$updAray = array(
				'pay_status'   => 'Paid',
				'transaction_info' => serialize($PaymentResponse) . " " . $StepValue
			);

			$updOrder = Order::Where("orders_id", "=", $OrderID)->update($updAray);
			return redirect(config('global.SITE_URL') . 'order-receipt');
		}

		if ($request->PaymentMethod == 'STRIPE_APPLEPAY') {
			$myFile = env('LOG_BASE_PATH') . 'Logs/Walmart/ApplePayLog.txt';

			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');

				$stringData = date("m/d/Y H:i:s") . " And " . json_encode($OrderInsert) . " : In Shoppingcart Controller.\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
			$StepValue = '';
			if (isset($request->is_step_gpay) && $request->is_step_gpay != '') {
				$StepValue = $request->is_step_gpay;
			}

			$updAray = array(
				'pay_status'   => 'Paid',
				'transaction_info' => serialize($PaymentResponse) . " " . $StepValue
			);

			$updOrder = Order::Where("orders_id", "=", $OrderID)->update($updAray);
			return redirect(config('global.SITE_URL') . 'order-receipt');
		}

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALEC') ## Paypal Express payment gateway condition
		{
			$myFile = config('global.PHYSICAL_PATH') . 'Logs/Walmart/PaypalLog.txt';

			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');

				$stringData = date("m/d/Y H:i:s") . " And " . $OrderID . " : Paypal DoPayment Function In Shoppingcart Controller.\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}

			if (isset($OrderID) && $OrderID > 0) {
				return redirect(config('global.SITE_URL') . 'paypal/dopayment/' . $OrderID);
			} else {
				return redirect(config('global.SITE_URL') . 'paypal/dopayment');
			}
		} elseif (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALCC') ## Paypal Do direct payment gateway condition
		{
			//header("location:".$SECURED_PATH."paypal_checkout/paypal_dodirect_payment.php");
			//exit;
		} elseif (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_STRIPE') ## Braintree payment gateway condition
		{
			// echo config('global.PHYSICAL_PATH');
			// exit;
			//$myFile = '/home/maxaroma/public_html/Logs/Walmart/StripeLog.txt';
			$myFile = config('global.PHYSICAL_PATH') . 'Logs/Walmart/StripeLog.txt';

			if (fopen($myFile, 'a+')) {
				$fh = fopen($myFile, 'a+');

				$stringData = date("m/d/Y H:i:s") . " And " . $OrderID . " : Stripe Payment In Shoppingcart Controller.\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
			return redirect(config('global.SITE_URL') . 'stripe/placeorder/' . $OrderID);
			exit;
		} elseif (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_AUTHORIZENETCC') ## AUTHORIZE payment gateway condition
		{
			//header("location:".$SECURED_PATH."authorize_checkout/payment_authorize.php");
			//exit;
		} elseif (isset($arrPaymentDetail['Payment_Type']) && ($arrPaymentDetail['Payment_Type'] == 'PAYMENT_MOC' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_WT' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_CL' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_DS' || $arrPaymentDetail['Payment_Type'] == 'PAYMENT_GIFT_CERTIFICATE')) ## Other payment gateway condition
		{
			addLog("PlaceOrder", $log);
			return redirect(config('global.SITE_URL') . 'order-receipt');
			exit;
		} else if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYWITHAFTERPAY') { ## Afterpay payment gateway condition
			if (isset($request->is_btm_ap_chkout) && $request->is_btm_ap_chkout == "Yes") {	//from the bottom express checkout button
				addLog("PlaceOrder", $log);
				return redirect(config('global.SITE_URL') . 'afterpay/dopayment_express_btm/' . $OrderID . '~' . $customer_id);
				exit;
			} else {
				$ap_psChecksum = $request->ap_psChecksum;

				if (empty($ap_psChecksum) && $ap_psChecksum == '') {
					Session::flash('PlaceOrderError', 'Error in Processing Request. Please try again.');
					$log['msg'] = 'Error in Processing Request. Please try again.';
					addLog("PlaceOrder", $log);

					$transaction_info = "This transaction has been Declined.";
					$updAray = array(
						'status' 	   				=> 'Declined',
						'transaction_info' 			=> $transaction_info
					);

					$uporderres = Order::Where("orders_id", "=", $OrderID)
						->update($updAray);

					$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e', $Data);

					/*$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e',$Data);

					$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
					OmanisendRequest('63c113f03d897d0020e8b31e',$Data);*/

					Session::forget('ShoppingCart.AfterPay.Checkout_Token');
					return redirect('checkout');
					exit;
				} else {
					return redirect(config('global.SITE_URL') . 'afterpay/success_express/' . $ap_psChecksum . '/' . $OrderID . '~' . $customer_id);
					exit;
				}
				//header("Location:" . $SECURED_PATH . "PayWithAfterpay/afterpay_checkout.php");
				//exit();
			}
		} elseif (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYWITHAMAZON') {
			return redirect(config('global.SITE_URL') . 'amazon/placeorder');
			exit;
		} elseif (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_STRIPE_BUTTON') {
			return redirect(config('global.SITE_URL') . 'order-receipt');
			exit;
		}
		#### Here check payment type and do processing end ####
		if ($OrderID > 0) {
			$updArayVAL = array('pay_status' => 'Unpaid', 'status' => 'Declined');
			$uporderres11 = $CurrOrder->update($updArayVAL);

			$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
			OmanisendRequest('63c113f03d897d0020e8b31e', $Data);

			/*$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
			OmanisendRequest('63c113f03d897d0020e8b31e',$Data);

			$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
			OmanisendRequest('63c113f03d897d0020e8b31e',$Data);*/
		}
		return redirect('/');
	}

	public function StoreOrderPlace(Request $request, $WebsiteOrder = "No")
	{

		$CreditData = $this->GetCreditLimitAmount();
		$CreditAmt = $CreditData['CreditLimit'];
		$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
		$cust_current_credit_limit = $CreditAmt;
		$apply_credit = $CreditDiscount;
		$remaining_credit = $CreditData['RemainCreditLimit'];

		if ($CreditDiscount > 0) {
			$use_credit_limit = 'Yes';
		} else {
			$use_credit_limit = 'No';
		}

		//Cre
		$is_amazon = 0;
		if ($this->GetNetTotal() <= 0 && $CreditDiscount > 0 && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && Session::get("etype") == "M" && Session::get("sess_icustomerid") > 0 && Session::get("is_dropshipper") != "Yes") {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod  = "PAYMENT_CL";
		}

		if ($this->GetNetTotal() <= 0 && $CreditDiscount <= 0 ) {

			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			//$request->PaymentMethod = 'PAYMENT_GIFT_CERTIFICATE';
			$request->PaymentMethod = 'PAYMENT_FREEITEM';
		}

		$this->setPaymentDetail($request);

		if ($this->GetNetTotal() <= 0) {
			$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
			$GCAmount = 0;
			$GCCode = "";
			$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');
			if ($GiftCoupon) {
				$GCAmount = $GiftCoupon['Value'];
				$GCCode = $GiftCoupon['Code'];
			}
			$log['GiftCoupon'] = json_encode($GiftCoupon);
			$log['arrPaymentDetail'] = json_encode($arrPaymentDetail);
			if (empty($GCCode) && $GCCode == '' && isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] != '' && $arrPaymentDetail['Payment_Type'] != 'PAYMENT_CL'  && $arrPaymentDetail['Payment_Type'] != 'PAYMENT_FREEITEM') {
				$err_msg = "Order total is invalid, please try again.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				return redirect('/shoppingcart');
			}
		}

		$customer_id = (int)Session::get('sess_icustomerid');

		if (!Session::has('sess_icustomerid') || empty($customer_id)) {
			$err_msg = "Error while processing your order, Please try again.";
			$log['err_msg'] = $err_msg;
			addLog("PlaceOrder", $log);
			Session::flash('PlaceOrderError', $err_msg);
			Log::info('PlaceOrderError: Customer Not Found');
			return redirect()->back();
		}

		$Shipping = Session::get('ShoppingCart.ShippingAddress');
		$log['Shipping'] = $Shipping;
		addLog("PlaceOrder", $log);
		$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');

		if ($onlyGCPurchased == 0 && $WebsiteOrder == "Yes") {

			if (((isset($Shipping['first_name']) && trim($Shipping['first_name']) == '') || (isset($Shipping['last_name']) &&	trim($Shipping['last_name']) == '') || (isset($Shipping['address1']) && trim($Shipping['address1']) == '') || (isset($Shipping['city']) && trim($Shipping['city']) == '') || (isset($Shipping['zip']) && trim($Shipping['zip']) == '') || (isset($Shipping['state']) && trim($Shipping['state']) == '') || (isset($Shipping['country']) && trim($Shipping['country']) == ''))) {
				$err_msg = "Please fill the required fields for shipping information. ";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: ShippingAddress Validation');
				return redirect()->back();
			}
		}

		if ($onlyGCPurchased == 1) {
			Session::put('ShoppingCart.BillingAsShipping', 'Yes');
			$Billing['sameasbill'] = 'Yes';
			$this->SetShippingAddress($Billing);
			$Shipping = Session::get('ShoppingCart.ShippingAddress');
		}

		$Billing  = Session::get('ShoppingCart.BillingAddress');

		if ((trim($Billing['first_name']) == '' || trim($Billing['last_name']) == '' || trim($Billing['address1']) == '' || trim($Billing['city']) == '' ||
			trim($Billing['zip']) == '' || trim($Billing['state']) == '' || trim($Billing['country']) == '' || trim($Billing['phone']) == '' ||
			trim($Billing['email']) == '')) {
			$err_msg = "Please fill the required fields for billing information2. ";
			$log['Billing'] = json_encode($Billing);
			$log['err_msg'] = $err_msg;
			addLog("PlaceOrder", $log);
			Session::flash('PlaceOrderError', $err_msg);
			Log::info('PlaceOrderError: BillingAddress Validation');
			return redirect()->back();
		}

		$Shipping = Session::get('ShoppingCart.ShippingAddress');
		$log['Shipping'] = $Shipping;
		addLog("PlaceOrder", $log);
		$onlyGCPurchased = $this->GetCartAttribute('onlyGCPurchased');

		Session::put('ShoppingCart.BillingAsShipping', 'Yes');
		$Billing['sameasbill'] = 'Yes';
		$this->SetShippingAddress($Billing);
		$Shipping = Session::get('ShoppingCart.ShippingAddress');

		$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');

		if (Session::has('ShoppingCart.Payment_Detail')) {

			if ((isset($arrPaymentDetail['Payment_Type']) && trim($arrPaymentDetail['Payment_Type']) == '')) {
				$err_msg =  "Please choose payment method.";
				$log['err_msg'] = $err_msg;
				addLog("PlaceOrder", $log);
				Session::flash('PlaceOrderError', $err_msg);
				Log::info('PlaceOrderError: Payment Method Not Selected');
				return redirect()->back();
			}
		}

		$ShippingInfo = "";
		if (Session::has('ShoppingCart.Shipping') && Session::get('ShoppingCart.Shipping') != '') {
			$ShippingInfo = Session::get('ShoppingCart.Shipping');
		}

		$currency_info = Session::get('currency_code') . "#" . Session::get('currency_symbol') . "#" . Session::get('currency_rate');
		if (Session::has('etype') && Session::get('etype') == 'M')
			$checkout_type = 'M';
		else
			$checkout_type = 'G';

		$cc_info = "";

		//$ShippingInfo	 = Session::get('ShoppingCart.Shipping');
		$GiftCouponInfo  = Session::get('ShoppingCart.GiftCoupon');
		$CouponCode = $this->GetAllCoupons('CouponCode');

		$couponRS = array();
		$OneCouponCode = "";
		$coupon_id = '';
		$SecondCouponCode = "";
		$Second_coupon_id = "";

		$yotporewardcode = '';

		if (Session::has('ShoppingCart.YotpoRewardCode') && Session::get('ShoppingCart.YotpoRewardCode') != '') {
			$yotporewardcode = Session::get('ShoppingCart.YotpoRewardCode');
		}

		if (!empty($CouponCode)) {
			$couponRS = Coupon::where('coupon_number', '=', $CouponCode)->limit(1)->get();
			if ($couponRS && $couponRS->count() > 0) {
				$OneCouponCode = $couponRS[0]->coupon_number;
				$coupon_id = $couponRS[0]->coupon_id;
			}
		}

		## Wholesaler Field Start ###
		$w_user_type = Session::get('eusertype');
		$w_ilevelid  = (Session::has('ilevelid')) ? Session::get('ilevelid') : 0;
		## Wholesaler Field End ###

		$GiftValue = $this->FreeGiftValue($this->GetNetTotal());

		if (count($GiftValue) > 0) {
			$free_gift = Session::get('ShoppingCart.FreeGift');
			$gift_from = Session::get('ShoppingCart.GiftFrom');
			$gift_to   = Session::get('ShoppingCart.GiftTo');
			$gift_message_customer = Session::get('ShoppingCart.GiftMessageCustomer');
		} else {
			$free_gift = '';
			$gift_from = '';
			$gift_to   = '';
			$gift_message_customer = (isset($request->gift_message_customer)) ? $request->gift_message_customer : '';
			if (strtolower(trim($gift_message_customer ?? '')) == " *gift message") {
				$gift_message_customer = '';
			}
			Session::forget('ShoppingCart.FreeGift');
			Session::forget('ShoppingCart.GiftFrom');
			Session::forget('ShoppingCart.GiftTo');
			Session::forget('ShoppingCart.GiftMessageCustomer');
		}

		//Credit Limit Code End

		if (isset($arrPaymentDetail['Payment_Type']) && $arrPaymentDetail['Payment_Type'] == 'PAYMENT_DS')
			$is_dropship_order = 'Yes';
		else
			$is_dropship_order = 'No';

		if (isset($request->customer_comment) && strtolower(trim($request->customer_comment)) == "special request") {
			$request->customer_comment = "";
		}

		$referDiscountId = (Session::has('ShoppingCart.ReferDiscountId')) ? Session::get('ShoppingCart.ReferDiscountId') : 0;

		$merge_note = (Session::has('ShoppingCart.merge_note')) ? Session::get('ShoppingCart.merge_note') : "";
		$Payment_Type = (isset($arrPaymentDetail['Payment_Type'])) ? $arrPaymentDetail['Payment_Type'] : '';
		$Payment_Method = (isset($arrPaymentDetail['Payment_Method'])) ? $arrPaymentDetail['Payment_Method'] : '';

		$PaymentResponse = "";

		$customer_comment = (isset($request->customer_comment)) ? $request->customer_comment : '';
		$SubTotal = (float)Session::get('ShoppingCart.SubTotal');
		$GiftCoupon = $this->GetAllCoupons('GiftCoupon');
		$GCAmount = 0;
		$GCCode = "";
		if ($GiftCoupon) {
			$GCAmount = $GiftCoupon['Value'];
			$GCCode = $GiftCoupon['Code'];
		}

		$paymentintentid = "";

		$ShippingSignatureFlag = 'No';
		if ($onlyGCPurchased == 0 && isset($request->shipsignatureflag) && $request->shipsignatureflag == 'Yes' && $WebsiteOrder == "Yes")
			$ShippingSignatureFlag = 'Yes';

		if ($onlyGCPurchased == 0 && isset($request->shipping_signature) && $request->shipping_signature == 'Yes' && $WebsiteOrder == "Yes")
			$ShippingSignatureFlag = 'Yes';

		$SubTotal = Session::get('ShoppingCart.SubTotal');

		$fullShippingname = '';
		$is_maxtwoday = "No";
		$EstimatedDeliveryDate = '';
		$ShipMethodName = '';
		$ShipMethodCharge = 0;
		$OrdShipSignature = 0;
		$OrdShipInsurance = 0;
		if ($onlyGCPurchased == 0 && $WebsiteOrder == "Yes") {
			if ($ShippingInfo["ShippingCharge"] > 0) {
				$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(" . Session::get('currency_symbol') . $ShippingInfo["ShippingCharge"] . ")</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
			} else {
				$fullShippingname =  $ShippingInfo["ShippingMethodName"] . " <b>(Free)</b> " . Session::get('ShoppingCart.Shipping.ShippingDays');
			}
			if (strtolower($ShippingInfo['ShippingMethodName'] ?? '') == 'max2days') {
				$is_maxtwoday = "Yes";
			}
			$EstimatedDeliveryDate = Session::get("ShoppingCart.EstimatedDeliveryDate");
			$ShipMethodName = $ShippingInfo['ShippingMethodName'];
			$ShipMethodCharge = $this->GetShippingCharge();
			$OrdShipSignature = $this->GetAllCharges('ShippingSignature');
			$OrdShipInsurance = $this->GetAllCharges('ShippingInsurance');
		}

		$cur_date = date("Y-m-d");
		if ($EstimatedDeliveryDate == "" && strtotime($EstimatedDeliveryDate) < strtotime($cur_date)) {
			$EstimatedDeliveryDate = "0000-00-00 00:00:00";
		}

		$SkipAddressPart = '';

		if (Session::get("BillingSkipVariable") === "Yes") {
			$SkipAddressPart .= "Yes";
		}

		if (Session::get("BillingSkipEmail") === "Yes") {
			$SkipAddressPart .= "###Yes";
		}

		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			$CartArray = Session::get('ShoppingCart.Cart');
		}

		$OrderInsert = array(
			'customer_id'		=> $customer_id,
			'sub_total' 		=> $SubTotal,
			'shipping_amt' 		=> $ShipMethodCharge,
			'tax' 				=> $this->GetAllCharges('Tax'),
			'gift_charge' 		=> $this->GetAllCharges('GiftWrappingCharge'),
			'gift_message' 		=> '',
			'is_gift_order'		=> 'No',
			'handling_charge' 	=> '0.00',
			'wire_discount' 	=> '0.00',
			'auto_discount' 	=> $this->GetAllDiscounts('AutoDiscount'),
			'quantity_discount'	=> $this->GetAllDiscounts('QuantityDiscount'),
			'reward_discount'	=> $this->GetAllDiscounts('YotpoRewardDiscount'),
			'coupon_amount' 	=> $this->GetAllDiscounts('CouponDiscount'),
			'coupon_id' 		=> $coupon_id,
			'Second_coupon_id'	=> $yotporewardcode,
			'coupon_code' 		=> $CouponCode,
			'gc_amount' 		=> $this->GetAllDiscounts('GiftCoupon'),
			'gc_code' 			=> $GCCode,
			'order_total' 		=> $this->GetNetTotal(),
			'shipinfo' 			=> $ShipMethodName,
			'payment_type' 		=> $Payment_Type,
			'payment_method' 	=> $Payment_Method,
			'pay_status' 		=> 'Unpaid',
			'ccinfo' 			=> $cc_info,
			'customer_comment' 	=> $customer_comment,
			'status'			=> 'Pending',
			'currency_info'		=> $currency_info,
			'checkout_type' 	=> $checkout_type,
			'user_type' 		=> $w_user_type,
			'ilevelid' 			=> $w_ilevelid,
			//'level_price' 		=> $w_level_price,
			'ship_first_name' 	=> isset($Shipping['first_name']) ? $Shipping['first_name'] : '', //$Shipping['first_name'],
			'ship_last_name' 	=> isset($Shipping['last_name']) ? $Shipping['last_name'] : '', //$Shipping['last_name'],
			'ship_company' 		=> isset($Shipping['company']) ? $Shipping['company'] : '', //$Shipping['company'],
			'ship_email' 		=> isset($Shipping['email']) ? $Shipping['email'] : '', //$Shipping['email'],
			'ship_address1' 	=> isset($Shipping['address1']) ? $Shipping['address1'] : '', //$Shipping['address1'],
			'ship_address2' 	=> isset($Shipping['address2']) ? $Shipping['address2'] : '', //$Shipping['address2'],
			'ship_city' 		=> isset($Shipping['city']) ? $Shipping['city'] : '', //$Shipping['city'],
			'ship_zip' 			=> isset($Shipping['zip']) ? $Shipping['zip'] : '', //$Shipping['zip'],
			'ship_state' 		=> isset($Shipping['state']) ? $Shipping['state'] : '', //$Shipping['state'],
			'ship_country' 		=> isset($Shipping['country']) ? $Shipping['country'] : '', //$Shipping['country'],
			'ship_phone' 		=> isset($Shipping['phone']) ? $Shipping['phone'] : '', //$Shipping['phone'],
			'bill_first_name' 	=> isset($Billing['first_name']) ? $Billing['first_name'] : '', //$Billing['first_name'],
			'bill_last_name' 	=> isset($Billing['last_name']) ? $Billing['last_name'] : '', //$Billing['last_name'],
			'bill_company' 		=> isset($Billing['company']) ? $Billing['company'] : '', //$Billing['company'],
			'bill_email' 		=> isset($Billing['email']) ? $Billing['email'] : '', //$Billing['email'],
			'bill_address1' 	=> isset($Billing['address1']) ? $Billing['address1'] : '', //$Billing['address1'],
			'bill_address2' 	=> isset($Billing['address2']) ? $Billing['address2'] : '', //$Billing['address2'],
			'bill_city' 		=> isset($Billing['city']) ? $Billing['city'] : '', //$Billing['city'],
			'bill_zip' 			=> isset($Billing['zip']) ? $Billing['zip'] : '', //$Billing['zip'],
			'bill_state' 		=> isset($Billing['state']) ? $Billing['state'] : '', //$Billing['state'],
			'bill_country' 		=> isset($Billing['country']) ? $Billing['country'] : '', //$Billing['country'],
			'bill_phone' 		=> isset($Billing['phone']) ? $Billing['phone'] : '', ////$Billing['phone'],
			'customer_ip' 		=> $_SERVER['REMOTE_ADDR'],
			'customer_browser' 	=> $_SERVER['HTTP_USER_AGENT'],
			'is_only_gc'		=> (string)$onlyGCPurchased,
			'free_gift'			=> $free_gift,
			//======= Added Code Date 09/02/2015 Start Here ===//
			'gift_from'				=> $gift_from,
			'gift_to'				=> $gift_to,
			'gift_message_customer'	=> $gift_message_customer,
			'cust_current_credit_limit' => $cust_current_credit_limit,
			'apply_credit'          => $apply_credit,
			'remaining_credit'      => $remaining_credit,
			'use_credit_limit'      => $use_credit_limit,
			'is_dropship_order'     => $is_dropship_order,
			'shipping_signature'	 => $OrdShipSignature,
			'is_shipping_signature' => $ShippingSignatureFlag,
			'Is_GiftCertificatPurchase' => $this->GetCartAttribute('CheckGCPurchasedVal'),
			'EstimatedDeliveryDate' 	=> $EstimatedDeliveryDate,
			'fullshipping_info'		=> 	$fullShippingname,
			'merge_note'		=> 	$merge_note,
			'bogo_discount'	=> $this->GetAllDiscounts('DogoDiscount'),
			'is_maxtwoday'	=> $is_maxtwoday,
			'route_shipping_insurance_charge' => $OrdShipInsurance,
			'vLang_flag' => Session::get('ShoppingCart.YotpoFreeGiftCoupon'),
			'paymentintentid' => $paymentintentid,
			'payment_gateway_response' => Session::get("PayMethodRes") ? Session::get("PayMethodRes") : '',
			'BraintreeResponse'        => '',
			'order_type'					=> "Store",
			'is_skip_addresspart'		 => $SkipAddressPart
		);

		if (Auth::guard('store')->check()) {
			$store = Auth::guard('store')->user();
			$OrderInsert['store_id']   =  $store->store_id;

			$OrderNewType = "Store";
			if ($WebsiteOrder == "Yes") {
				$OrderNewType = "Website";
			}
			$OrderInsert['order_type']   =  $OrderNewType;
		}

		$agent_email_id = '';
		$salesPersonId = 0;
		if (Auth::guard('store')->check()) {
			$agent_email_id = Auth::guard('store')->user()->store_user_email;
			$salesPersonId = Auth::guard('store')->user()->store_user_id;
			$OrderInsert['sales_person_id'] = $salesPersonId;
		}

		$is_order_update = 'N';

		$log['PlaceOrderInsert'] = json_encode($OrderInsert);

		//echo "<pre>"; print_r($OrderInsert); exit;
		addLog("PlaceOrder", $log);
		$PlaceOrder = Order::create($OrderInsert);
		$OrderID = $PlaceOrder->orders_id;

		$aa = Session::put('ShoppingCart.OrderID', $OrderID);

		$myFile = env('LOG_BASE_PATH') . 'Logs/ApplePayOtherLog.txt';

		if (fopen($myFile, 'a+')) {
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			if (Session::has('ShoppingCart') && Session::get('ShoppingCart') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Data : " . json_encode(Session::get('ShoppingCart')) . " :\n";
			}
			if (isset($OrderInsert) && !empty($OrderInsert)) {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Order Data : " . json_encode($OrderInsert) . " :\n";
			}
			if (Session::has('sess_useremail') && Session::get('sess_useremail') != '') {
				$stringData .= date("m/d/Y H:i:s") . " Regarding Gift Certificate Place order after order insert Session User Email : " . Session::get('sess_useremail') . " :\n";
			}
			fwrite($fh, $stringData);
			fclose($fh);
		}

		if ($OrderID != "") {
			// To add 'OR' Change on :: 06-10-2015
			$Prefix = GetStorePrefix();
			if($OrderNewType == 'Website')
				$Prefix = "OR";
			$CurrOrder = Order::find($OrderID);
			$updateOrder = array('orders_no'	 => $Prefix . $OrderID);
			$udpRefer = $CurrOrder->update($updateOrder);

			$UpdateCustomer = ['sf_accountid' => $store->store_id];
			Log::info($UpdateCustomer);
			$cust_upd = Customer::where('customer_id', '=', $customer_id)->update($UpdateCustomer);
		//Update sf_accountid for Customer

		}

		$tempCart = Session::get('ShoppingCart.Cart');
		$cnt_row  = count($tempCart);

		$IsVender = "No";
		$IsAmazOR = 'No';

		$IsPerfumePWVendor = "No";
		$TaxValueNew = $this->GetAllCharges('TaxValue');
		if ($is_order_update == 'N') {
			
			
				$couponCode = $this->GetAllCoupons('CouponCode');
			    $CouponDiscount = $this->GetAllDiscounts('CouponDiscount');

				
				$TotalTxShipping = 0;
				$couponPercentage = 0;
				if (isset($couponCode) && $couponCode != '' && $CouponDiscount > 0 && Session::has('ShoppingCart.CountShipTax') && Session::get('ShoppingCart.CountShipTax')=='1' && Session::has('ShoppingCart.CouponPercentage'))
				{
					$couponPercentage = session::get('ShoppingCart.CouponPercentage');
									 	
				}
			
			$ShoppingcartController = app()->make(ShoppingcartController::class);
			for ($i = 0; $i < $cnt_row; $i++) {
				
					$ItemWiseTaxVal = ($TaxValueNew > 0)
					? NumberFormat($ShoppingcartController->ItemWiseTax($tempCart[$i]['TotPrice'], $i))
					: 0;
					$allocatedDiscount  = 0;
					if (isset($couponCode) && $couponCode != '' && $CouponDiscount > 0 && Session::has('ShoppingCart.CountShipTax') && Session::get('ShoppingCart.CountShipTax')=='1' && Session::has('ShoppingCart.CouponPercentage'))
					{
						
						
						
						$ShippingChargeItemWise = ($this->GetShippingCharge() > 0  && $SubTotal > 0)
							? NumberFormat(($tempCart[$i]["TotPrice"] * $this->GetShippingCharge()) / $SubTotal)
							: 0;	
						$ItemWiseTaxShipping = NumberFormat($ItemWiseTaxVal + $ShippingChargeItemWise);
						$allocatedDiscount      = $ItemWiseTaxShipping * ($couponPercentage / 100);
						
						$allocatedDiscount      = NumberFormat($allocatedDiscount);
						
					}
					
					

					$tempCart[$i]['TaxShippingItemWiseDiscount'] = $allocatedDiscount;
						
				
				
				if (empty($this->GetAllDiscounts('CouponDiscount')) || $this->GetAllDiscounts('CouponDiscount') <= 0) {
					$tempCart[$i]['CouponDisItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('AutoDiscount')) || $this->GetAllDiscounts('AutoDiscount') <= 0) {
					$tempCart[$i]['AutoItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('QuantityDiscount')) || $this->GetAllDiscounts('QuantityDiscount') <= 0) {
					$tempCart[$i]['QuantityItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('YotpoRewardDiscount')) || $this->GetAllDiscounts('YotpoRewardDiscount') <= 0) {
					$tempCart[$i]['RewardItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('DogoDiscount')) || $this->GetAllDiscounts('DogoDiscount') <= 0) {
					$tempCart[$i]['BogoItemWiseDiscout'] = 0;
				}
				if (empty($this->GetAllDiscounts('GiftCoupon')) || $this->GetAllDiscounts('GiftCoupon') <= 0) {
					$tempCart[$i]['GiftCertificateItemWiseDiscout'] = 0;
				}
				if (empty($apply_credit) && $apply_credit <= 0) {
					$tempCart[$i]['CreditLimitItemWiseDiscout'] = 0;
				}
				$ActualPrice = 0;
				$CouponDisItemWiseDiscout = 0;
				$AutoItemWiseDiscout = 0;
				$QuantityItemWiseDiscout = 0;
				$RewardItemWiseDiscout = 0;
				$BogoItemWiseDiscout = 0;
				$GiftCertificateItemWiseDiscout = 0;
				$CreditLimitItemWiseDiscout = 0;

				if (!empty($tempCart[$i]['CouponDisItemWiseDiscout']) && $tempCart[$i]['CouponDisItemWiseDiscout'] > 0) {
					$CouponDisItemWiseDiscout = $tempCart[$i]['CouponDisItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['AutoItemWiseDiscout']) && $tempCart[$i]['AutoItemWiseDiscout'] > 0) {
					$AutoItemWiseDiscout = $tempCart[$i]['AutoItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['QuantityItemWiseDiscout']) && $tempCart[$i]['QuantityItemWiseDiscout'] > 0) {
					$QuantityItemWiseDiscout = $tempCart[$i]['QuantityItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['RewardItemWiseDiscout']) && $tempCart[$i]['RewardItemWiseDiscout'] > 0) {
					$RewardItemWiseDiscout = $tempCart[$i]['RewardItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['BogoItemWiseDiscout']) && $tempCart[$i]['BogoItemWiseDiscout'] > 0) {
					$BogoItemWiseDiscout = $tempCart[$i]['BogoItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['GiftCertificateItemWiseDiscout']) && $tempCart[$i]['GiftCertificateItemWiseDiscout'] > 0) {
					$GiftCertificateItemWiseDiscout = $tempCart[$i]['GiftCertificateItemWiseDiscout'];
				}
				if (!empty($tempCart[$i]['CreditLimitItemWiseDiscout']) && $tempCart[$i]['CreditLimitItemWiseDiscout'] > 0) {
					$CreditLimitItemWiseDiscout = $tempCart[$i]['CreditLimitItemWiseDiscout'];
				}
				$ActualPrice = $CouponDisItemWiseDiscout + $AutoItemWiseDiscout + $QuantityItemWiseDiscout + $RewardItemWiseDiscout + $BogoItemWiseDiscout + $GiftCertificateItemWiseDiscout + $CreditLimitItemWiseDiscout;

				if (!empty($ActualPrice) && $ActualPrice > 0) {
					$ActualPrice = $tempCart[$i]['TotPrice'] - $ActualPrice;
					$ActualPrice = NumberFormat($ActualPrice);
				} else {
					$ActualPrice = 0;
				}

				
				//$TotalItemTaxAmount = $tempCart[$i]['TotalItemTaxAmount']; //$ShoppingcartController->itemTaxAmount($ActualPrice,$tempCart[$i]['TotPrice']);
				
				if (!isset($tempCart[$i]['YotpoFreeGift']))
					$tempCart[$i]['YotpoFreeGift'] = '';

				$OrderDetailInsert = array(
					'orders_id'				=> $OrderID,
					'orders_no'				=> $Prefix . $OrderID, // To add 'OR' Change on :: 06-10-2015
					'products_id'			=> $tempCart[$i]['ProductID'],
					'sku' 					=> $tempCart[$i]['SKU'],
					'product_name'			=> $tempCart[$i]['ProductName'] . '<br>' . $tempCart[$i]['short_description'],
					'quantity' 				=> $tempCart[$i]['Qty'],
					'price' 				=> $tempCart[$i]['Price'],
					'total' 				=> $tempCart[$i]['TotPrice'],
					'status' 				=> '1',
					'item_price' 			=> (isset($tempCart[$i]['ItemPrice'])) ? $tempCart[$i]['ItemPrice'] : 0,
					'excluded_flag'  		=> (isset($tempCart[$i]['FinalSale'])) ? $tempCart[$i]['FinalSale'] : '',
					'is_gift_wrap'			=> (isset($tempCart[$i]['gift_wrap'])) ? $tempCart[$i]['gift_wrap'] : '',
					'is_free_gift_products' => (isset($tempCart[$i]['IS_Free_Gift'])) ? $tempCart[$i]['IS_Free_Gift'] : 'No',
					'VendorSKU'				=> $tempCart[$i]['VendorSKU'],
					'IsCosmo'				=> $tempCart[$i]['IsCosmo'],
					'IsNandansons'  		=> $tempCart[$i]['IsNandansons'],
					'IsPerfumePW'			=> $tempCart[$i]['IsPerfumePW'],
					'IsPCA'					=> $tempCart[$i]['IsPCA'],
					'IsND'					=> $tempCart[$i]['IsND'],
					'coupon_itemwise_discount' => $tempCart[$i]['ItemWiseCouponDiscount'],
					'handling_time_str'		=> (isset($tempCart[$i]['HandlingTimeStr'])) ? $tempCart[$i]['HandlingTimeStr'] : '',
					'attribute_info'        => (isset($tempCart[$i]['IsYotpoFreeProduct'])) ? $tempCart[$i]['IsYotpoFreeProduct'] : 'No',
					'actual_price'			=> $ActualPrice,
					'item_type'				=>	$tempCart[$i]['OrderType'],
					'item_tax_amount'		=> $ItemWiseTaxVal,
					'sf_orderitemid'		=> $tempCart[$i]['TaxShippingItemWiseDiscount'] ?? 0
				);
				$log['OrderDetailInsert'] = json_encode($OrderDetailInsert);
				addLog("PlaceOrder", $log);
				$OrdDetail = OrderDetail::create($OrderDetailInsert);

				$OrderDetailID = $OrdDetail->orders_detail_id;

				if (($tempCart[$i]['IsCosmo'] == "Yes" || $tempCart[$i]['IsNandansons'] == 'Yes' || $tempCart[$i]['IsPerfumePW'] == 'Yes' || $tempCart[$i]['IsPCA'] == "Yes" || $tempCart[$i]['IsND'] == "Yes") && $tempCart[$i]['VendorSKU'] != '') {
					$IsVender = "Yes";
				}
				if ($tempCart[$i]['IsPerfumePW'] == 'Yes') {
					$IsPerfumePWVendor = "Yes";
				}

				$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem', $tempCart[$i]);

				if ($IsGiftCertificateItem == 'Yes') {
					$AddGC = $this->checkGiftCertificateItem('InsertGiftCertificateInDB', $tempCart[$i], 'Yes', $OrdDetail->orders_detail_id, $customer_id, $IsAmazOR);
				}
			}
		}

		$EstimateNewDate = '';

		$cur_date = date("Y-m-d");
		if ($EstimateNewDate == "" && strtotime($EstimateNewDate) < strtotime($cur_date)) {
			$EstimateNewDate = "0000-00-00 00:00:00";
		}

		$updArayVal = array('EstimatedDeliveryDate' => $EstimateNewDate);
		$updOrder22 = $CurrOrder->update($updArayVal);

		if ($OrderID != "" && $IsVender == "Yes") {
			// To add 'OR' Change on :: 06-10-2015
			$updateOrder1 = array('IsVender' => $IsVender);
			$udpRefer1 = $CurrOrder->update($updateOrder1);
		}

		$customerEmail = '';
		if (isset($Billing['email']) && $Billing['email'] != '') {
			//$customerEmail	= "naresh.qualdev@gmail.com";
			$customerEmail	= $Billing['email'];
		}

		$orderNo		= $Prefix . $OrderID;
		$customerName = '';
		if ((isset($Billing['first_name']) && $Billing['first_name'] != '')) {
			$customerName	= $Billing['first_name'] . " ";
		}

		if (isset($Billing['last_name']) && $Billing['last_name']) {
			$customerName = $customerName . $Billing['last_name'];
		}
		Log::info('Before Order Sales Person Entry 5121 '.$OrderID);
		if (Auth::guard('store')->check()) {

			$insertOrderStore = array(
				'orders_id'		=> $OrderID,
				'store_id'		=> Auth::guard('store')->user()->store_id,
				'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
				'added_datetime'	=> date("Y-m-d H:i:s")
			);
			$insertOSId = DB::table('pu_orders_store')->insertGetId($insertOrderStore);
			Log::info('After Order Store Insert - 5131 - '.json_encode($insertOrderStore));

			if (Session::has('ShoppingCart.SalesAgent')) {
				$orderSalesAgents = Session::get('ShoppingCart.SalesAgent');
				Log::info('After Order Store Insert - 5135 - '.json_encode($orderSalesAgents));
				for ($ms = 0; $ms < count($orderSalesAgents); $ms++) {
					$agent_email_id = $orderSalesAgents[$ms]['sales_person_email'];

					$salesPerson = StoreSalesPerson::where('email', $agent_email_id)->where('store_id', Auth::guard('store')->user()->store_id)->where('status', '=', '1')->first();

					if ($salesPerson) {
						$salesPersonId = $salesPerson->sales_person_id;

						$insertOrderSalesPerson = array(
							'orders_id'		=> $OrderID,
							'store_id'		=> Auth::guard('store')->user()->store_id,
							'store_user_id'	=> Auth::guard('store')->user()->store_user_id,
							'sales_person_id'	=> $salesPersonId,
							'email' => $agent_email_id,
							'added_datetime'	=> date("Y-m-d H:i:s"),
							'commision_percentage'	=> $orderSalesAgents[$ms]['sales_person_per']
						);
						$insertOSPId = DB::table('pu_orders_sales_person')->insertGetId($insertOrderSalesPerson);
						Log::info('After Order Store Insert - 5154 - '.json_encode($insertOrderSalesPerson));
					}
				}
			}
		}
		 else {
				Log::info('Order Sales Person Entry auth problem 2092 '.$OrderID);
			}

		$gc_remaining_value = 0;
		$GiftCouponInfo = $this->GetAllCoupons('GiftCoupon');

		if ($GiftCouponInfo && count($GiftCouponInfo) > 0 && isset($GiftCouponInfo["Code"]) && $GiftCouponInfo["Code"] != '') {

			$GiftCouponInfo['Value'] = ($GiftCouponInfo['Value'] != '') ? $GiftCouponInfo['Value'] : 0.00;
			$new_total = $this->GetNetTotal() + $GiftCouponInfo['Value'];
			if ($new_total <= $GiftCouponInfo['Applicable_Value']) {
				$gc_remaining_value = NumberFormat(($GiftCouponInfo['Applicable_Value'] - $new_total));
			}

			if ($GiftCouponInfo['Code'] != '' && $new_total <= $GiftCouponInfo['Applicable_Value']) {
				$str_info  = 'Gift Certificate discount value is greater than order total amount. \n\n';
				$str_info .= 'So net $' . $new_total . ' is deduct from gift certifiacte value. \n\n';
				$str_info .= 'Used Gift Certificate code is (' . $GiftCouponInfo['Code'] . ')';

				$updAray = array('pay_status' 	   => 'Paid', 'transaction_info' => $str_info);

				Order::where('orders_id', '=', Session::get('ShoppingCart.OrderID'))->update($updAray);

				//return redirect(config('global.SITE_URL')."order-receipt");

			}
		} else if ($this->GetNetTotal() == 0) {
			$updAray = array('pay_status' => 'Paid');
			Order::where('orders_id', '=', Session::get('ShoppingCart.OrderID'))->update($updAray);

			//return redirect(config('global.SITE_URL')."order-receipt");

		}

		//return redirect(config('global.SITE_URL')."order-receipt");
		//exit;
		return true;
	}
	public function StoreUpdateOrder(Request $request)
	{

		if (!Session::has('ShoppingCart.WebsiteOrderID') && !Session::has('ShoppingCart.StoreOrderID') && $request->OrderStoreTypeValue == "Both") {
			$err_msg = "Please try again to place order.";
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/shoppingcart/view');
			exit;
		} else if (!Session::has('ShoppingCart.OrderID')  && $request->OrderStoreTypeValue != "Both") {
			$err_msg = "Please try again to place order.";
			Session::flash('PlaceOrderError', $err_msg);
			return redirect('/shoppingcart/view');
			exit;
		}

		$CreditDiscount = $this->GetAllDiscounts('CreditLimitDiscount');
		$is_amazon = 0;
		if ($this->GetNetTotal() <= 0 && $CreditDiscount > 0 && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && Session::get("etype") == "M" && Session::get("sess_icustomerid") > 0 && Session::get("is_dropshipper") != "Yes") {
			if ($request->PaymentMethod == 'PAYMENT_PAYWITHAMAZON')
				$is_amazon = 1;
			$request->PaymentMethod  = "PAYMENT_CL";
		}

		if ($this->GetNetTotal() <= 0 && $CreditDiscount <= 0) {

			$request->PaymentMethod = 'PAYMENT_FREEITEM';
		}

		$this->setPaymentDetail($request);

		$arrPaymentDetail = Session::get('ShoppingCart.Payment_Detail');

	/*	$GiftValue = $this->FreeGiftValue($this->GetNetTotal());

		if (count($GiftValue) > 0) {
			$free_gift = Session::get('ShoppingCart.FreeGift');
			$gift_from = Session::get('ShoppingCart.GiftFrom');
			$gift_to   = Session::get('ShoppingCart.GiftTo');
			$gift_message_customer = Session::get('ShoppingCart.GiftMessageCustomer');
		} else {
			$free_gift = '';
			$gift_from = '';
			$gift_to   = '';
			$gift_message_customer = (isset($request->gift_message_customer)) ? $request->gift_message_customer : '';
			if (strtolower(trim($gift_message_customer ?? '')) == " *gift message") {
				$gift_message_customer = '';
			}
			Session::forget('ShoppingCart.FreeGift');
			Session::forget('ShoppingCart.GiftFrom');
			Session::forget('ShoppingCart.GiftTo');
			Session::forget('ShoppingCart.GiftMessageCustomer');
		}

		if (isset($request->customer_comment) && strtolower(trim($request->customer_comment)) == "special request") {
			$request->customer_comment = "";
		}
		*/		
		$merge_note = (Session::has('ShoppingCart.merge_note')) ? Session::get('ShoppingCart.merge_note') : "";
		$Payment_Type = (isset($arrPaymentDetail['Payment_Type'])) ? $arrPaymentDetail['Payment_Type'] : '';
		$Payment_Method = (isset($arrPaymentDetail['Payment_Method'])) ? $arrPaymentDetail['Payment_Method'] : '';

		$PaymentResponse = "";

		//$customer_comment = (isset($request->customer_comment)) ? $request->customer_comment : '';

		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			$CartArray = Session::get('ShoppingCart.Cart');
		}

		 
		if(isset($request->PaymentMethod) && $request->PaymentMethod == 'PAYMENT_FREEITEM'){
			$store_ord_status = 'Completed';
		} else {
			$store_ord_status = 'Pending';
		}

		$OrderUpdate = array(
			'payment_type' 		=> $Payment_Type,
			'payment_method' 	=> $Payment_Method,
			'pay_status' 		=> 'Paid',
			//'customer_comment' 	=> $customer_comment,
			'status'			=> $store_ord_status, //'Pending',
			//'gift_from'				=> $gift_from,
			//'gift_to'				=> $gift_to,
			//'gift_message_customer'	=> $gift_message_customer,
			'merge_note'		=> 	$merge_note
		);

		if (isset($request->stripe_payment_id) && $Payment_Type == "PAYMENT_STRIPE_NORMAL") {
			$OrderUpdate["transaction_info"] 		 	= $request->fullresponse ?? "";
			$OrderUpdate["payment_gateway_response"] 	= $request->fullresponse ?? "";
			$OrderUpdate["paymentintentid"] 			= $request->stripe_payment_id;
		}
		if (empty($request->stripe_payment_id) && $Payment_Type == "PAYMENT_STRIPE_NORMAL") {
			$OrderUpdate["pay_status"] = "Unpaid";
			$OrderUpdate["payment_gateway_response"] = $request->fullresponse ?? "";
		}

		if (Session::has('ShoppingCart.WebsiteOrderID') && Session::has('ShoppingCart.StoreOrderID') && $request->OrderStoreTypeValue == "Both") {

			$cust_upd = Order::where('orders_id', '=', Session::get('ShoppingCart.WebsiteOrderID'))->update($OrderUpdate);
			$cust_upd = Order::where('orders_id', '=', Session::get('ShoppingCart.StoreOrderID'))->update($OrderUpdate);
			return redirect('/order-receipts');
		} else {
			$cust_upd = Order::where('orders_id', '=', Session::get('ShoppingCart.OrderID'))->update($OrderUpdate);
			return redirect('/order-receipt');
		}
		exit;
	}
	
	public function skuvault(array $OrderRes, array $OrderDetailRes)
{
	return null;
    try {

        $orderId = $OrderRes['orders_id'] ?? 0;
        $orderNo = (string)($OrderRes['orders_no'] ?? '');

        if (empty($orderId) || empty($orderNo)) {
            Log::error('SkuVault: Invalid order information.', [
                'orders_id' => $orderId,
                'orders_no' => $orderNo,
            ]);
            return;
        }

        /*
         * ---------------------------------------------------------
         * KEEP EXISTING ORDER STATUS LOGIC
         * ---------------------------------------------------------
         */
        $CurrOrder = Order::find($orderId);

        if ($CurrOrder) {
            $CurrOrder->update([
                'status'      => 'Completed',
                'ship_status' => 'Shipped',
                'ship_date'   => date('Y-m-d'),
            ]);

            OrderDetail::where('orders_id', $orderId)
                ->where('item_type', 'Store')
                ->update([
                    'ostatus'         => 'Completed',
                    'dtl_ship_status' => 'Shipped',
                    'dtl_ship_date'   => date('Y-m-d'),
                ]);
        }

        /*
         * ---------------------------------------------------------
         * STORE DETAILS
         * ---------------------------------------------------------
         */
        $orderItems = [];
        $OrderPickupItems = [];

        $normaluser = Auth::guard('store')->user();

        if (!$normaluser) {
            Log::error(
                'SkuVault: Store user not found. Order ID=' . $orderId
            );
            return;
        }

        $StoreDetailsVal = DB::table('pu_stores')
            ->where('store_id', $normaluser->store_id)
            ->first();

        if (!$StoreDetailsVal) {

            $message = 'Store details not found. Store ID='
                . $normaluser->store_id;

            Log::error(
                'SkuVault: ' . $message
            );

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS skuvault from Order receipt',
                'error_message' => $message,
                'cron_file'     => 'POS Order Receipt Page',
            ]);

            return;
        }

        /*
         * ---------------------------------------------------------
         * BUILD ORDER ITEMS
         * ---------------------------------------------------------
         */
        foreach ($OrderDetailRes as $item) {

            $sku = trim((string)($item['sku'] ?? ''));
            $quantity = (int)($item['quantity'] ?? 0);
            $price = (float)($item['price'] ?? 0);

            if ($sku === '' || $quantity <= 0) {

                Log::warning(
                    'SkuVault: Invalid order item skipped.',
                    [
                        'order_id' => $orderId,
                        'sku'      => $sku,
                        'quantity' => $quantity,
                    ]
                );

                continue;
            }

            $orderItems[] = [
                'Sku'       => $sku,
                'Quantity'  => $quantity,
                'UnitPrice' => $price,
            ];

            $OrderPickupItems[] = [
                'SaleId'        => $orderNo,
                'Sku'           => $sku,
                'WarehouseId'   => (int)$StoreDetailsVal->store_warehouse,
                'Quantity'      => $quantity,
                'IsExpressPick' => true,
                'LocationCode'  => null,
            ];
        }

        /*
         * ---------------------------------------------------------
         * NO VALID ITEMS
         * ---------------------------------------------------------
         */
        if (empty($orderItems)) {

            $message =
                'OrderItems cannot be empty. Order ID=' . $orderId;

            Log::error('SkuVault: ' . $message);

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS skuvault from Order receipt',
                'error_message' => $message,
                'cron_file'     => 'POS Order Receipt Page',
            ]);

            return;
        }

        /*
         * ---------------------------------------------------------
         * CLIENT
         * ---------------------------------------------------------
         */
        $client = new Client([
            'base_uri'        => 'https://app.skuvault.com/',
            'timeout'         => 15,
            'connect_timeout' => 5,
        ]);

        /*
         * ---------------------------------------------------------
         * PAYLOAD
         * ---------------------------------------------------------
         */
        $payload = [
            'TenantToken' => config('services.skuvault.tenant_token'),
            'UserToken'   => config('services.skuvault.user_token'),

            'Sales' => [
                [
                    'OrderId' => $orderNo,

                    /*
                     * Keep your existing behavior.
                     */
                    'OrderDateUtc' => gmdate('c'),

                    'OrderTotal'     => $OrderRes['order_total'] ?? 0,
                    'CheckoutStatus' => 'Completed',
                    'PaymentStatus'  => 'Cleared',
                    'SaleState'      => 'Active',

                    'ItemSkus' => $orderItems,

                    'ShippingInfo' => [
                        'FirstName' => $OrderRes['ship_first_name'] ?? '',
                        'LastName'  => $OrderRes['ship_last_name'] ?? '',
                        'Line1'     => $OrderRes['ship_address1'] ?? '',
                        'City'      => $OrderRes['ship_city'] ?? '',
                        'Region'    => $OrderRes['ship_state'] ?? '',
                        'Postal'    => $OrderRes['ship_postcode'] ?? '',
                        'Country'   => $OrderRes['ship_country'] ?? '',
                    ],

                    'AutoRemoveInfo' => [
                        'AutoRemove' => true,
                        'WarehouseCode' => [
                            'Code' => $StoreDetailsVal->store_code ?? '',
                        ],
                    ],
                ],
            ],
        ];

        Log::info(
            'SkuVault syncOnlineSales request. '
            . 'Order ID=' . $orderId
        );

        /*
         * ---------------------------------------------------------
         * SYNC ONLINE SALES
         * ---------------------------------------------------------
         */
        try {

            $response = $client->post(
                'api/sales/syncOnlineSales',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                    ],
                    'json' => $payload,
                ]
            );

            $responseBody =
                $response->getBody()->getContents();

            $DataArr = json_decode(
                $responseBody,
                true
            );

            if (!is_array($DataArr)) {
                $DataArr = [];
            }

            $StatusVal = $DataArr['Status'] ?? '';

            /*
             * TESTING ONLY:
             *
             * echo $StatusVal;
             * exit;
             */

            Log::info(
                'SkuVault syncOnlineSales response. '
                . 'Order ID=' . $orderId
                . ' Status=' . $StatusVal
                . ' Response=' . $responseBody
            );

            /*
             * -----------------------------------------------------
             * SUCCESS
             * -----------------------------------------------------
             */
            if ($StatusVal === 'OK') {

                $CurrOrder = Order::find($orderId);

                if ($CurrOrder) {

                    $CurrOrder->is_skuvault = 'Yes';

                    $CurrOrder->transaction_info =
                        ($CurrOrder->transaction_info ?? '')
                        . '###syncOnlineSales:'
                        . $responseBody;

                    $CurrOrder->save();
                }

                /*
                 * ONLY PICK AFTER SUCCESSFUL SYNC
                 */
                $this->SkuvaultPickup(
                    $orderId,
                    $OrderPickupItems
                );

            } else {

                /*
                 * -------------------------------------------------
                 * SYNC FAILED
                 * -------------------------------------------------
                 */
                $CurrOrder = Order::find($orderId);

                if ($CurrOrder) {

                    $CurrOrder->is_skuvault = 'No';

                    $CurrOrder->transaction_info =
                        ($CurrOrder->transaction_info ?? '')
                        . '###syncOnlineSales:'
                        . $responseBody;

                    $CurrOrder->save();
                }

                DB::table('pu_errors_notification')->insert([
                    'error_for'     => 'POS skuvault from Order receipt',
                    'error_message' => $responseBody,
                    'cron_file'     => 'POS Order Receipt Page',
                ]);
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $statusCode = $e->hasResponse()
                ? $e->getResponse()->getStatusCode()
                : 0;

            $errorMessage = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            Log::error(
                'SkuVault syncOnlineSales RequestException.',
                [
                    'order_id'    => $orderId,
                    'http_status' => $statusCode,
                    'error'       => $errorMessage,
                ]
            );

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS skuvault from Order receipt',
                'error_message' => $errorMessage,
                'cron_file'     => 'POS Order Receipt Page',
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'SkuVault syncOnlineSales unexpected exception.',
                [
                    'order_id' => $orderId,
                    'error'    => $e->getMessage(),
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                ]
            );

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS skuvault from Order receipt',
                'error_message' => $e->getMessage(),
                'cron_file'     => 'POS Order Receipt Page',
            ]);
        }

    } catch (\Throwable $e) {

        /*
         * FINAL SAFETY NET
         */
        Log::error(
            'SkuVault main function unexpected exception.',
            [
                'order_id' => $OrderRes['orders_id'] ?? 0,
                'error'    => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]
        );

        DB::table('pu_errors_notification')->insert([
            'error_for'     => 'POS skuvault from Order receipt',
            'error_message' => $e->getMessage(),
            'cron_file'     => 'POS Order Receipt Page',
        ]);
    }
}


public function SkuvaultPickup($orders_id, $OrderPickupItems)
{
    try {

        if (empty($OrderPickupItems)) {
            return;
        }

        $client = new Client([
            'base_uri'        => 'https://app.skuvault.com/',
            'timeout'         => 15,
            'connect_timeout' => 5,
        ]);

        $payload = [
            'TenantToken' => config('services.skuvault.tenant_token'),
            'UserToken'   => config('services.skuvault.user_token'),
            'Items'       => $OrderPickupItems,
        ];

        Log::info(
            'SkuVault pickItemBulk request. '
            . 'Order ID=' . $orders_id
        );

        /*
         * These are business/inventory errors.
         *
         * DO NOT retry the same batch when these occur.
         */
        $nonRetryableErrors = [
            'ExpressPickSingleItemLocationButFoundMultipleLocations',
            'PickQuantityGreaterThanRemainingSaleQuantity',
            'SaleIdNotFound',
            'SkuNotFound',
            'ItemNotFound',
            'LocationNotFound',
        ];

        /*
         * Only transient network/server errors
         * are allowed to retry.
         */
        $maxAttempts = 3;

        $attempt = 0;
        $success = false;
        $FinalResponse = '';

        while ($attempt < $maxAttempts && !$success) {

            $attempt++;

            try {

                Log::info(
                    'SkuVault pickItemBulk attempt.',
                    [
                        'attempt' => $attempt,
                        'order_id' => $orders_id,
                    ]
                );

                $response = $client->post(
                    'api/inventory/pickItemBulk',
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept'       => 'application/json',
                        ],
                        'json' => $payload,
                    ]
                );

                $FinalResponse =
                    $response->getBody()->getContents();

                Log::info(
                    'SkuVault pickItemBulk response.',
                    [
                        'attempt'  => $attempt,
                        'order_id' => $orders_id,
                        'response' => $FinalResponse,
                    ]
                );

                $ResponseArray = json_decode(
                    $FinalResponse,
                    true
                );

                if (!is_array($ResponseArray)) {

                    Log::error(
                        'SkuVault pickItemBulk invalid JSON.',
                        [
                            'order_id' => $orders_id,
                        ]
                    );

                    break;
                }

                $status = $ResponseArray['Status'] ?? '';
                $errors = $ResponseArray['Errors'] ?? [];

                /*
                 * -------------------------------------------------
                 * ITEM LEVEL ERRORS
                 * -------------------------------------------------
                 */
                if (!empty($errors)) {

                    foreach ($errors as $error) {

                        $sku =
                            $error['Sku'] ?? '';

                        $saleId =
                            $error['SaleId'] ?? '';

                        $errorMessages =
                            $error['ErrorMessages'] ?? [];

                        foreach ($errorMessages as $errorMessage) {

                            Log::error(
                                'SkuVault pickItemBulk item error.',
                                [
                                    'order_id'   => $orders_id,
                                    'sale_id'    => $saleId,
                                    'sku'        => $sku,
                                    'error'      => $errorMessage,
                                ]
                            );
                        }
                    }

                    /*
                     * IMPORTANT:
                     *
                     * ANY item-level error means:
                     * DO NOT resend entire batch.
                     */
                    Log::warning(
                        'SkuVault pickItemBulk returned item errors. '
                        . 'Stopping retry to avoid duplicate picking.',
                        [
                            'order_id' => $orders_id,
                        ]
                    );

                    $success = false;
                    break;
                }

                /*
                 * -------------------------------------------------
                 * SUCCESS
                 * -------------------------------------------------
                 */
                if (
                    ($status === 'OK' || $status === 'Accepted')
                    && empty($errors)
                ) {

                    $success = true;
                    break;
                }

                /*
                 * Unknown business response.
                 *
                 * Do not hammer API.
                 */
                Log::warning(
                    'SkuVault pickItemBulk unexpected response.',
                    [
                        'order_id' => $orders_id,
                        'status'   => $status,
                    ]
                );

                break;

            } catch (\GuzzleHttp\Exception\RequestException $e) {

                $statusCode = $e->hasResponse()
                    ? $e->getResponse()->getStatusCode()
                    : 0;

                $FinalResponse = $e->hasResponse()
                    ? $e->getResponse()->getBody()->getContents()
                    : $e->getMessage();

                Log::error(
                    'SkuVault pickItemBulk RequestException.',
                    [
                        'attempt'     => $attempt,
                        'order_id'    => $orders_id,
                        'http_status' => $statusCode,
                        'error'       => $FinalResponse,
                    ]
                );

                /*
                 * ONLY retry temporary HTTP errors.
                 */
               if (!in_array($statusCode, [429], true)) {
					break;
				}

            } catch (\Throwable $e) {

                $FinalResponse = $e->getMessage();

                Log::error(
                    'SkuVault pickItemBulk unexpected exception.',
                    [
                        'attempt'  => $attempt,
                        'order_id' => $orders_id,
                        'error'    => $FinalResponse,
                    ]
                );

                break;
            }

            /*
             * Retry only temporary failures.
             */
            if (
                !$success &&
                $attempt < $maxAttempts
            ) {
                sleep(2);
            }
        }

        /*
         * ---------------------------------------------------------
         * SAVE PICKUP RESULT
         * ---------------------------------------------------------
         */
        $CurrOrder = Order::find($orders_id);

        if ($CurrOrder) {

            $CurrOrder->is_skuvault =
                ($CurrOrder->is_skuvault ?? '')
                . ($success ? '##Yes' : '##No');

            $CurrOrder->transaction_info =
                ($CurrOrder->transaction_info ?? '')
                . '###pickItemBulk:'
                . $FinalResponse;

            $CurrOrder->save();
        }

        /*
         * ---------------------------------------------------------
         * ONLY UPDATE COMPLETED AFTER PICK SUCCESS
         * ---------------------------------------------------------
         */
        if ($success) {

            Log::info(
                'SkuVault pickup successful. '
                . 'Calling updateOnlineSaleStatus.',
                [
                    'order_id' => $orders_id,
                ]
            );

            $this->SkuvalutUpdateOnlineSaleStatus(
                $orders_id
            );

        } else {

            Log::error(
                'SkuVault pickup failed. '
                . 'updateOnlineSaleStatus will NOT be called.',
                [
                    'order_id' => $orders_id,
                ]
            );

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS Skuvault Pickup Items',
                'error_message' => $FinalResponse,
                'cron_file'     => 'POS Order Receipt Page',
            ]);
        }

    } catch (\Throwable $e) {

        /*
         * FINAL SAFETY NET
         */
        Log::error(
            'SkuVault pickup main exception.',
            [
                'order_id' => $orders_id,
                'error'    => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]
        );

        DB::table('pu_errors_notification')->insert([
            'error_for'     => 'POS Skuvault Pickup Items',
            'error_message' => $e->getMessage(),
            'cron_file'     => 'POS Order Receipt Page',
        ]);
    }
}


public function SkuvalutUpdateOnlineSaleStatus($orders_id)
{
    try {

        if (empty($orders_id)) {
            return;
        }

        $CurrOrder = Order::find($orders_id);

        if (!$CurrOrder) {
            Log::warning(
                'SkuVault updateOnlineSaleStatus: Order not found.',
                [
                    'order_id' => $orders_id,
                ]
            );
            return;
        }

        $client = new Client([
            'base_uri'        => 'https://app.skuvault.com/',
            'timeout'         => 15,
            'connect_timeout' => 5,
        ]);

        $payload = [
            'TenantToken' => config('services.skuvault.tenant_token'),
            'UserToken'   => config('services.skuvault.user_token'),
            'OrderId'     => $CurrOrder->orders_no,
            'Status'      => 'Completed',
        ];

        Log::debug(
            'SkuVault updateOnlineSaleStatus request.',
            [
                'order_id' => $orders_id,
            ]
        );

        $maxAttempts = 3;
        $attempt = 0;
        $success = false;
        $FinalResponse = '';

        while ($attempt < $maxAttempts && !$success) {

            $attempt++;

            try {

                Log::info(
                    'SkuVault updateOnlineSaleStatus attempt.',
                    [
                        'attempt'  => $attempt,
                        'order_id' => $orders_id,
                    ]
                );

                $response = $client->post(
                    'api/sales/updateOnlineSaleStatus',
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept'       => 'application/json',
                        ],
                        'json' => $payload,
                    ]
                );

                $FinalResponse =
                    $response->getBody()->getContents();

                Log::info(
                    'SkuVault updateOnlineSaleStatus response.',
                    [
                        'attempt'  => $attempt,
                        'order_id' => $orders_id,
                        'response' => $FinalResponse,
                    ]
                );

                $ResponseArray = json_decode(
                    $FinalResponse,
                    true
                );

                /*
                 * Empty successful HTTP response.
                 */
                if (!is_array($ResponseArray)) {

                    if (
                        $response->getStatusCode() >= 200 &&
                        $response->getStatusCode() < 300 &&
                        trim($FinalResponse) === ''
                    ) {
                        $success = true;
                    }

                    break;
                }

                $status = $ResponseArray['Status'] ?? '';
                $errors = $ResponseArray['Errors'] ?? [];

                /*
                 * SUCCESS
                 */
                if (
                    in_array(
                        $status,
                        ['OK', 'Accepted'],
                        true
                    ) &&
                    empty($errors)
                ) {

                    $success = true;
                    break;
                }

                /*
                 * BUSINESS/API ERROR
                 *
                 * Do NOT keep sending Completed.
                 */
                if (!empty($errors)) {

                    Log::error(
                        'SkuVault updateOnlineSaleStatus returned errors.',
                        [
                            'order_id' => $orders_id,
                            'errors'   => $errors,
                        ]
                    );

                    break;
                }

                /*
                 * Unknown response.
                 */
                Log::warning(
                    'SkuVault updateOnlineSaleStatus unexpected response.',
                    [
                        'order_id' => $orders_id,
                        'status'   => $status,
                    ]
                );

                break;

            } catch (\GuzzleHttp\Exception\RequestException $e) {

                $statusCode = $e->hasResponse()
                    ? $e->getResponse()->getStatusCode()
                    : 0;

                $FinalResponse = $e->hasResponse()
                    ? $e->getResponse()->getBody()->getContents()
                    : $e->getMessage();

                Log::error(
                    'SkuVault updateOnlineSaleStatus RequestException.',
                    [
                        'attempt'     => $attempt,
                        'order_id'    => $orders_id,
                        'http_status' => $statusCode,
                        'error'       => $FinalResponse,
                    ]
                );

                /*
                 * Retry only temporary errors.
                 */
                if (
                    !in_array(
                        $statusCode,
                        [429],
                        true
                    )
                ) {
                    break;
                }

            } catch (\Throwable $e) {

                $FinalResponse = $e->getMessage();

                Log::error(
                    'SkuVault updateOnlineSaleStatus exception.',
                    [
                        'attempt'  => $attempt,
                        'order_id' => $orders_id,
                        'error'    => $FinalResponse,
                    ]
                );

                break;
            }

            if (
                !$success &&
                $attempt < $maxAttempts
            ) {
                sleep(2);
            }
        }

        /*
         * ---------------------------------------------------------
         * SAVE RESULT
         * ---------------------------------------------------------
         */
        $CurrOrder = Order::find($orders_id);

        if ($CurrOrder) {

            $CurrOrder->is_skuvault =
                ($CurrOrder->is_skuvault ?? '')
                . ($success ? '##Yes' : '##No');

            $CurrOrder->transaction_info =
                ($CurrOrder->transaction_info ?? '')
                . '###updateOnlineSaleStatus:'
                . $FinalResponse;

            $CurrOrder->save();
        }

        /*
         * ---------------------------------------------------------
         * ERROR NOTIFICATION
         * ---------------------------------------------------------
         */
        if (!$success) {

            DB::table('pu_errors_notification')->insert([
                'error_for'     => 'POS Skuvault Update Online Sale Status',
                'error_message' => $FinalResponse,
                'cron_file'     => 'POS Order Receipt Page',
            ]);
        }

    } catch (\Throwable $e) {

        /*
         * FINAL SAFETY NET
         */
        Log::error(
            'SkuVault updateOnlineSaleStatus main exception.',
            [
                'order_id' => $orders_id,
                'error'    => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]
        );

        DB::table('pu_errors_notification')->insert([
            'error_for'     => 'POS Skuvault Update Online Sale Status',
            'error_message' => $e->getMessage(),
            'cron_file'     => 'POS Order Receipt Page',
        ]);
    }
}
	
			
	public function cashDrawer(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		$store_id = Session::get('sess_store_id');
		$store_user_id = Auth::guard('store')->user()->store_user_id;

		$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id', '=', $store_id)->first();
		$store_name = $get_store_name->store_name;

		$store_open_balance = $store_cash_sales = $store_cash_refunds = $store_cash_manual = $set_store_cash_manual = $store_bank_deposite = '-';
		$current_balance = '$0.00';

		$isshow_dailyopen_pending = 1;
		$isshow_dailyopen_btn = $isshow_dailyclose_btn = $isshow_manualopen_btn = 0;

		/*if(session()->has('store_user_access_rights') && is_array(session()->get('store_user_access_rights')))
		{
			if(in_array('pos_cash_drawer_daily_open', session()->get('store_user_access_rights')))
			{*/
		$isshow_dailyopen_btn = 1;
		/*}
			if(in_array('pos_cash_drawer_daily_close', session()->get('store_user_access_rights')))
			{*/
		$isshow_dailyclose_btn = 1;
		/*}
		}*/

		$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->orderBy('added_datetime', 'desc')->first();

		/*$getAllManualOpenForDay = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->orderBy('added_datetime', 'desc')->get();

		$total_store_cash_manual = 0;
		//for($rf=0;$rf<count($getAllManualOpenForDay);$rf++){
		foreach ($getAllManualOpenForDay as $ManualOpenForDay) {
			$total_store_cash_manual += $ManualOpenForDay->store_cash_manual;
		}

		if (!empty($getAllManualOpenForDay)) {
			$store_cash_manual = Price($total_store_cash_manual, 'yes', 'yes');
		}else{
			$store_cash_manual = Price(0, 'yes', 'yes');
		}*/

		if (!empty($cashd_res)) {
			$today_date = date('m-d-Y');
			if ($today_date == date('m-d-Y', strtotime($cashd_res->added_datetime))) {
				$isshow_dailyopen_pending = 0;
				$isshow_manualopen_btn = 1;
				$store_open_balance = Price($cashd_res->store_open_balance, 'yes', 'yes');
				$store_cash_sales = Price($cashd_res->store_cash_sales, 'yes', 'yes');
				$store_cash_refunds = Price($cashd_res->store_cash_refunds, 'yes', 'yes');
				$store_cash_manual = Price($cashd_res->store_cash_manual, 'yes', 'yes');
				$store_bank_deposite = Price($cashd_res->store_bank_deposite, 'yes', 'yes');
				$current_balance = Price($cashd_res->current_balance, 'yes', 'yes');

				if($cashd_res->store_cash_manual < 0){
					$set_store_cash_manual = str_replace("-", "", $cashd_res->store_cash_manual);
					$set_store_cash_manual = "-$".$set_store_cash_manual;
				}
				else if($cashd_res->store_cash_manual == 0){
					$set_store_cash_manual = Price($cashd_res->store_cash_manual, 'yes', 'yes');
				}
				else{
					$set_store_cash_manual = "$".$cashd_res->store_cash_manual;
				}

			} else {
				$isshow_dailyclose_btn = 0;
				$current_balance = Price($cashd_res->current_balance, 'yes', 'yes');
			}
		} else {
			$isshow_dailyclose_btn = 0;
		}

		$metaData = (object) [
			'meta_title' => config('global.META_TITLE') . ' :: POS Cash Drawer',
			'meta_description' => '',
			'meta_keywords' => '',
		];

		/* Canonical URL */
		$canonical_url = config('global.SITE_URL') . "store/cash-drawer";
		//$this->PageData = $this->checkForMetaData($canonical_url, $metaData);
		$this->PageData['canonical_url'] = $canonical_url;

		## Breadcrumb
		$page_bread_crumb	= '';
		$page_bread_crumb 	.= '<a href="' . config('global.SITE_URL') . '">Home</a>';
		$page_bread_crumb 	.= '<span class="active">POS Cash Drawer</span>';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$is_noindex_nofollow_meta = 1;

		$store_user = Auth::guard('store')->user();
		$this->PageData['store_user_id'] =  $store_user->store_user_id;

		//dd($this->PageData);
		return view('pos.cashdrawer', compact('page_bread_crumb', 'is_noindex_nofollow_meta', 'store_open_balance', 'store_cash_sales', 'store_cash_refunds', 'store_cash_manual', 'set_store_cash_manual', 'store_bank_deposite', 'current_balance', 'isshow_dailyopen_btn', 'isshow_dailyclose_btn', 'isshow_manualopen_btn', 'isshow_dailyopen_pending', 'store_id', 'store_name', 'store_user_id'))->with($this->PageData);
	}

	public function cashDrawerDailyOpen(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		/*if(!session()->has('store_user_access_rights') || !is_array(session()->get('store_user_access_rights')) || !in_array('pos_cash_drawer_daily_open', session()->get('store_user_access_rights')))
		{
			return redirect('store/store-dashboard.html');
		}*/

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		$store_id = Session::get('sess_store_id');
		$store_user_id = Auth::guard('store')->user()->store_user_id;

		$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id', '=', $store_id)->first();
		$store_name = $get_store_name->store_name;

		$us_currencies = [
			//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
			'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
			'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
			'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
			'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
			'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
			'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
			'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
			'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
			'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
		];

		$act_type = 'add';
		$store_cash_drawer_id = '';
		$expected_cash_drawer_fund = 0;
		$total_balance = 0;
		$is_balance_matched = null;
		$is_deposit_adjusted = null;
		$adjustment_type = '';
		$adjustment_amount = '0.00';
		$adjustment_reason = '';

		$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('type', 'open')->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();

		if (!empty($cashdoc_res)) {
			foreach ($us_currencies as $key => $value) {
				if ($cashdoc_res->{$key . '_count'} != 0) {
					$us_currencies[$key]['count'] = $cashdoc_res->{$key . '_count'};
					$us_currencies[$key]['row_total'] = $cashdoc_res->{$key . '_value'};
				}
			}

			$store_cash_drawer_id = $cashdoc_res->store_cash_drawer_id;
			$expected_cash_drawer_fund = $cashdoc_res->expected_cash_drawer_fund;
			$total_balance = $cashdoc_res->total_balance;
			$is_balance_matched = $cashdoc_res->is_balance_matched;
			$is_deposit_adjusted = $cashdoc_res->is_deposit_adjusted;
			$adjustment_type = $cashdoc_res->adjustment_type;
			$adjustment_amount = $cashdoc_res->adjustment_amount;
			$adjustment_reason = $cashdoc_res->adjustment_reason;

			if ($is_balance_matched == 'no' && $is_deposit_adjusted == null) {
				if ($request->act_type == 'update') {
					$act_type = 'update';
				} else {
					$act_type = 'manually_adjust';
				}
			} else {
				$act_type = 'view';
			}
		} else {
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}
		}

		$metaData = (object) [
			'meta_title' => config('global.META_TITLE') . ' :: POS Cash Drawer :: Daily Open',
			'meta_description' => '',
			'meta_keywords' => '',
		];

		/* Canonical URL */
		$canonical_url = config('global.SITE_URL') . "store/cash-drawer-daily-open";
		//$this->PageData = $this->checkForMetaData($canonical_url, $metaData);
		$this->PageData['canonical_url'] = $canonical_url;

		## Breadcrumb
		$page_bread_crumb	= '';
		$page_bread_crumb 	.= '<a href="' . config('global.SITE_URL') . '">Home</a>';
		$page_bread_crumb 	.= '<span class="active">POS Cash Drawer</span>';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$is_noindex_nofollow_meta = 1;

		return view('pos.cashdrawerdailyopen', compact('page_bread_crumb', 'is_noindex_nofollow_meta', 'act_type', 'us_currencies', 'store_id', 'store_user_id', 'store_name', 'store_cash_drawer_id', 'expected_cash_drawer_fund', 'total_balance', 'is_balance_matched', 'is_deposit_adjusted', 'adjustment_type', 'adjustment_amount', 'adjustment_reason'))->with($this->PageData);
	}

	public function saveCashDrawerDailyOpen(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		$input = $request->all();

		$act_type = $input['act_type'];

		$store_id = $input['store_id'];

		if ($act_type == 'add') {
			$expected_cash_drawer_fund = 0.00;
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();

			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}

			$insertData = array();
			$insertData['store_id'] = $store_id;
			$insertData['type'] = $input['type'];

			$total_balance = 0;
			$us_currencies = [
				//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
				'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
				'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
				'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
				'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
				'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
				'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
				'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
				'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
				'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
			];
			foreach ($us_currencies as $key => $value) {
				if (isset($input[$key])) {
					$insertData[$key . '_count'] = $input[$key];
					$row_total = $value['conv_value'] * $input[$key];
					$insertData[$key . '_value'] = $row_total;
					$total_balance += $row_total;
				} else {
					$insertData[$key . '_count'] = 0;
					$insertData[$key . '_value'] = 0.00;
				}
			}

			$insertData['expected_cash_drawer_fund'] = $expected_cash_drawer_fund;
			$insertData['total_balance'] = $total_balance;
			$insertData['is_balance_matched'] = null;
			$insertData['is_deposit_adjusted'] = null;
			$insertData['admin_email'] = Session::get('sess_storeuseremail');
			$insertData['added_datetime'] = date("Y-m-d H:i:s");
			$insertData['updated_datetime'] = date("Y-m-d H:i:s");

			$insertId = DB::table('pu_store_cash_drawer_daily_open')->insertGetId($insertData);

			if ($insertId) {
				$success_msg = "Daily open submitted successfully.";
				$expected_cash_drawer_fund = 0;
				$adjustment_amount = 0;
				$adjustment_type = null;

				$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('store_cash_drawer_id', $insertId)->first();

				/* Log daily open event if cash drawer not connected start */
				$today_cdlog_res = DB::table('pu_store_cashdrawer_log')
					->select('action_type')
					->where('store_id', '=', $store_id)
					->where('store_user_id', '=', Auth::guard('store')->user()->store_user_id)
					->where('action', '=', 'Daily Open')
					->where('action_type', '=', 'Open')
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();
				if (empty($today_cdlog_res)) {

					$action_notes = array(
						config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $cashdoc_res->expected_cash_drawer_fund,
						config('global.STORE_LOGS_ADDED_AMOUNT') => $cashdoc_res->total_balance,
						config('global.STORE_LOGS_ADJUSTED_AMOUNT') => $cashdoc_res->adjustment_amount,
						config('global.STORE_LOGS_UPDATED_AMOUNT') => $cashdoc_res->total_balance,
						//config('global.STORE_LOGS_ADJUSTMENT_TYPE') => '',
						//config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => ''
					);
					//dd($action_notes);

					$newrequest = new \Illuminate\Http\Request();
					$newrequest->setMethod('POST');
					$newrequest->request->add(['action' => 'Daily Open', 'action_type' => 'Open', 'actionNotes' => json_encode($action_notes), 'cd_amount' => 0, 'is_device_event' => 0]);
					$this->cashDrawerLog($newrequest);
				}
				/* Log daily open event if cash drawer not connected start */

				if (!empty($prev_day_cd_res)) {
					$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
					if ($prev_day_cd_res->current_balance == $total_balance) {
						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $insertId)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

						if ($is_update) {
							$insertCDData = array();
							$insertCDData['store_id'] = $store_id;
							$insertCDData['store_open_balance'] = $total_balance;
							$insertCDData['current_balance'] = $total_balance;
							$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
							$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

							$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

							if ($insertIdCD) {
								$success_msg = "Your opening balance matches your previous closing balance, and this amount has been recorded for your store opening.";

								return redirect('store/cash-drawer')->with(['success' => $success_msg]);
							} else {
								return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
							}
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						$adjustment_amount = abs($total_balance - $prev_day_cd_res->current_balance);
						$adjustment_type = ($total_balance > $prev_day_cd_res->current_balance) ? 'increase' : 'decrease';
					}
				} elseif (empty($prev_day_cd_res) && $total_balance == 0) {

					$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $insertId)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

					if ($is_update) {
						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $total_balance;
						$insertCDData['current_balance'] = $total_balance;
						$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

						if ($insertIdCD) {
							$success_msg = "This amount has been recorded for your store opening.";

							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
					}
				} else {
					$adjustment_amount = $total_balance;
					$adjustment_type = 'increase';
				}

				$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $insertId)->update(['is_balance_matched' => 'no', 'adjustment_type' => $adjustment_type, 'adjustment_amount' => $adjustment_amount, 'updated_datetime' => date("Y-m-d H:i:s")]);

				if($is_update){
					$isshow_dailyopen_pending = 2;
				}else{
					$isshow_dailyopen_pending = 1;
				}
				/*$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
				//dd($cashd_res);
				if (empty($cashd_res)) {
					$isshow_dailyopen_pending = 1;
				}*/
				/*Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);
				Session::save();*/
				$request->attributes->add(['shared_isshow_dailyopen_pending' => $isshow_dailyopen_pending]);

				//if($is_update)
				//{
				return redirect('store/cash-drawer-daily-open')->with(['manual_adjustment' => $success_msg]);
				//}

			}

			return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
		} elseif ($act_type == 'update') {
			$expected_cash_drawer_fund = 0;
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}

			$store_cash_drawer_id = $input['store_cash_drawer_id'];

			$updateData = array();
			$total_balance = 0;
			$us_currencies = [
				//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
				'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
				'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
				'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
				'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
				'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
				'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
				'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
				'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
				'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
			];
			foreach ($us_currencies as $key => $value) {
				if (isset($input[$key])) {
					$updateData[$key . '_count'] = $input[$key];
					$row_total = $value['conv_value'] * $input[$key];
					$updateData[$key . '_value'] = $row_total;
					$total_balance += $row_total;
				} else {
					$updateData[$key . '_count'] = 0;
					$updateData[$key . '_value'] = 0.00;
				}
			}

			$updateData['expected_cash_drawer_fund'] = $expected_cash_drawer_fund;
			$updateData['total_balance'] = $total_balance;
			$updateData['updated_datetime'] = date("Y-m-d H:i:s");

			$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update($updateData);

			if ($is_update) {
				$success_msg = "Daily open updated successfully.";
				$adjustment_amount = '0.00';
				$adjustment_type = null;

				$isshow_dailyopen_pending = 2;
				/*$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
				//dd($cashd_res);
				if (empty($cashd_res)) {
					$isshow_dailyopen_pending = 1;
				}*/
				/*Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);
				Session::save();*/
				$request->attributes->add(['shared_isshow_dailyopen_pending' => $isshow_dailyopen_pending]);

				/* Log daily open event if cash drawer not connected start */
				$today_cdlog_res = DB::table('pu_store_cashdrawer_log')
					->select('action_type')
					->where('store_id', '=', $store_id)
					->where('store_user_id', '=', Auth::guard('store')->user()->store_user_id)
					->where('action', '=', 'Daily Open Re-check')
					->where('action_type', '=', 'Open')
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();
				if (empty($today_cdlog_res)) {
					$newrequest = new \Illuminate\Http\Request();
					$newrequest->setMethod('POST');
					$newrequest->request->add(['action' => 'Daily Open Re-check', 'action_type' => 'Open', 'actionNotes' => '', 'cd_amount' => 0, 'is_device_event' => 0]);
					$this->cashDrawerLog($newrequest);
				}
				/* Log daily open event if cash drawer not connected start */

				//dd($prev_day_cd_res);
				if (!empty($prev_day_cd_res)) {
					if ($prev_day_cd_res->current_balance == $total_balance) {
						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

						//if($is_update)
						//{
						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $total_balance;
						$insertCDData['current_balance'] = $total_balance;
						$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

						if ($insertIdCD) {
							$success_msg = "Your opening balance matches your previous closing balance, and this amount has been recorded for your store opening.";
							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
						//}
						//else
						//{
						//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						//}

					} else {
						$adjustment_amount = abs($total_balance - $prev_day_cd_res->current_balance);
						$adjustment_type = ($total_balance > $prev_day_cd_res->current_balance) ? 'increase' : 'decrease';
					}
				} elseif (empty($prev_day_cd_res) && $total_balance == 0) {

					$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

					if ($is_update) {
						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $total_balance;
						$insertCDData['current_balance'] = $total_balance;
						$insertCDData['admin_email'] = Auth::guard('store')->user()->store_user_email;
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

						if ($insertIdCD) {
							$success_msg = "This amount has been recorded for your store opening.";

							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
					}
				} else {
					$adjustment_amount = $total_balance;
					$adjustment_type = 'increase';
				}

				$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'no', 'adjustment_type' => $adjustment_type, 'adjustment_amount' => $adjustment_amount, 'updated_datetime' => date("Y-m-d H:i:s")]);

				//if($is_update)
				//{
				return redirect('store/cash-drawer-daily-open')->with(['manual_adjustment' => $success_msg]);
				//}
				//else
				//{
				//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
				//}

			}
		} elseif ($act_type == 'manually_adjust') {

			$store_cash_drawer_id = $input['store_cash_drawer_id'];

			$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('store_cash_drawer_id', $store_cash_drawer_id)->first();

			if (!empty($cashdoc_res)) {
				$is_deposit_adjusted = 'yes';
				$adjustment_reason = $input['adjustment_reason'];

				$updateData = array();
				$updateData['is_deposit_adjusted'] = $is_deposit_adjusted;
				$updateData['adjustment_reason'] = $adjustment_reason;

				$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update($updateData);

				if ($is_update) {
					$store_open_balance = '0.00';
					$store_open_balance = $cashdoc_res->total_balance;

					$insertCDData = array();
					$insertCDData['store_id'] = $store_id;
					$insertCDData['store_open_balance'] = $store_open_balance;
					$insertCDData['current_balance'] = $store_open_balance;
					$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
					$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

					$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

					if ($insertIdCD) {
						$isshow_dailyopen_pending = 2;
						/*$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
						//dd($cashd_res);
						if (empty($cashd_res)) {
							$isshow_dailyopen_pending = 1;
						}*/
						/*Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);
						Session::save();*/
						$request->attributes->add(['shared_isshow_dailyopen_pending' => $isshow_dailyopen_pending]);

						// cash drawer log - update discrepancy amount start.
						$action_notes = array(
							config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $cashdoc_res->expected_cash_drawer_fund,
							config('global.STORE_LOGS_ADDED_AMOUNT') => $cashdoc_res->total_balance,
							config('global.STORE_LOGS_ADJUSTED_AMOUNT') => $cashdoc_res->adjustment_amount,
							config('global.STORE_LOGS_UPDATED_AMOUNT') => $cashdoc_res->total_balance,
							config('global.STORE_LOGS_ADJUSTMENT_TYPE') => $cashdoc_res->adjustment_type,
							config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => $adjustment_reason
						);
						$last_cd_res = DB::table('pu_store_cashdrawer_log')
							->select('cashdrawer_log_id', 'available_balance')
							->whereIn('action', array('Daily Open', 'Daily Open Re-check'))
							->where('store_id', '=', $store_id)
							->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
							->orderBy('added_datetime', 'desc')
							->first();
						if (!empty($last_cd_res)) {
							$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['cd_amount' => 0, 'available_balance' => $store_open_balance, 'action_notes' => json_encode($action_notes)]);
						}
						// cash drawer log - update discrepancy amount end.

						// Trigger mail to manager about mismatch start.
						/*$storeManagers = StoreUser::selectRaw('store_user_id, store_id, store_user_email, first_name, last_name, phone, role_id, store_user_status, created_by, store_user_created_at')
							->where('store_id', $store_id)
							->where('role_id', 18)
							->where('store_user_status', '1')
							->get();

						if($storeManagers->count() > 0)
						{
							$store_dtl = DB::table('pu_stores')
								->select('store_code', 'store_name', 'store_email', 'store_phone', 'store_address1', 'store_address2', 'store_city', 'store_state', 'store_country', 'store_zipcode')
								->where('store_id','=',$store_id)
								->first();

							$sendMailStatus  = 'sucess';

							$STORE_MANAGER_NAME = $storeManagers->first_name;

							if(isset($storeManagers->last_name) && $storeManagers->last_name != ''){
								$STORE_MANAGER_NAME .= " ".$storeManagers->last_name;
							}

							$OtherData = [
								'toMail'=> $storeManagers->store_user_email,
								'STORE_MANAGER_NAME'=> $STORE_MANAGER_NAME,
								'IS_DEPOSIT_ADJUSTED'	=> $is_deposit_adjusted,
								'ADJUSTMENT_TYPE'		=> $cashdoc_res->adjustment_type,
								'ADJUSTMENT_REASON'		=> $adjustment_reason,
								'ADJUSTMENT_AMOUNT'		=> $cashdoc_res->adjustment_amount,
								'CASH_IN_DRAWER'		=> $cashdoc_res->total_balance,
								'CASH_SHOULD_BE_IN_DRAWER'		=> $cashdoc_res->total_balance + ($cashdoc_res->adjustment_amount * -1),
								'STORE_ID' => $store_id,
								'STORE_CODE' => $store_dtl->store_code,
								'STORE_NAME' => $store_dtl->store_name,
								'STORE_EMAIL' => $store_dtl->store_email,
								'STORE_PHONE' => $store_dtl->store_phone,
								'STORE_ADDRESS1' => $store_dtl->store_address1,
								'STORE_ADDRESS2' => $store_dtl->store_address2,
								'STORE_CITY' => $store_dtl->store_city,
								'STORE_STATE' => $store_dtl->store_state,
								'STORE_COUNTRY' => $store_dtl->store_country,
								'STORE_ZIPCODE' => $store_dtl->store_zipcode,
								'STORE_USER_ID' => Session::get('sess_storeuserid'),
								'STORE_USER_EMAIL' => Session::get('sess_storeuseremail'),
								'STORE_USER_FIRST_NAME' => Session::get('sess_storeusername'),
								'SITE_URL'	 			=> config('global.SITE_URL'),
								'SITE_TITLE' 			=> 'Maxaroma',
								'TOLL_FREE_NO' 			=> '',
								'CONTACT_MAIL' 			=> '',
								'CUSTOMER_SUPPORT_URL'	=> '',
								'FROM_EMAIL'			=> 'info@maxaroma.com',
								'FROM_NAME' 			=> 'Maxaroma',
							];

							foreach($storeManagers as $storeManager)
							{

								if(config('global.OMNISEND_PROG') !== false){
									OmanisendRequest('6854468f1732c89fb346462a',array(),$OtherData);
								}
							}

						}*/
						// Trigger mail to manager about mismatch end.

						$success_msg = 'Manual adjustment submitted successfully.';
						return redirect('store/cash-drawer')->with(['success' => $success_msg]);
					}
				}
			}

			//return redirect('store/cash-drawer')->with(['error' => 'Something went wrong.']);
			return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
		}

		return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
	}

	public function cashDrawerDailyClose(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		/*if(!session()->has('store_user_access_rights') || !is_array(session()->get('store_user_access_rights')) || !in_array('pos_cash_drawer_daily_close', session()->get('store_user_access_rights')))
		{
			return redirect('store/store-dashboard.html');
		}*/

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		$store_id = Session::get('sess_store_id');
		$store_user_id = Auth::guard('store')->user()->store_user_id;

		$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id', '=', $store_id)->first();
		$store_name = $get_store_name->store_name;

		$us_currencies = [
			//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
			'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
			'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
			'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
			'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
			'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
			'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
			'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
			'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
			'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
		];

		$act_type = 'add';
		$store_cash_drawer_id = '';
		$expected_cash_drawer_fund = 0;
		$total_balance = 0;
		$is_balance_matched = null;
		$is_deposit_adjusted = null;
		$adjustment_type = '';
		$adjustment_amount = '0.00';
		$adjustment_reason = '';
		$cash_drawer_fund = '0.00';
		$bank_deposit = '0.00';

		$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('type', 'close')->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();

		if (!empty($cashdoc_res)) {
			foreach ($us_currencies as $key => $value) {
				if ($cashdoc_res->{$key . '_count'} != 0) {
					$us_currencies[$key]['count'] = $cashdoc_res->{$key . '_count'};
					$us_currencies[$key]['row_total'] = $cashdoc_res->{$key . '_value'};
				}
			}

			$store_cash_drawer_id = $cashdoc_res->store_cash_drawer_id;
			$expected_cash_drawer_fund = $cashdoc_res->expected_cash_drawer_fund;
			$total_balance = $cashdoc_res->total_balance;
			$is_balance_matched = $cashdoc_res->is_balance_matched;
			$is_deposit_adjusted = $cashdoc_res->is_deposit_adjusted;
			$adjustment_type = $cashdoc_res->adjustment_type;
			$adjustment_amount = $cashdoc_res->adjustment_amount;
			$adjustment_reason = $cashdoc_res->adjustment_reason;
			$cash_drawer_fund = $cashdoc_res->cash_drawer_fund;
			$bank_deposit = $cashdoc_res->bank_deposit;

			if ($is_balance_matched == 'no' && $is_deposit_adjusted == null) {
				if ($request->act_type == 'update') {
					$act_type = 'update';
				} else {
					$act_type = 'manually_adjust';
				}
			} else {
				$act_type = 'view';
			}
		} else {
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}
		}

		$store_cash_drawer_fund = '0.00';
		$store_res = DB::table('pu_stores')->select('store_cash_drawer_fund')->where('store_id', $store_id)->first();

		if (!empty($store_res)) {
			$store_cash_drawer_fund = $store_res->store_cash_drawer_fund;
		}

		$metaData = (object) [
			'meta_title' => config('global.META_TITLE') . ' :: POS Cash Drawer :: Daily Close',
			'meta_description' => '',
			'meta_keywords' => '',
		];

		/* Canonical URL */
		$canonical_url = config('global.SITE_URL') . "store/cash-drawer-daily-close";
		//$this->PageData = $this->checkForMetaData($canonical_url, $metaData);
		$this->PageData['canonical_url'] = $canonical_url;

		## Breadcrumb
		$page_bread_crumb	= '';
		$page_bread_crumb 	.= '<a href="' . config('global.SITE_URL') . '">Home</a>';
		$page_bread_crumb 	.= '<span class="active">POS Cash Drawer</span>';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$is_noindex_nofollow_meta = 1;

		return view('pos.cashdrawerdailyclose', compact('page_bread_crumb', 'is_noindex_nofollow_meta', 'act_type', 'us_currencies', 'store_id', 'store_user_id', 'store_name', 'store_cash_drawer_id', 'expected_cash_drawer_fund', 'total_balance', 'cash_drawer_fund', 'bank_deposit', 'is_balance_matched', 'is_deposit_adjusted', 'adjustment_type', 'adjustment_amount', 'adjustment_reason', 'store_cash_drawer_fund'))->with($this->PageData);
	}

	public function saveCashDrawerDailyClose(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		//dd($request->all());
		$input = $request->all();

		$act_type = $input['act_type'];

		$store_id = $input['store_id'];

		if ($act_type == 'add') {
			$expected_cash_drawer_fund = 0;
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}

			$cash_drawer_fund = $input['cash_drawer_fund'];
			$bank_deposit = $input['bank_deposit'];

			$insertData = array();
			$insertData['store_id'] = $store_id;
			$insertData['type'] = $input['type'];
			$total_balance = 0;
			$us_currencies = [
				//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
				'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
				'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
				'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
				'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
				'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
				'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
				'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
				'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
				'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
			];
			foreach ($us_currencies as $key => $value) {
				if (isset($input[$key])) {
					$insertData[$key . '_count'] = $input[$key];
					$row_total = $value['conv_value'] * $input[$key];
					$insertData[$key . '_value'] = $row_total;
					$total_balance += $row_total;
				} else {
					$insertData[$key . '_count'] = 0;
					$insertData[$key . '_value'] = 0.00;
				}
			}

			$insertData['expected_cash_drawer_fund'] = $expected_cash_drawer_fund;
			$insertData['total_balance'] = $total_balance;
			$insertData['cash_drawer_fund'] = $cash_drawer_fund;
			$insertData['bank_deposit'] = $bank_deposit;
			$insertData['is_balance_matched'] = null;
			$insertData['is_deposit_adjusted'] = null;
			$insertData['admin_email'] = Session::get('sess_storeuseremail');
			$insertData['added_datetime'] = date("Y-m-d H:i:s");
			$insertData['updated_datetime'] = date("Y-m-d H:i:s");

			$insertId = DB::table('pu_store_cash_drawer_daily_open')->insertGetId($insertData);

			if ($insertId) {
				$success_msg = "Daily close submitted successfully.";
				$adjustment_amount = '0.00';
				$adjustment_type = null;

				$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('store_cash_drawer_id', $insertId)->first();

				/* Log daily open event if cash drawer not connected start */
				$today_cdlog_res = DB::table('pu_store_cashdrawer_log')
					->select('action_type')
					->where('store_id', '=', $store_id)
					->where('store_user_id', '=', Auth::guard('store')->user()->store_user_id)
					->where('action', '=', 'Daily Closeout')
					->where('action_type', '=', 'Open')
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();
				if (empty($today_cdlog_res)) {

					$action_notes = array(
						config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $prev_day_cd_res->current_balance,
						config('global.STORE_LOGS_ADDED_AMOUNT') => $cashdoc_res->total_balance,
						config('global.STORE_LOGS_ADJUSTED_AMOUNT') => $cashdoc_res->adjustment_amount,
						config('global.STORE_LOGS_UPDATED_AMOUNT') => $cashdoc_res->total_balance,
						//config('global.STORE_LOGS_ADJUSTMENT_TYPE') => '',
						//config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => '',
						config('global.STORE_LOGS_BANK_DEPOSIT') => $cashdoc_res->bank_deposit,
						config('global.STORE_LOGS_CASH_DRAWER_FUND') => $cashdoc_res->cash_drawer_fund,
					);

					$newrequest = new \Illuminate\Http\Request();
					$newrequest->setMethod('POST');
					//$newrequest->replace(['foo' => 'bar']);
					$newrequest->request->add(['action' => 'Daily Closeout', 'action_type' => 'Open', 'actionNotes' => json_encode($action_notes), 'cd_amount' => 0, 'is_device_event' => 0]);
					$this->cashDrawerLog($newrequest);
				}
				/* Log daily open event if cash drawer not connected start */

				$today_cd_res = DB::table('pu_store_cash_drawer')
					->select('*')
					->where('store_id', '=', $store_id)
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->orderBy('added_datetime', 'desc')
					->first();

				if (!empty($today_cd_res)) {
					if ($today_cd_res->current_balance == $total_balance) {

						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $insertId)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

						if ($is_update) {
							$updateCDData = array();
							$updateCDData['store_bank_deposite'] = $bank_deposit;
							$updateCDData['current_balance'] = $cash_drawer_fund;
							$updateCDData['updated_datetime'] = date("Y-m-d H:i:s");

							$is_update_CD = DB::table('pu_store_cash_drawer')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->update($updateCDData);

							if ($is_update_CD) {

								// cash drawer log - deduct bank deposit from available balance.
								if ($bank_deposit != 0) {
									$last_cd_res = DB::table('pu_store_cashdrawer_log')
										->select('cashdrawer_log_id', 'available_balance')
										->whereIn('action', array('Daily Closeout', 'Daily Closeout Re-check'))
										->where('store_id', '=', $store_id)
										->orderBy('added_datetime', 'desc')
										->first();
									if (!empty($last_cd_res)) {
										//$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['available_balance' => ($cash_drawer_fund - $bank_deposit)]);
										$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['available_balance' => ($input['grand_total'] - $bank_deposit)]);
									}
								}
								// cash drawer log - deduct bank deposit from available balance.

								// cash drawer log - log bank deposit and cash drawer fund.
								/*$action_notes = array(
									'bankDeposit' => $bank_deposit,
									'cashDrawerFund' => $cash_drawer_fund,
								);*/
								$last_cd_res = DB::table('pu_store_cashdrawer_log')
									->select('cashdrawer_log_id', 'available_balance')
									->whereIn('action', array('Daily Closeout', 'Daily Closeout Re-check'))
									->where('store_id', '=', $store_id)
									->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
									->orderBy('added_datetime', 'desc')
									->first();
								if (!empty($last_cd_res)) {
									$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['action_notes' => json_encode($action_notes)]);
								}
								// cash drawer log - log bank deposit and cash drawer fund.

								$success_msg = "Your closing balance matches the system cash total, and this amount has been recorded for your store closing. Store has been successfully closed";
								return redirect('store/cash-drawer')->with(['success' => $success_msg]);
							} else {
								return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
							}
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						$adjustment_amount = abs($total_balance - $today_cd_res->current_balance);
						$adjustment_type = ($total_balance > $today_cd_res->current_balance) ? 'increase' : 'decrease';

						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $insertId)->update(['is_balance_matched' => 'no', 'adjustment_type' => $adjustment_type, 'adjustment_amount' => $adjustment_amount, 'updated_datetime' => date("Y-m-d H:i:s")]);

						//if($is_update)
						//{
						return redirect('store/cash-drawer-daily-close')->with(['manual_adjustment' => $success_msg]);
						//}
						//else
						//{
						//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						//}

					}
				}

				return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
			}
		} elseif ($act_type == 'update') {
			$expected_cash_drawer_fund = 0;
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}

			$store_cash_drawer_id = $input['store_cash_drawer_id'];
			$cash_drawer_fund = $input['cash_drawer_fund'];
			$bank_deposit = $input['bank_deposit'];

			$updateData = array();
			$total_balance = 0;
			$us_currencies = [
				//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
				'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
				'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
				'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
				'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
				'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
				'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
				'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
				'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
				'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
			];
			foreach ($us_currencies as $key => $value) {
				if (isset($input[$key])) {
					$updateData[$key . '_count'] = $input[$key];
					$row_total = $value['conv_value'] * $input[$key];
					$updateData[$key . '_value'] = $row_total;
					$total_balance += $row_total;
				} else {
					$updateData[$key . '_count'] = 0;
					$updateData[$key . '_value'] = 0.00;
				}
			}

			$updateData['expected_cash_drawer_fund'] = $expected_cash_drawer_fund;
			$updateData['total_balance'] = $total_balance;
			$updateData['cash_drawer_fund'] = $cash_drawer_fund;
			$updateData['bank_deposit'] = $bank_deposit;
			$updateData['updated_datetime'] = date("Y-m-d H:i:s");

			$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update($updateData);

			if ($is_update) {
				$success_msg = "Daily close updated successfully.";
				$adjustment_amount = '0.00';
				$adjustment_type = null;

				/* Log daily open event if cash drawer not connected start */
				$today_cdlog_res = DB::table('pu_store_cashdrawer_log')
					->select('action_type')
					->where('store_id', '=', $store_id)
					->where('store_user_id', '=', Auth::guard('store')->user()->store_user_id)
					->where('action', '=', 'Daily Closeout Re-check')
					->where('action_type', '=', 'Open')
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();
				if (empty($today_cdlog_res)) {
					$newrequest = new \Illuminate\Http\Request();
					$newrequest->setMethod('POST');
					$newrequest->request->add(['action' => 'Daily Closeout Re-check', 'action_type' => 'Open', 'actionNotes' => '', 'cd_amount' => 0, 'is_device_event' => 0]);
					$this->cashDrawerLog($newrequest);
				}
				/* Log daily open event if cash drawer not connected start */

				$today_cd_res = DB::table('pu_store_cash_drawer')
					->select('*')
					->where('store_id', '=', $store_id)
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();

				if (!empty($today_cd_res)) {
					if ($today_cd_res->current_balance == $total_balance) {
						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s")]);

						//if($is_update)
						//{
						$updateCDData = array();
						$updateCDData['store_bank_deposite'] = $bank_deposit;
						$updateCDData['current_balance'] = $cash_drawer_fund;
						$updateCDData['updated_datetime'] = date("Y-m-d H:i:s");

						$is_update_CD = DB::table('pu_store_cash_drawer')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->update($updateCDData);

						if ($is_update_CD) {
							// cash drawer log - deduct bank deposit from available balance.
							if ($bank_deposit != 0) {
								$last_cd_res = DB::table('pu_store_cashdrawer_log')
									->select('cashdrawer_log_id', 'available_balance')
									->whereIn('action', array('Daily Closeout', 'Daily Closeout Re-check'))
									->where('store_id', '=', $store_id)
									->orderBy('added_datetime', 'desc')
									->first();
								if (!empty($last_cd_res)) {
									$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['available_balance' => ($current_balance - $bank_deposit)]);
								}
							}
							// cash drawer log - deduct bank deposit from available balance.

							// cash drawer log - log bank deposit and cash drawer fund.
							$action_notes = array(
								'bankDeposit' => $bank_deposit,
								'cashDrawerFund' => $cash_drawer_fund,
							);
							$last_cd_res = DB::table('pu_store_cashdrawer_log')
								->select('cashdrawer_log_id', 'available_balance')
								->whereIn('action', array('Daily Closeout', 'Daily Closeout Re-check'))
								->where('store_id', '=', $store_id)
								->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
								->orderBy('added_datetime', 'desc')
								->first();
							if (!empty($last_cd_res)) {
								$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['action_notes' => json_encode($action_notes)]);
							}
							// cash drawer log - log bank deposit and cash drawer fund.

							$success_msg = "Your closing balance matches the system cash total, and this amount has been recorded for your store closing. Store has been successfully closed";
							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
						//}
						//else
						//{
						//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						//}

					} else {
						$adjustment_amount = abs($total_balance - $today_cd_res->current_balance);
						$adjustment_type = ($total_balance > $today_cd_res->current_balance) ? 'increase' : 'decrease';

						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'no', 'adjustment_type' => $adjustment_type, 'adjustment_amount' => $adjustment_amount, 'updated_datetime' => date("Y-m-d H:i:s")]);

						//if($is_update)
						//{
						return redirect('store/cash-drawer-daily-close')->with(['manual_adjustment' => $success_msg]);
						//}
						//else
						//{
						//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						//}
					}
				}

				return redirect('store/cash-drawer')->with(['success' => $success_msg]);
			} else {
				return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
			}
		} elseif ($act_type == 'manually_adjust') {

			$store_cash_drawer_id = $input['store_cash_drawer_id'];

			$is_deposit_adjusted = 'yes';
			$adjustment_reason = $input['adjustment_reason'];

			$updateData = array();
			$updateData['is_deposit_adjusted'] = $is_deposit_adjusted;
			$updateData['adjustment_reason'] = $adjustment_reason;

			$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update($updateData);

			if ($is_update) {
				$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('type', 'close')->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();

				if (!empty($cashdoc_res)) {
					$current_balance = '0.00';
					$current_balance = $cashdoc_res->cash_drawer_fund;

					$updateCDData = array();
					$updateCDData['store_bank_deposite'] = $cashdoc_res->bank_deposit;
					$updateCDData['current_balance'] = $current_balance;
					$updateCDData['updated_datetime'] = date("Y-m-d H:i:s");

					$is_update_CD = DB::table('pu_store_cash_drawer')->where('store_id', $store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->update($updateCDData);

					if ($is_update_CD) {
						// cash drawer log - update discrepancy amount start.
						$action_notes = array(
							config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $cashdoc_res->expected_cash_drawer_fund,
							config('global.STORE_LOGS_ADDED_AMOUNT') => $cashdoc_res->total_balance,
							config('global.STORE_LOGS_ADJUSTED_AMOUNT') => $cashdoc_res->adjustment_amount,
							config('global.STORE_LOGS_UPDATED_AMOUNT') => $cashdoc_res->total_balance,
							config('global.STORE_LOGS_ADJUSTMENT_TYPE') => $cashdoc_res->adjustment_type,
							config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => $cashdoc_res->adjustment_reason,
							config('global.STORE_LOGS_BANK_DEPOSIT') => $cashdoc_res->bank_deposit,
							config('global.STORE_LOGS_CASH_DRAWER_FUND') => $cashdoc_res->cash_drawer_fund,
						);
						$last_cd_res = DB::table('pu_store_cashdrawer_log')
							->select('cashdrawer_log_id', 'available_balance')
							->whereIn('action', array('Daily Closeout', 'Daily Closeout Re-check'))
							->where('store_id', '=', $store_id)
							->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
							->orderBy('added_datetime', 'desc')
							->first();
						if (!empty($last_cd_res)) {
							$is_update =  DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['cd_amount' => 0, 'available_balance' => ($current_balance), 'action_notes' => json_encode($action_notes)]);
						}
						// cash drawer log - update discrepancy amount end.

						// Trigger mail to manager about mismatch start.
						/*$storeManagers = StoreUser::selectRaw('store_user_id, store_id, store_user_email, first_name, last_name, phone, role_id, store_user_status, created_by, store_user_created_at')
							->where('store_id', $store_id)
							->where('role_id', 18)
							->where('store_user_status', '1')
							->get();

						if($storeManagers->count() > 0)
						{
							$store_dtl = DB::table('pu_stores')
								->select('store_code', 'store_name', 'store_email', 'store_phone', 'store_address1', 'store_address2', 'store_city', 'store_state', 'store_country', 'store_zipcode')
								->where('store_id','=',$store_id)
								->first();

							$sendMailStatus  = 'sucess';

							$STORE_MANAGER_NAME = $storeManagers->first_name;

							if(isset($storeManagers->last_name) && $storeManagers->last_name != ''){
								$STORE_MANAGER_NAME .= " ".$storeManagers->last_name;
							}

							$OtherData = [
								'toMail'=> $storeManagers->store_user_email,
								'STORE_MANAGER_NAME'=> $STORE_MANAGER_NAME,
								'IS_DEPOSIT_ADJUSTED'	=> $is_deposit_adjusted,
								'ADJUSTMENT_TYPE'		=> $cashdoc_res->adjustment_type,
								'ADJUSTMENT_REASON'		=> $adjustment_reason,
								'ADJUSTMENT_AMOUNT'		=> $cashdoc_res->adjustment_amount,
								'CASH_IN_DRAWER'		=> $cashdoc_res->total_balance,
								'CASH_SHOULD_BE_IN_DRAWER'		=> $cashdoc_res->total_balance + ($cashdoc_res->adjustment_amount * -1),
								'BANK_DEPOSITE_AMOUNT'	=> $cashdoc_res->bank_deposit,
								'STORE_ID' => $store_id,
								'STORE_CODE' => $store_dtl->store_code,
								'STORE_NAME' => $store_dtl->store_name,
								'STORE_EMAIL' => $store_dtl->store_email,
								'STORE_PHONE' => $store_dtl->store_phone,
								'STORE_ADDRESS1' => $store_dtl->store_address1,
								'STORE_ADDRESS2' => $store_dtl->store_address2,
								'STORE_CITY' => $store_dtl->store_city,
								'STORE_STATE' => $store_dtl->store_state,
								'STORE_COUNTRY' => $store_dtl->store_country,
								'STORE_ZIPCODE' => $store_dtl->store_zipcode,
								'STORE_USER_ID' => Session::get('sess_storeuserid'),
								'STORE_USER_EMAIL' => Session::get('sess_storeuseremail'),
								'STORE_USER_FIRST_NAME' => Session::get('sess_storeusername'),
								'SITE_URL'	 			=> config('global.SITE_URL'),
								'SITE_TITLE' 			=> 'Maxaroma',
								'TOLL_FREE_NO' 			=> '',
								'CONTACT_MAIL' 			=> '',
								'CUSTOMER_SUPPORT_URL'	=> '',
								'FROM_EMAIL'			=> 'info@maxaroma.com',
								'FROM_NAME' 			=> 'Maxaroma',
							];

							foreach($storeManagers as $storeManager)
							{
								if(config('global.OMNISEND_PROG') !== false){
									OmanisendRequest('685446ca9aa22e9b6625189d',array(),$OtherData);
								}
							}

						}*/
						// Trigger mail to manager about mismatch end.

						$success_msg = 'Manual adjustment submitted successfully.';
						return redirect('store/cash-drawer')->with(['success' => $success_msg]);
					}
				}
			}

			//return redirect('store/cash-drawer')->with(['error' => 'Something went wrong while.']);
			return redirect()->back()->withErrors(['error' => 'Something went wrong while.']);
		}

		return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
	}

	public function cashDrawerManualOpen(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		$store_id = Session::get('sess_store_id');
		$store_user_id = Auth::guard('store')->user()->store_user_id;

		$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id', '=', $store_id)->first();
		$store_name = $get_store_name->store_name;

		//$transaction_type = '';
		//$transaction_amount = '';
		$transaction_remark = '';
		$success_msg = '';
		if ($request->method() == 'POST') {
			$act_type = 'view';
			$transaction_remark = $request->transaction_remark;
			$success_msg = "Details submitted successfully.";
		} else {
			$act_type = 'add';
		}

		$us_currencies = [
			//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
			'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
			'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
			'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
			'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
			'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
			'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
			'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
			'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
			'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
		];

		$act_type = 'add';
		$store_cash_drawer_id = '';
		$expected_cash_drawer_fund = 0;
		$total_balance = 0;
		$current_balance = 0;
		$is_balance_matched = null;
		$is_deposit_adjusted = null;
		$adjustment_type = '';
		$adjustment_amount = '0.00';
		$adjustment_reason = '';
		$store_cash_manual = $set_store_cash_manual = $store_open_balance = 0;

		$cashdoc_res = DB::table('pu_store_cash_drawer_daily_open')->select('*')->where('store_id', $store_id)->where('type', 'open')->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();

		if (!empty($cashdoc_res)) {
			foreach ($us_currencies as $key => $value) {
				if ($cashdoc_res->{$key . '_count'} != 0) {
					$us_currencies[$key]['count'] = $cashdoc_res->{$key . '_count'};
					$us_currencies[$key]['row_total'] = $cashdoc_res->{$key . '_value'};
				}
			}

			$store_cash_drawer_id = $cashdoc_res->store_cash_drawer_id;
			$expected_cash_drawer_fund = $cashdoc_res->expected_cash_drawer_fund;
			$total_balance = $cashdoc_res->total_balance;
			$is_balance_matched = $cashdoc_res->is_balance_matched;
			$is_deposit_adjusted = $cashdoc_res->is_deposit_adjusted;
			$adjustment_type = $cashdoc_res->adjustment_type;
			$adjustment_amount = $cashdoc_res->adjustment_amount;
			$adjustment_reason = $cashdoc_res->adjustment_reason;

			$get_manual_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($get_manual_res)) {

				$current_balance = $get_manual_res->current_balance;

				$store_cash_manual = $get_manual_res->store_cash_manual;
				$store_open_balance = $get_manual_res->store_open_balance;

				if($get_manual_res->store_cash_manual < 0){
					$set_store_cash_manual = str_replace("-", "", $get_manual_res->store_cash_manual);
					$set_store_cash_manual = "-$".$set_store_cash_manual;
				}else{
					$set_store_cash_manual = "$".$get_manual_res->store_cash_manual;
				}
			}

		} else {
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($prev_day_cd_res)) {
				$current_balance = $prev_day_cd_res->current_balance;
				$expected_cash_drawer_fund = $prev_day_cd_res->current_balance;
			}
		}

		$metaData = (object) [
			'meta_title' => config('global.META_TITLE') . ' :: Cash Drawer :: Daily Open',
			'meta_description' => '',
			'meta_keywords' => '',
		];

		/* Canonical URL */
		$canonical_url = config('global.SITE_URL') . "store/cash-drawer-manual-open";
		//$this->PageData = $this->checkForMetaData($canonical_url, $metaData);
		$this->PageData['canonical_url'] = $canonical_url;

		## Breadcrumb
		$page_bread_crumb	= '';
		$page_bread_crumb 	.= '<a href="' . config('global.SITE_URL') . '">Home</a>';
		$page_bread_crumb 	.= '<span class="active">POS Cash Drawer</span>';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$is_noindex_nofollow_meta = 1;

		return view('pos.cashdrawermanualopen', compact('store_id', 'store_user_id', 'store_name', 'page_bread_crumb', 'is_noindex_nofollow_meta', 'act_type', 'transaction_remark', 'success_msg', 'us_currencies', 'store_id', 'store_user_id', 'store_name', 'store_cash_drawer_id', 'expected_cash_drawer_fund', 'total_balance', 'current_balance', 'is_balance_matched', 'is_deposit_adjusted', 'adjustment_type', 'adjustment_amount', 'adjustment_reason', 'store_cash_manual', 'store_open_balance', 'set_store_cash_manual'))->with($this->PageData);

	}

	public function saveCashDrawerManualOpen(Request $request)
	{

		if (!Session::has('sess_storeuserid')) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('store/store-dashboard.html');
		}

		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js', 'StarWebPrintBuilder.js', 'StarBarcodeEncoder.js', 'StarWebPrintExtManager.js', 'StarWebPrintDisplayBuilder.js', 'StarWebPrintTrader.js', 'pos_cashdrawer.js'];

		//dd($request->all());
		$input = $request->all();

		$act_type = $input['act_type'];

		$store_id = $input['store_id'];

		$adjustment_type = '';

		if ($act_type == 'add') {

			$insertData = array();
			$insertData['store_id'] = $store_id;
			$insertData['store_user_id'] = Session::get('sess_storeuserid');
			$insertData['store_user_email'] = Session::get('sess_storeuseremail');
			//$insertData['cd_amount'] = $input['transaction_amount'];
			//$insertData['transaction_type'] = $input['transaction_type'];
			$insertData['cd_amount'] = 0;
			$insertData['transaction_type'] = 'credit';
			$insertData['available_balance'] = '';
			$insertData['remarks'] = json_encode(['remark' => 'Manual Open - ' . $input['transaction_remark']]);
			$insertData['orders_id'] = 0;
			$insertData['added_datetime'] = date("Y-m-d H:i:s");

			$insertId = DB::table('pu_store_cash_drawer_log')->insertGetId($insertData);

			$expected_cash_drawer_fund = 0;
			$total_balance = 0;
			$prev_day_cd_res = DB::table('pu_store_cash_drawer')
				->select('*')
				->where('store_id', '=', $store_id)
				->orderBy('added_datetime', 'desc')
				->first();

			$today_date = date('m-d-Y');
			if ($today_date == date('m-d-Y', strtotime($prev_day_cd_res->added_datetime))) {
				$current_balance = $prev_day_cd_res->current_balance;
			} else {
				$current_balance = $prev_day_cd_res->current_balance;
			}

			$getAllManualOpenForDay = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $store_id)->where('store_cash_manual', '!=', '')->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->orderBy('added_datetime', 'desc')->first();
			//->get();

			$total_store_cash_manual = 0;
			if (!empty($getAllManualOpenForDay)) {
				//foreach ($getAllManualOpenForDay as $ManualOpenForDay) {
					$total_store_cash_manual = $getAllManualOpenForDay->store_cash_manual;
				//}
			}

			if (!empty($prev_day_cd_res)) {
				$total_balance = $prev_day_cd_res->current_balance - $prev_day_cd_res->store_cash_sales;
			}
			$total_balance = $total_balance + (float)$prev_day_cd_res->store_cash_refunds + (float)$prev_day_cd_res->store_bank_deposite;

			$expected_cash_drawer_fund = $input['expected_cash_drawer_fund'];
			$store_cash_drawer_id = $input['store_cash_drawer_id'];

			$updateData = array();
			$manual_balance = $store_cash_manual = 0;

			/*if($input['store_manual_open_cash'] != ''){
				$manual_balance = $input['store_manual_open_cash'];
				$store_cash_manual = $input['store_manual_open_cash'];
			}*/

			$us_currencies = [
				//'pennies'	=> ['label' => 'Pennies', 'conv_value' => '0.01', 'count' => '', 'row_total' => ''],
				'nickels'	=> ['label' => 'Nickels', 'conv_value' => '0.05', 'count' => '', 'row_total' => ''],
				'dimes'		=> ['label' => 'Dimes', 'conv_value' => '0.10', 'count' => '', 'row_total' => ''],
				'quarters'	=> ['label' => 'Quarters', 'conv_value' => '0.25', 'count' => '', 'row_total' => ''],
				'ones'		=> ['label' => 'Ones', 'conv_value' => '1.00', 'count' => '', 'row_total' => ''],
				'fives'		=> ['label' => 'Fives', 'conv_value' => '5.00', 'count' => '', 'row_total' => ''],
				'tens'		=> ['label' => 'Tens', 'conv_value' => '10.00', 'count' => '', 'row_total' => ''],
				'twenties'	=> ['label' => 'Twenties', 'conv_value' => '20.00', 'count' => '', 'row_total' => ''],
				'fifties'	=> ['label' => 'Fifties', 'conv_value' => '50.00', 'count' => '', 'row_total' => ''],
				'hundreds'	=> ['label' => 'Hundreds', 'conv_value' => '100.00', 'count' => '', 'row_total' => '']
			];
			foreach ($us_currencies as $key => $value) {
				if (isset($input[$key])) {
					$updateData[$key . '_count'] = $input[$key];
					$row_total = $value['conv_value'] * $input[$key];
					$updateData[$key . '_value'] = $row_total;
					$manual_balance += $row_total;
				} else {
					$updateData[$key . '_count'] = 0;
					$updateData[$key . '_value'] = 0.00;
				}
			}
			//dd((float)$total_balance);
			//dd((float)$manual_balance);
			//dd((float)$total_balance - (float)$manual_balance);

			$set_total_balance = $set_manual_balance = $set_daily_open_total = 0;

			//dd($manual_balance);
			//dd($total_balance);

			if($manual_balance > $total_balance){
				$set_daily_open_total = (float)$manual_balance;
				$set_total_balance = (float)$manual_balance - (float)$total_balance;
				$set_manual_balance = $set_total_balance;
				$set_total_balance = (float)$total_balance + (float)$set_total_balance;
				$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_cash_refunds;
				$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_bank_deposite;
				$adjustment_type = 'increase';

				//$total_store_cash_manual = (float)$total_store_cash_manual + (float)($set_manual_balance);
				$total_store_cash_manual = (float)$input['store_manual_open_cash'] + (float)($set_manual_balance);
			}
			else if($manual_balance < $total_balance){
				$set_daily_open_total = (float)$manual_balance;
				$set_total_balance = (float)$total_balance - ((float)$manual_balance);
				$set_manual_balance = (float)(-$set_total_balance);
				$set_total_balance = (float)$total_balance - (float)$set_total_balance;
				$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_cash_refunds;
				$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_bank_deposite;
				$adjustment_type = 'decrease';

				//$total_store_cash_manual = (float)$total_store_cash_manual + (float)($set_manual_balance);
				$total_store_cash_manual = (float)$input['store_manual_open_cash'] + (float)($set_manual_balance);
			}else{
				//$set_total_balance = (float)$manual_balance;
				//$set_manual_balance = $set_total_balance;

				if($input['store_manual_open_cash'] != ''){
					if($prev_day_cd_res->store_cash_manual == $input['store_manual_open_cash']){
						$set_daily_open_total = (float)$manual_balance;
						$set_manual_balance = (float)($input['store_manual_open_cash']);
						$set_total_balance = (float)$total_balance;
						$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_cash_refunds;
						$set_total_balance = (float)$set_total_balance - (float)$prev_day_cd_res->store_bank_deposite;

						$total_store_cash_manual = (float)$set_manual_balance;
					}
				}
				else{
					$set_daily_open_total = 0.00;
					$set_total_balance = (float)$total_balance;
					$set_manual_balance = 0.00;

					$total_store_cash_manual = 0.00;
				}
			}

			$updateData['expected_cash_drawer_fund'] = $expected_cash_drawer_fund;
			$updateData['total_balance'] = $set_daily_open_total;
			$updateData['adjustment_reason'] = $input['transaction_remark'];
			$updateData['updated_datetime'] = date("Y-m-d H:i:s");
			//dd($updateData);
			$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update($updateData);

			if ($is_update) {
				$success_msg = "Manual open submitted successfully.";
				$adjustment_amount = '0.00';
				$adjustment_type = null;

				/* Log daily open event if cash drawer not connected start */
				$today_cdlog_res = DB::table('pu_store_cashdrawer_log')
					->select('action_type')
					->where('store_id', '=', $store_id)
					->where('store_user_id', '=', Auth::guard('store')->user()->store_user_id)
					->where('action', '=', 'Daily Open Re-check')
					->where('action_type', '=', 'Open')
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->first();
					//dd($today_cdlog_res);
				//if (empty($today_cdlog_res)) {
					$newrequest = new \Illuminate\Http\Request();
					$newrequest->setMethod('POST');

					if($manual_balance > $total_balance){
						$adjustment_type = 'increase';
					}
					else if($manual_balance < $total_balance){
						$adjustment_type = 'decrease';
					}

					$action_notes = array();
					if($adjustment_type == 'increase'){
						$action_notes = array(
							config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $prev_day_cd_res->current_balance,
							config('global.STORE_LOGS_ADDED_AMOUNT') => $set_manual_balance,
							config('global.STORE_LOGS_ADJUSTED_AMOUNT') => ((float)$prev_day_cd_res->current_balance + (float)$set_manual_balance),
							config('global.STORE_LOGS_UPDATED_AMOUNT') => ((float)$set_manual_balance + (float)$prev_day_cd_res->current_balance),
							config('global.STORE_LOGS_ADJUSTMENT_TYPE') => $adjustment_type,
							config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => $input['transaction_remark']
						);
					}
					else if($adjustment_type == 'decrease'){
						$action_notes = array(
							config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $prev_day_cd_res->current_balance,
							config('global.STORE_LOGS_DEDUCTED_AMOUNT') => $set_manual_balance,
							config('global.STORE_LOGS_ADJUSTED_AMOUNT') => ((float)$prev_day_cd_res->current_balance + (float)$set_manual_balance),
							config('global.STORE_LOGS_UPDATED_AMOUNT') => ((float)$prev_day_cd_res->current_balance + (float)$set_manual_balance),
							config('global.STORE_LOGS_ADJUSTMENT_TYPE') => $adjustment_type,
							config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => $input['transaction_remark']
						);
					}
					else{
						$action_notes = array(
							config('global.STORE_LOGS_PREVIOUS_AMOUNT') => $prev_day_cd_res->current_balance,
							config('global.STORE_LOGS_ADDED_AMOUNT') => $set_manual_balance,
							config('global.STORE_LOGS_ADJUSTED_AMOUNT') => ((float)$prev_day_cd_res->current_balance + (float)$set_manual_balance),
							config('global.STORE_LOGS_UPDATED_AMOUNT') => ((float)$set_manual_balance + (float)$prev_day_cd_res->current_balance),
							config('global.STORE_LOGS_ADJUSTMENT_TYPE') => '',
							config('global.STORE_LOGS_REASON_FOR_ADJUSTMENT') => $input['transaction_remark']
						);
					}
					//dd($action_notes);

					if($manual_balance > $total_balance || $manual_balance < $total_balance){
						$newrequest->request->add(['action' => 'Manual', 'action_type' => 'Open', 'actionNotes' => json_encode($action_notes), 'cd_amount' => $set_manual_balance, 'is_device_event' => 0]);
						$this->cashDrawerLog($newrequest);
					}
				//}
				/* Log daily open event if cash drawer not connected start */

				//dd($prev_day_cd_res);
				if (!empty($prev_day_cd_res)) {
					if (((float)$prev_day_cd_res->current_balance - (float)$prev_day_cd_res->store_cash_sales) == $set_total_balance) {
						$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s"), 'adjustment_reason' => $input['transaction_remark']]);

						//if($is_update)
						//{
						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $prev_day_cd_res->store_open_balance;
						//$insertCDData['store_cash_manual'] = $set_manual_balance;
						$insertCDData['store_cash_manual'] = $total_store_cash_manual;
						$insertCDData['store_cash_sales'] = $prev_day_cd_res->store_cash_sales;
						$insertCDData['store_cash_refunds'] = $prev_day_cd_res->store_cash_refunds;
						$insertCDData['store_bank_deposite'] = $prev_day_cd_res->store_bank_deposite;
						$insertCDData['current_balance'] = $set_total_balance + $prev_day_cd_res->store_cash_sales;
						$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						if ($today_date == date('m-d-Y', strtotime($prev_day_cd_res->added_datetime))) {
							$insertIdCD =  DB::table('pu_store_cash_drawer')->where('store_cash_drawer_id', $prev_day_cd_res->store_cash_drawer_id)->update($insertCDData);
						}
						else{
							$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);
						}

						if ($insertIdCD) {
							$success_msg = "Manual open submitted successfully.";
							//return redirect('store/cash-drawer-manual-open?logId=' . $insertId)->with(['success' => $success_msg]);
							//return redirect('store/cash-drawer-manual-open')->with(['success' => $success_msg]);
							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						$adjustment_amount = abs($set_total_balance - ((float)$prev_day_cd_res->current_balance - (float)$prev_day_cd_res->store_cash_sales));
						$adjustment_type = ($set_total_balance > ((float)$prev_day_cd_res->current_balance - (float)$prev_day_cd_res->store_cash_sales)) ? 'increase' : 'decrease';

						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $prev_day_cd_res->store_open_balance;
						//$insertCDData['store_cash_manual'] = $set_manual_balance;
						$insertCDData['store_cash_manual'] = $total_store_cash_manual;
						$insertCDData['store_cash_sales'] = $prev_day_cd_res->store_cash_sales;
						$insertCDData['store_cash_refunds'] = $prev_day_cd_res->store_cash_refunds;
						$insertCDData['store_bank_deposite'] = $prev_day_cd_res->store_bank_deposite;
						$insertCDData['current_balance'] = $set_total_balance + $prev_day_cd_res->store_cash_sales;
						$insertCDData['admin_email'] = Session::get('sess_storeuseremail');
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						if ($today_date == date('m-d-Y', strtotime($prev_day_cd_res->added_datetime))) {
							$insertIdCD =  DB::table('pu_store_cash_drawer')->where('store_cash_drawer_id', $prev_day_cd_res->store_cash_drawer_id)->update($insertCDData);
						}
						else{
							$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);
						}
					}
				} elseif (empty($prev_day_cd_res) && $manual_balance == 0) {

					$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'yes', 'updated_datetime' => date("Y-m-d H:i:s"), 'adjustment_reason' => $input['transaction_remark']]);

					if ($is_update) {
						$insertCDData = array();
						$insertCDData['store_id'] = $store_id;
						$insertCDData['store_open_balance'] = $manual_balance;
						$insertCDData['current_balance'] = $manual_balance;
						$insertCDData['admin_email'] = Auth::guard('store')->user()->store_user_email;
						$insertCDData['added_datetime'] = date("Y-m-d H:i:s");

						$insertIdCD = DB::table('pu_store_cash_drawer')->insertGetId($insertCDData);

						if ($insertIdCD) {
							$success_msg = "Manual open submitted successfully.";

							//return redirect('store/cash-drawer-manual-open?logId=' . $insertId)->with(['success' => $success_msg]);
							//return redirect('store/cash-drawer-manual-open')->with(['success' => $success_msg]);
							return redirect('store/cash-drawer')->with(['success' => $success_msg]);
						} else {
							return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
						}
					} else {
						return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
					}
				} else {
					$adjustment_amount = $total_balance;
					$adjustment_type = 'increase';
				}

				$is_update =  DB::table('pu_store_cash_drawer_daily_open')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['is_balance_matched' => 'no', 'adjustment_type' => $adjustment_type, 'adjustment_amount' => $adjustment_amount, 'updated_datetime' => date("Y-m-d H:i:s"), 'adjustment_reason' => $input['transaction_remark']]);

				//if($is_update)
				//{
				//return redirect('store/cash-drawer-manual-open?logId=' . $insertId)->with(['success' => $success_msg]);
				//return redirect('store/cash-drawer-manual-open')->with(['success' => $success_msg]);
				return redirect('store/cash-drawer')->with(['success' => $success_msg]);
				//}
				//else
				//{
				//	return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
				//}

			}

			//return redirect('store/cash-drawer')->with(['error' => 'Something went wrong.']);
			return redirect()->back()->withErrors(['error' => 'Something went wrong.']);

			/*if ($insertId) {
				$success_msg = "Manual open submitted successfully.";
				return redirect('store/cash-drawer-manual-open?logId=' . $insertId)->with(['success' => $success_msg]);
			}*/

			return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
		}

		return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
	}

	public function cashDrawerLog(Request $request)
	{

		//error_reporting(E_ALL);
		//ini_set("display_errors", 1);

		if (!Auth::guard('store')->user()) {
			return 0;
		}

		//if(!Auth::guard('store')->check()  || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return 0;
		}

		$action = '';
		if (isset($request->action) && $request->action != '') {
			$action = $request->action;
		}
		$action_type = $request->action_type;
		$cd_amount = $request->cd_amount;

		$store_id = Auth::guard('store')->user()->store_id;
		$store_user_id = Auth::guard('store')->user()->store_user_id;
		$store_user_email = Auth::guard('store')->user()->store_user_email;

		$variables = array();
		$variables['action'] = $action;
		$variables['action_type'] = $action_type;
		$variables['store_id'] = $store_id;
		$variables['store_user_id'] = $store_user_id;
		$variables['store_user_email'] = $store_user_email;

		if ($action_type == 'Close') {
			$last_cd_res = DB::table('pu_store_cashdrawer_log')
				->select('action_type')
				->where('store_id', '=', $store_id)
				->where('store_user_id', '=', $store_user_id)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($last_cd_res)) {
				$variables['last_cd_res'] = $last_cd_res->action_type;
				if ($last_cd_res->action_type == "Close") {
					return 1;
				}
			} elseif (empty($last_cd_res)) {
				return 1;
			}
		}

		if ($action_type == 'Open' && $action == 'Order' && isset($request->actionNotes) && $request->actionNotes != '') {
			$last_cd_res = DB::table('pu_store_cashdrawer_log')
				->select('cashdrawer_log_id')
				->where('store_id', '=', $store_id)
				->where('store_user_id', '=', $store_user_id)
				->where('action', '=', 'Order')
				->where('action_type', '=', 'Open')
				->where('action_notes', '=', $request->actionNotes)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($last_cd_res)) {
				DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['is_device_event' => '0', 'added_datetime' => date('Y-m-d H:i:s')]);
				return 1;
			}
		}

		if ($action_type == 'Close' && $action == 'Order' && isset($request->actionNotes) && $request->actionNotes != '') {
			$last_cd_res = DB::table('pu_store_cashdrawer_log')
				->select('cashdrawer_log_id')
				->where('store_id', '=', $store_id)
				->where('store_user_id', '=', $store_user_id)
				->where('action', '=', 'Order')
				->where('action_type', '=', 'Close')
				->where('action_notes', '=', $request->actionNotes)
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($last_cd_res)) {
				DB::table('pu_store_cashdrawer_log')->where('cashdrawer_log_id', $last_cd_res->cashdrawer_log_id)->update(['is_device_event' => '0', 'added_datetime' => date('Y-m-d H:i:s')]);
				return 1;
			}
		}

		// \Log::channel('POSpayment')->info('POSpayment Cash Drawer Log : '.json_encode($variables), $this->getLogExtraParameters());
		//if($action_type=='Open')
		//{
		$data = [];
		$data["store_id"] = $store_id;
		$data["store_user_id"] = $store_user_id;
		$data["store_user_email"] = $store_user_email;
		$data["action"]  = $action;
		$data["action_type"] =	$action_type;
		$data["cd_amount"] =	$cd_amount;

		$last_cd_res = DB::table('pu_store_cashdrawer_log')
			->select('available_balance')
			->where('store_id', '=', $store_id)
			->orderBy('cashdrawer_log_id', 'desc')
			->first();

		$newBalance = $setnewBalance = 0;
		if (!empty($last_cd_res)) {
			$newBalance = $last_cd_res->available_balance;
			$setnewBalance = $newBalance;
		}

		if($action_type == 'Open'){
			$newBalance += $cd_amount;
			$setnewBalance = $newBalance;
		}

		if ($action_type == 'Close' && $action == 'Order') {
			$newBalance = $setnewBalance;
		}

		$data["available_balance"] =	$newBalance;

		if (isset($request->actionNotes) && $request->actionNotes != '') {
			$data["action_notes"] = $request->actionNotes;
		}

		if (isset($request->isDeviceEvent) && $request->isDeviceEvent != '') {
			$data["is_device_event"] = $request->isDeviceEvent;
		}

		$cashDrawerLogId = DB::table('pu_store_cashdrawer_log')->insertGetId($data);

		/*
				\Log::channel('POSpayment')->info('POSpayment Cash Drawer Log Data: '.json_encode($data).json_encode($variables), $this->getLogExtraParameters());
				\Log::channel('POSpayment')->info('=============================================================');
				*/

		if ($action_type == 'Open' && $action == 'Order') {
			if (isset($request->actionNotes) && $request->actionNotes != '') {
				$action_notes = json_decode($request->actionNotes);
				$day_cd_res = DB::table('pu_store_cash_drawer')
					->select('current_balance', 'store_cash_sales', 'store_cash_drawer_id')
					->where('store_id', '=', $store_id)
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->orderBy('added_datetime', 'desc')
					->first();

				$current_balance = 0;
				$updated_balance = 0;
				if (!empty($day_cd_res)) {
					$current_balance = $day_cd_res->current_balance;
					$updated_balance = $current_balance + $action_notes->cd_amount;
					$store_cash_sales = $day_cd_res->store_cash_sales;
					$store_cash_drawer_id = $day_cd_res->store_cash_drawer_id;

					DB::table('pu_store_cash_drawer')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['store_cash_sales' => $store_cash_sales + $action_notes->cd_amount, 'updated_datetime' => date('Y-m-d H:i:s'), 'current_balance' => $updated_balance]);
				}

				$data = [];
				$data["store_id"] = $store_id;
				$data["store_user_id"] = $store_user_id;
				$data["store_user_email"] = $store_user_email;
				$data["cd_amount"] = $action_notes->cd_amount;
				$data["available_balance"] = $updated_balance;
				$data["transaction_type"] = 'credit';
				$data["remarks"] = $request->actionNotes;
				$data["orders_id"]  = $action_notes->orderId;

				$posCDLog = DB::table('pu_store_cash_drawer_log')->insertGetId($data);
			}
		}

		/*if($action_type=='Close' && $action=='Order') {
					if(isset($request->actionNotes) && $request->actionNotes!='') {
						$action_notes = json_decode($request->actionNotes);
						$day_cd_res = DB::table('pu_store_cash_drawer')
										->select('current_balance','store_cash_sales','store_cash_drawer_id')
										->where('store_id','=',$store_id)
										->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
										->orderBy('added_datetime', 'desc')
										->first();

						$current_balance = 0;$updated_balance = 0;
						if(!empty($day_cd_res))
						{
							$current_balance = $day_cd_res->current_balance;
							$updated_balance = $current_balance+$action_notes->cd_amount;
							$store_cash_sales = $day_cd_res->store_cash_sales;
							$store_cash_drawer_id = $day_cd_res->store_cash_drawer_id;

							DB::table('pu_store_cash_drawer')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['store_cash_sales' => $store_cash_sales+$action_notes->cd_amount, 'updated_datetime' => date('Y-m-d H:i:s'), 'current_balance' => $updated_balance]);
						}

						$data=[];
						$data["store_id"]=$store_id;
						$data["store_user_id"]=$store_user_id;
						$data["store_user_email"]=$store_user_email;
						$data["cd_amount"]=$action_notes->cd_amount;
						$data["available_balance"]=$updated_balance;
						$data["transaction_type"]='credit';
						$data["remarks"] = $request->actionNotes;
						$data["orders_id"]  = $action_notes->orderId;

						$posCDLog = DB::table('pu_store_cash_drawer_log')->insertGetId($data);
					}
				}*/

		if ($action_type == 'Open' && $action == 'Manual') {
			if (isset($request->actionNotes) && $request->actionNotes != '') {
				$action_notes = json_decode($request->actionNotes);

				$day_cd_res = DB::table('pu_store_cash_drawer')
					->select('current_balance', 'store_cash_manual', 'store_cash_drawer_id')
					->where('store_id', '=', $store_id)
					->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))
					->orderBy('added_datetime', 'desc')
					->first();
				$current_balance = 0;
				$updated_balance = 0;
				if (!empty($day_cd_res)) {
					$current_balance = $day_cd_res->current_balance;
					$updated_balance = $current_balance + $action_notes->adjustedAmount;
					$store_cash_manual = $day_cd_res->store_cash_manual;
					$store_cash_drawer_id = $day_cd_res->store_cash_drawer_id;

					DB::table('pu_store_cash_drawer')->where('store_cash_drawer_id', $store_cash_drawer_id)->update(['store_cash_manual' => $store_cash_manual + $action_notes->adjustedAmount, 'updated_datetime' => date('Y-m-d H:i:s'), 'current_balance' => $updated_balance]);
				}

				$data = [];
				$data["store_id"] = $store_id;
				$data["store_user_id"] = $store_user_id;
				$data["store_user_email"] = $store_user_email;
				//$data["cd_amount"]= ($action_notes->cd_amount < 0 ? ($action_notes->cd_amount * -1) : $action_notes->cd_amount);
				//$data["available_balance"]=$updated_balance;
				//$data["transaction_type"]= ($action_notes->cd_amount > 0 ? 'credit' : 'debit');
				$data["cd_amount"] = 0;
				$data["available_balance"] = 0;
				$data["transaction_type"] = 'credit';
				$data["remarks"] = $request->actionNotes;
				$data["orders_id"]  = 0;

				$posCDLog = DB::table('pu_store_cash_drawer_log')->insertGetId($data);
			}
		}

		//}
		elseif ($action_type == 'Close' && $action != 'Order') {
			$last_cd_res = DB::table('pu_store_cashdrawer_log')
				->select('action', 'action_notes')
				->where('store_id', '=', $store_id)
				->where('store_user_id', '=', $store_user_id)
				->where('action_type', '=', 'Open')
				->orderBy('added_datetime', 'desc')
				->first();
			if (!empty($last_cd_res)) {
				$data = [];
				$data["store_id"] = $store_id;
				$data["store_user_id"] = $store_user_id;
				$data["store_user_email"] = $store_user_email;
				$data["action"]  = $last_cd_res->action;
				$data["action_type"] =	$action_type;
				$data["action_notes"] = $last_cd_res->action_notes;
				$cashDrawerLogId = DB::table('pu_store_cashdrawer_log')->insertGetId($data);
			}
		}

		return 1;
	}

	public function getDailyOpenPendingPopup()
	{
		$str = '';
		$str .= '<div id="daily-open-pending-popup" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog vertical-align-center">
					<div class="modal-content">
						<div class="modal-body">
							<div class="home-popup-width">
								<div class="home-popup-content">
								   <a href="javascript:void(0);" type="button" class="close" data-dismiss="modal" role="button" title="Close" aria-label="Close">
										<svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
											<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
										</svg>
									</a>
									<div class="text-center p-md-5 p-3">
										<h3 class="cust-h2 mb-2 mb-md-3 mt-md-3">Daily Open - Pending</h3>';
		//if(Auth::guard('store')->check() && Auth::guard('store')->user() && session()->has('store_user_access_rights') && is_array(session()->get('store_user_access_rights')) && in_array('pu_cash_drawer_daily_open', session()->get('store_user_access_rights')))
		if (Auth::guard('store')->check() && Auth::guard('store')->user()) {
			$str .= '<div class="alert alert-danger p-2 mt-md-4 mt-2">Please click the "Daily Open" button to verify your cash amounts and open your store.</div>
											<a href="' . config('global.SITE_URL') . 'store/cash-drawer-daily-open" class="btn btn-sm btn-secondary1 mt-3">Daily Open</a>';
		} else {
			$str .= '<div class="alert alert-danger p-0">Daily Open is pending but you do not have the right to perform the daily opening.</div>';
		}
		$str .= '</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';

		$data = array();
		$data['html'] = $str;
		return response()->json($data, 200);
	}

	public function savePOSOrderCashChangeData(Request $request)
	{
		if (!Auth::guard('store')->check()) {
			return redirect('store/login.html');
		}

		//if(!Auth::guard('store')->check() || auth('store')->user()->enable_pos != '1')
		if (!Auth::guard('store')->check()) {
			return redirect('/store/store-dashboard.html');
		}

		$input = $request->all();
		//$store_user = Auth::guard('store')->user();

		$act_type = $input['act_type'];
		if ($act_type == 'add') {
			$store_id = $input['pos_store_id'];
			$order_id = $input['pos_order_id'];

			$payable_cash_amount = $input['pos_payable_cash_amount'];
			$payable_cash_amount = str_replace(",", "", $payable_cash_amount);
			$payable_cash_amount = str_replace("$", "", $payable_cash_amount);

			$received_amount = $input['pos_received_amount'];
			$received_amount = str_replace(",", "", $received_amount);
			$received_amount = str_replace("$", "", $received_amount);

			$change_amount = $input['pos_change_amount'];
			$change_amount = str_replace(",", "", $change_amount);
			$change_amount = str_replace("$", "", $change_amount);

			$insertData = [
				"store_id" => $store_id,
				"orders_id" => $order_id,
				"payable_cash_amount" => $payable_cash_amount,
				"received_amount" => $received_amount,
				"change_amount" => $change_amount,
				"admin_email" => Session::get('sess_storeuseremail'),
				"added_datetime" => date("Y-m-d H:i:s")
			];

			$insertDataId = DB::table('pu_store_order_cashchange_log')->insertGetId($insertData);

			if ($insertDataId) {
				$response = array();
				$response['message'] = "success";
				$response['store_order_cashchange_log_id'] = $insertDataId;
				return response()->json($response, 200);
			}
		} elseif ($act_type == 'update') {
			$store_order_cashchange_log_id = $input['store_order_cashchange_log_id'];

			$received_amount = $input['pos_received_amount'];
			$received_amount = str_replace(",", "", $received_amount);
			$received_amount = str_replace("$", "", $received_amount);

			$change_amount = $input['pos_change_amount'];
			$change_amount = str_replace(",", "", $change_amount);
			$change_amount = str_replace("$", "", $change_amount);

			$updateData = [
				"received_amount" => $received_amount,
				"change_amount" => $change_amount,
				"updated_datetime" => date("Y-m-d H:i:s")
			];

			$is_update = DB::table('pu_store_order_cashchange_log')
				->where('store_order_cashchange_log_id', $store_order_cashchange_log_id)
				->update($updateData);

			if ($is_update) {
				$response = array();
				$response['message'] = "success";
				$response['store_order_cashchange_log_id'] = $store_order_cashchange_log_id;
				return response()->json($response, 200);
			}
		}

		$response = array();
		$response['message'] = "error";
		return response()->json($response, 200);
	}

	public function storeDeviceLog(Request $request)
	{

		/*$clientToken = $request->header('X-API-KEY');

		if($clientToken!="iG7yZuXHHaPBsQwPOS") {
			return response()->json([
            'status' => '401',
            'message' => 'Unauthorized'
			]);
		}*/

		//$data = $request->all();

		/*$data = [
			"logs" => [
				[
					"deviceID" => "ABCD1234",
					"deviceName" => "iPad",
					"lastLoggedInStoreID" => "storeID goes here",
					"lastLoggedInStoreEmployeeID" => "storeEmployeeID goes here",
					"logType" => "error",
					"message" => "log message goes here",
					"isHardwareLog" => true,
					"metadata" => "string consisting of metadata about this log",
					"timestamp" => 1680000000
				]
			]
		];*/

		//$log = json_decode(json_encode($data['log']), true);

		//$logsInput = $data['logs'];

		$addedData = 0;
		//for($ms=0;$ms<count($logsInput);$ms++) {
		$insertStoreDeviceLog = array(
			'device_id' 								=> $request->deviceID,
			'device_name'							=> $request->deviceName,
			'last_loggedIn_store_id'			=> (int)Session::get('sess_store_id'),
			'last_loggedIn_store_user_id'	=> (int)Session::get('sess_storeuserid'),
			'log_type' 								=> $request->logType,
			'message'								=> $request->message,
			'is_hardware_log'					=> $request->isHardwareLog,
			'metadata' 								=> '',
			//'datetime'  								=> date('Y-m-d H:i:s', $logsInput[$ms]['timestamp']),
			'added_datetime'						=> date("Y-m-d H:i:s")
		);
		$insertCSId = DB::table('pu_store_device_log')->insertGetId($insertStoreDeviceLog);

		if ($insertCSId) {
			$addedData++;
		}
		//}

		if ($addedData > 0) {
			return response()->json([
				'status' => 'true',
				'message' => 'Data received and stored.'
			]);
		} else {
			return response()->json([
				'status' => 'false',
				'message' => 'Something went wrong.'
			]);
		}
	}
	public function SkipAddress(Request $request)
	{

		$normaluser = Auth::user();
		if (Auth::guard('store')->check()) {
			$normaluser = Auth::guard('web')->user();
		}

		if (!$normaluser && config('global.IS_GUEST_CHECKOUT') == 'Yes') {
			$this->SetSkipGuestCustomer($request);
		} else {
			$this->SetPOSCustomerInfoUpdate($request);
		}

		$this->PageData['Billing'] = Session::get('ShoppingCart.BillingAddress');
		$this->PageData['Shipping'] = Session::get('ShoppingCart.ShippingAddress');

		//return view('checkout.shipbillinfo')->with($this->PageData);

	}

	public function SetPOSCustomerInfoUpdate($request)
	{
		$allow_update_details = "Yes";
		$normaluser = Auth::user();
		if (Auth::guard('store')->check()) {
			$normaluser = Auth::guard('web')->user();
		}

		//if(Auth::user() && $allow_update_details == "Yes" )
		if ($normaluser && $allow_update_details == "Yes") {
			if ($request['bill_country'] != 'US') {
				$state = isset($request['bill_other_state']) ? $request['bill_other_state'] : "";
			} else {
				$state = $request['bill_state'];
			}
			$CustomerAddNew = array(
				'first_name'		=> stripslashes($request['bill_fname']),
				'last_name' 		=> stripslashes($request['bill_lname']),
				'address1' 			=> stripslashes($request['bill_address1']),
				'city' 				=> stripslashes($request['bill_city']),
				'state' 			=> $state,
				'country' 			=> $request['bill_country'],
				'zip' 				=> $request['bill_zip'],
				'phone' 			=> $request['bill_phone']
			);
			if (isset($request['bill_company']) && $request['bill_company'] != "") {
				$CustomerAddNew['company_name'] = stripslashes($request['bill_company']);
			}
			if (isset($request['bill_address2']) && $request['bill_address2'] != "") {
				$CustomerAddNew['address2'] = stripslashes($request['bill_address2']);
			}
			$normaluser = Auth::user();
			if (Auth::guard('store')->check()) {
				$normaluser = Auth::guard('web')->user();
			}
			$cust_upd = Customer::where('customer_id', '=', $normaluser->customer_id)->update($CustomerAddNew);
			//$cust_upd = Customer::where('customer_id','=',Auth::user()->customer_id)->update($CustomerAddNew);

			//merge guest accounts
			$user_email = Session::has('sess_useremail') ? Session::get('sess_useremail') : "";
			if ($user_email == "") {
				$user_email = $request['bill_email'];
			}
			$this->Merge_Guest_Register($user_email, Session::get('sess_icustomerid'));
			//merge guest accounts
		}

		if (isset($request['newsletter']) && $request['newsletter'] == 'Yes' && trim($request['bill_email']) != '') {
			$check_news = NewsLetter::where('email', '=', trim($request['bill_email']))->get();
			if ($check_news && $check_news->count() <= 0) {
				$arrInsert = array(
					'first_name' => trim($request['bill_fname']),
					'last_name'  => trim($request['bill_lname']),
					'email' 	 => trim($request['bill_email']),
					'phone_no' => trim($request['bill_phone']),
					'status'	 => '1'
				);
				$News = NewsLetter::create($arrInsert);
				$NewsId = $News->news_letter_id;
				if ($NewsId) {
					$data["phone"] = trim($request['bill_phone']); //"+12679018713";
					$data["email"] = trim($request['bill_email']); //"test@gmail.com";
					$data["first_name"] = trim($request['bill_fname']);
					$data["visitorId"] = $NewsId; //"762bb2a97d604f958e3071fef83dfd5a";
					if (trim($data["phone"]) != "" && config('global.SITE_MODE') == 'Live') {
						AddAttentiveSubscriber($data);
					}
				}
			}
		}
	}

	public function SetGuestCustomerAutoComplete111(Request $request)
	{
		if(Session::has('sess_icustomerid')){
			Session::forget('sess_icustomerid');
		}
		if(Session::has('etype')){
			Session::forget('etype');
		}
		if(Session::has('eusertype')){
			Session::forget('eusertype');
		}
		if(Session::has('sess_useremail')){
			Session::forget('sess_useremail');
		}

		if(Session::has('ShoppingCart.BillingAddress')){
			Session::forget('ShoppingCart.BillingAddress');
		}
		if(Session::has('ShoppingCart.ShippingAddress')){
			Session::forget('ShoppingCart.ShippingAddress');
		}		

		$storeUser = Auth::guard('store')->user();		
		
		if (!$storeUser || config('global.IS_GUEST_CHECKOUT') !== 'Yes') {
			return response()->json(['success'=>'0']);
		}
		
		$store = DB::table('pu_stores')
			->where('store_id', $storeUser->store_id)
			->first();

		$guestEmail = trim($request['guest_email'] ?? '');

		if (empty($guestEmail) || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
			return response()->json(['success'=>'0']);
		}

		$firstName  = trim($request['firstname'] ?? '');
		$lastName   = trim($request['lastname'] ?? '');
		$phone      = trim($request['phonenumber'] ?? '');
		$BillingSkipEmail = trim($request['SkipEmailValidation'] ?? '');
		$BillingSkip = trim($request['BillingSkip'] ?? '');

		Session::put('ShoppingCart.Store.FirstName', $firstName);
		Session::put('ShoppingCart.Store.LastName', $lastName);
		Session::put('ShoppingCart.Store.Phone', $phone);
		Session::put('ShoppingCart.Store.Email', $guestEmail);
		
		if(isset($request['SkipEmailValidation']) && $request['SkipEmailValidation']=="Yes")
		{
			Session::put('BillingSkipEmail',$request['BillingSkipEmail']);
		}
		if(isset($request['BillingSkip']) && $request['BillingSkip']!="")
		{
			Session::put('BillingSkipVariable',$request['BillingSkip']);
		}

		$registrationType = 'Member';
		$customer = Customer::where('email', $guestEmail)
			->where('registration_type', 'M')
			->where('eusertype','Retailer')
			->where('is_deleted', 'No')
			->first();

		// if (!$customer) {
		// 	$customer = Customer::where('email', $guestEmail)
		// 		->where('registration_type', 'G')
		// 		->where('is_deleted', 'No')
		// 		->first();

		// 	$registrationType = 'Guest';
		// }

		if(!empty($customer)){			
			$firstName = $customer->first_name ?? '';	
			$lastName = $customer->last_name ?? '';	
			$phone = $customer->phone ?? '';	
			$address1 = $customer->address1 ?? '';
			$address2 = $customer->address2 ?? '';
			$city = $customer->city ?? '';
			$country = $customer->country ?? '';	
			$state = $customer->state ?? '';
			$zip = $customer->zip ?? '';
			$bill_other_state = '';
			if(!empty($country) && $country != 'US')
			{
				$bill_other_state = $state;
				$state = "";
			}	
			if(empty($country))
			{
				$country= "US";
				$state = "NY";
			}		
		} else {
			$firstName = $store->store_name ?? '';			
			$lastName = $store->store_name ?? '';
			$address1 = $store->store_address1 ?? '';
			$address2 = $store->store_address2 ?? '';
			$city = $store->store_city ?? '';
			$country = $store->store_country ?? '';
			$state = $store->store_state ?? '';
			$zip = $store->store_zipcode ?? '';
			$phone  = $store->store_phone ?? '';
		}	

		$billingData = [
			'bill_fname'    => $firstName ?? '',
			'bill_lname'    => $lastName ?? '',
			'bill_company'  => '',
			'bill_address1' => $address1 ?? '',
			'bill_address2' => $address2 ?? '',
			'bill_city'     => $city ?? '',
			'bill_country'  => $country ?? '',
			'bill_state'    => $state ?? '',
			'bill_other_state'=> $bill_other_state ?? '',
			'bill_zip'      => $zip ?? '',
			'bill_phone'    => $phone ?? '',
			'bill_email'    => $guestEmail,
			'sameasbill'    => 'Yes',
		];

		foreach ($billingData as $key => $value) {
			$request[$key] = $value;
		}
		$allowUpdateDetails = 'Yes';	
		if ($customer) {
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', $customer->eusertype);
			Session::put('sess_useremail', $customer->email);

			$allowUpdateDetails = 'No';

			if ($customer->status == '0') {
				Customer::where('customer_id', $customer->customer_id)->update([
					'status'       => '1',
					'upd_datetime' => now(),
					'merge_log'    => 'Auto updated to Active from billing page'
				]);
			}

			Session::put(
				'ShoppingCart.merge_note',
				'Merge with ' . $customer->eusertype . ' (' . $registrationType . ') customer id: ' . $customer->customer_id
			);

			Session::put('ShoppingCart.is_registered_guest', 'Yes');
		}  else {

			$customerData = [
				'first_name'        => $firstName,
				'last_name'         => $lastName,
				'phone'             => $phone,
				'email'             => $guestEmail,
				'registration_type' => 'G',
				'status'            => '1',
				'eusertype'         => 'Retailer',
				'customer_ip'       => request()->ip(),
				'customer_browser'  => request()->userAgent(),
			];

			if($registrationType=="Guest" && Session::has('ShoppingCart.sess_guest_icustomerid') && Session::get('ShoppingCart.sess_guest_icustomerid')!=''){
				Customer::where('customer_id', Session::get('ShoppingCart.sess_guest_icustomerid'))->update($customerData);
				$customer = Customer::where('customer_id',Session::get('ShoppingCart.sess_guest_icustomerid'))->first();
			} else {
				$customer = Customer::create($customerData);
			}
			if($registrationType=="Guest")
			{
				Session::put('ShoppingCart.sess_guest_icustomerid', $customer->customer_id);
			}			
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', 'Retailer');
			Session::put('sess_useremail', $guestEmail);
		}
		// if ($allowUpdateDetails === 'No') {
		// 	$this->Merge_Guest_Register(
		// 		$guestEmail,
		// 		Session::get('sess_icustomerid')
		// 	);
		// }

		$this->SetBillingAddress($request);
		$Billing = Session::get('ShoppingCart.BillingAddress');

		$newCustId = 0;
		if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid')!=''){
			$newCustId = Session::get('sess_icustomerid');
		}

		$load_yotpo_widget = "";

		$yotpo_sess_useremail   = Session::get('sess_useremail', '');
		$yotpo_sess_icustomerid = Session::get('sess_icustomerid', '');

		$isStoreUserEmail = "N";
		if (Auth::guard('store')->check()) {
			$storeUserN = Auth::guard('store')->user();
			$storeN = DB::table('pu_stores')->where('store_id', $storeUserN->store_id)->first();
			$sessionEmail = Session::get('ShoppingCart.Store.Email');
			if (!empty($sessionEmail) && !empty($storeN->store_email ?? '') && $sessionEmail === $storeN->store_email) {
				$isStoreUserEmail = "Y";
			}
		}	

		if (!empty($yotpo_sess_useremail) && !empty($yotpo_sess_icustomerid) && $registrationType=="Member" && $isStoreUserEmail == "N") {
			$load_yotpo_widget = "";// $this->initYotpoWidget($yotpo_sess_useremail, $yotpo_sess_icustomerid);
		}

		//$load_yotpo_widget = $this->initYotpoWidget(Session::get('sess_useremail'),Session::get('sess_icustomerid'));

		return response()->json(['success' => '1','Billing'=>$Billing, 'newcustid' => $newCustId, 'yotpo_widget' => $load_yotpo_widget]);

	}

	public function SetGuestCustomerAutoComplete(Request $request)
	{
		if(Session::has('sess_icustomerid')){
			Session::forget('sess_icustomerid');
		}
		if(Session::has('etype')){
			Session::forget('etype');
		}
		if(Session::has('eusertype')){
			Session::forget('eusertype');
		}
		if(Session::has('sess_useremail')){
			Session::forget('sess_useremail');
		}

		if(Session::has('ShoppingCart.BillingAddress')){
			Session::forget('ShoppingCart.BillingAddress');
		}
		if(Session::has('ShoppingCart.ShippingAddress')){
			Session::forget('ShoppingCart.ShippingAddress');
		}

		

		$storeUser = Auth::guard('store')->user();
		
		
		if (!$storeUser || config('global.IS_GUEST_CHECKOUT') !== 'Yes') {
			return response()->json(['success'=>'0']);
		}
		
		$store = DB::table('pu_stores')
			->where('store_id', $storeUser->store_id)
			->first();			
			

		$guestEmail = trim($request['guest_email'] ?? '');

		if (empty($guestEmail) || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
			return response()->json(['success'=>'0']);
		}

		$firstName  = trim($request['firstname'] ?? '');
		$lastName   = trim($request['lastname'] ?? '');
		$phone      = trim($request['phonenumber'] ?? '');
		$BillingSkipEmail = trim($request['SkipEmailValidation'] ?? '');
		$BillingSkip = trim($request['BillingSkip'] ?? '');

		Session::put('ShoppingCart.Store.FirstName', $firstName);
		Session::put('ShoppingCart.Store.LastName', $lastName);
		Session::put('ShoppingCart.Store.Phone', $phone);
		Session::put('ShoppingCart.Store.Email', $guestEmail);
		
		if(isset($request['SkipEmailValidation']) && $request['SkipEmailValidation']=="Yes")
		{
			Session::put('BillingSkipEmail',$request['BillingSkipEmail']);
		}
		if(isset($request['BillingSkip']) && $request['BillingSkip']!="")
		{
			Session::put('BillingSkipVariable',$request['BillingSkip']);
		}

		$registrationType = 'Member';
		$customer = Customer::where('email', $guestEmail)
			->where('registration_type', 'M')
			->where('eusertype','Retailer')
			->first();

		if (!$customer) {
			$customer = Customer::where('email', $guestEmail)
				->where('registration_type', 'G')
				->where('is_deleted', 'No')
				->first();

			$registrationType = 'Guest';
		}

		if (empty($firstName)) {
			$firstName = $customer->first_name ?? '';
			if(empty($firstName)  && $registrationType=="Member")
			{
			   $firstName = $store->store_name ?? '';
			}

		}
		if (empty($lastName)) {
			$lastName = $customer->last_name ?? '';
			if(empty($lastName) && $registrationType=="Member")
			{
			   $lastName = $store->store_name ?? '';
			}
		}
		if (empty($phone) && $registrationType=="Member") {
			$phone = $customer->phone ?? '';
		}
		$address1 = $customer->address1 ?? '';
		if(empty($address1) && $registrationType=="Member")
		{
			$address1 = $store->store_address1 ?? '';
		}
		$address2 = $customer->address2 ?? '';
		if(empty($address2) && $registrationType=="Member")
		{
			$address2 = $store->store_address2 ?? '';
		}
		$city = $customer->city ?? '';
		if(empty($city) && $registrationType=="Member")
		{
			$city = $store->store_city ?? '';
		}
		$country = $customer->country ?? '';
		if(empty($country) && $registrationType=="Member")
		{
			$country = $store->store_country ?? '';
		}
		$state = $customer->state ?? '';

		if(empty($state) && $registrationType=="Member")
		{
			$state = $store->store_state ?? '';

		}
		$zip = $customer->zip ?? '';
		if(empty($zip) && $registrationType=="Member")
		{
			$zip = $store->store_zipcode ?? '';
		}
		if(empty($phone) && $registrationType=="Member")
		{
			$phone  = $store->store_phone ?? '';
		}
		$bill_other_state = '';
		if(!empty($country) && $country != 'US')
		{
			$bill_other_state = $state;
			$state = "";
		}
		if(empty($country))
		{
			$country= "US";
			$state = "NY";
		}
		

		$billingData = [
			'bill_fname'    => $firstName ?? '',
			'bill_lname'    => $lastName ?? '',
			'bill_company'  => '',
			'bill_address1' => $address1 ?? '',
			'bill_address2' => $address2 ?? '',
			'bill_city'     => $city ?? '',
			'bill_country'  => $country ?? '',
			'bill_state'    => $state ?? '',
			'bill_other_state'=> $bill_other_state ?? '',
			'bill_zip'      => $zip ?? '',
			'bill_phone'    => $phone ?? '',
			'bill_email'    => $guestEmail,
			'sameasbill'    => 'Yes',
		];

		foreach ($billingData as $key => $value) {
			$request[$key] = $value;
		}
		$allowUpdateDetails = 'Yes';	
		if ($customer) {
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', $customer->eusertype);
			Session::put('sess_useremail', $customer->email);

			$allowUpdateDetails = 'No';

			if ($customer->status == '0') {
				Customer::where('customer_id', $customer->customer_id)->update([
					'status'       => '1',
					'upd_datetime' => now(),
					'merge_log'    => 'Auto updated to Active from billing page'
				]);
			}

			Session::put(
				'ShoppingCart.merge_note',
				'Merge with ' . $customer->eusertype . ' (' . $registrationType . ') customer id: ' . $customer->customer_id
			);

			Session::put('ShoppingCart.is_registered_guest', 'Yes');
		} else {

			$customerData = [
				'first_name'        => $firstName,
				'last_name'         => $lastName,
				'phone'             => $phone,
				'email'             => $guestEmail,
				'registration_type' => 'G',
				'status'            => '1',
				'eusertype'         => 'Retailer',
				'customer_ip'       => request()->ip(),
				'customer_browser'  => request()->userAgent(),
			];

			if($registrationType=="Guest" && Session::has('ShoppingCart.sess_guest_icustomerid') && Session::get('ShoppingCart.sess_guest_icustomerid')!=''){
				Customer::where('customer_id', Session::get('ShoppingCart.sess_guest_icustomerid'))->update($customerData);
				$customer = Customer::where('customer_id',Session::get('ShoppingCart.sess_guest_icustomerid'))->first();
			} else {
				$customer = Customer::create($customerData);
			}
			Session::put('ShoppingCart.sess_guest_icustomerid', $customer->customer_id);
			// if($registrationType=="Guest")
			// {
			// 	Session::put('ShoppingCart.sess_guest_icustomerid', $customer->customer_id);
			// }			
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', 'Retailer');
			Session::put('sess_useremail', $guestEmail);
		}

		if ($allowUpdateDetails === 'No') {
			$this->Merge_Guest_Register(
				$guestEmail,
				Session::get('sess_icustomerid')
			);
		}

		$this->SetBillingAddress($request);
		$Billing = Session::get('ShoppingCart.BillingAddress');

		//added for taxcalculation
		$ship_country = $Billing["country"] ?? '';
		$ship_zip 	  = $Billing["zip"] ?? '';
		$ship_state	  = $Billing["state"] ?? '';
		$ship_address1 = trim($Billing["address1"]) ?? '';
		$ship_address2 = trim($Billing["address2"]) ?? '';
		$ship_city 	   = trim($Billing["city"]) ?? '';
		
		$ShoppingcartController = app()->make(ShoppingcartController::class);
		$ShoppingcartController->TaxCalculation($ship_country, $ship_state, $ship_zip,'','',$ship_city);

		$newCustId = 0;
		if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid')!=''){
			$newCustId = Session::get('sess_icustomerid');
		}

		$load_yotpo_widget = "";

		$yotpo_sess_useremail   = Session::get('sess_useremail', '');
		$yotpo_sess_icustomerid = Session::get('sess_icustomerid', '');

		$isStoreUserEmail = "N";
		if (Auth::guard('store')->check()) {
			$storeUserN = Auth::guard('store')->user();
			$storeN = DB::table('pu_stores')->where('store_id', $storeUserN->store_id)->first();
			$sessionEmail = Session::get('ShoppingCart.Store.Email');
			if (!empty($sessionEmail) && !empty($storeN->store_email ?? '') && $sessionEmail === $storeN->store_email) {
				$isStoreUserEmail = "Y";
			}
		}	

		if (!empty($yotpo_sess_useremail) && !empty($yotpo_sess_icustomerid) && $registrationType=="Member" && $isStoreUserEmail == "N") {
			$load_yotpo_widget = '';// $this->initYotpoWidget($yotpo_sess_useremail, $yotpo_sess_icustomerid);
		}

		//$load_yotpo_widget = $this->initYotpoWidget(Session::get('sess_useremail'),Session::get('sess_icustomerid'));

		return response()->json(['success' => '1','Billing'=>$Billing, 'newcustid' => $newCustId, 'yotpo_widget' => $load_yotpo_widget]);
	}

	public function SetGuestCustomer(Request $request)
	{
		if(Session::has('sess_icustomerid')){
			Session::forget('sess_icustomerid');
		}
		if(Session::has('etype')){
			Session::forget('etype');
		}
		if(Session::has('eusertype')){
			Session::forget('eusertype');
		}
		if(Session::has('sess_useremail')){
			Session::forget('sess_useremail');
		}

		if(Session::has('ShoppingCart.BillingAddress')){
			Session::forget('ShoppingCart.BillingAddress');
		}
		if(Session::has('ShoppingCart.ShippingAddress')){
			Session::forget('ShoppingCart.ShippingAddress');
		}

		

		$storeUser = Auth::guard('store')->user();
		
		
		if (!$storeUser || config('global.IS_GUEST_CHECKOUT') !== 'Yes') {
			return response()->json(['success'=>'0']);
		}
		
		$store = DB::table('pu_stores')
			->where('store_id', $storeUser->store_id)
			->first();
			
			

		$guestEmail = trim($request['guest_email'] ?? '');

		$firstName  = trim($request['firstname'] ?? '');
		$lastName   = trim($request['lastname'] ?? '');
		$phone      = trim($request['phonenumber'] ?? '');
		$BillingSkipEmail = trim($request['SkipEmailValidation'] ?? '');
		$BillingSkip = trim($request['BillingSkip'] ?? '');

		Session::put('ShoppingCart.Store.FirstName', $firstName);
		Session::put('ShoppingCart.Store.LastName', $lastName);
		Session::put('ShoppingCart.Store.Phone', $phone);
		Session::put('ShoppingCart.Store.Email', $guestEmail);
		
		if(isset($request['SkipEmailValidation']) && $request['SkipEmailValidation']=="Yes")
		{
			Session::put('BillingSkipEmail',$request['BillingSkipEmail']);
		}
		if(isset($request['BillingSkip']) && $request['BillingSkip']!="")
		{
			Session::put('BillingSkipVariable',$request['BillingSkip']);
		}

		$registrationType = 'Member';
		$customer = Customer::where('email', $guestEmail)
			->where('registration_type', 'M')
			->where('eusertype','Retailer')
			->first();

		if (!$customer) {
			$customer = Customer::where('email', $guestEmail)
				->where('registration_type', 'G')
				->where('is_deleted', 'No')
				->first(); 

			$registrationType = 'Guest';
		}

		if (empty($firstName)) {
			$firstName = $customer->first_name ?? '';
			if(empty($firstName)  && $registrationType=="Member")
			{
			   $firstName = $store->store_name ?? '';
			}

		}
		if (empty($lastName)) {
			$lastName = $customer->last_name ?? '';
			if(empty($lastName) && $registrationType=="Member")
			{
			   $lastName = $store->store_name ?? '';
			}
		}
		if (empty($phone) && $registrationType=="Member") {
			$phone = $customer->phone ?? '';
		}
		$address1 = $customer->address1 ?? '';
		if(empty($address1) && $registrationType=="Member")
		{
			$address1 = $store->store_address1 ?? '';
		}
		$address2 = $customer->address2 ?? '';
		if(empty($address2) && $registrationType=="Member")
		{
			$address2 = $store->store_address2 ?? '';
		}
		$city = $customer->city ?? '';
		if(empty($city) && $registrationType=="Member")
		{
			$city = $store->store_city ?? '';
		}
		$country = $customer->country ?? '';
		if(empty($country) && $registrationType=="Member")
		{
			$country = $store->store_country ?? '';
		}
		$state = $customer->state ?? '';

		if(empty($state) && $registrationType=="Member")
		{
			$state = $store->store_state ?? '';

		}
		$zip = $customer->zip ?? '';
		if(empty($zip) && $registrationType=="Member")
		{
			$zip = $store->store_zipcode ?? '';
		}
		if(empty($phone) && $registrationType=="Member")
		{
			$phone  = $store->store_phone ?? '';
		}
		$bill_other_state = '';
		if($country != 'US')
		{
			$bill_other_state = $state;
			$state = "";
		}
		if(empty($country))
		{
			$country= "US";
			$state = "NY";
		}
		

		$billingData = [
			'bill_fname'    => $firstName ?? '',
			'bill_lname'    => $lastName ?? '',
			'bill_company'  => '',
			'bill_address1' => $address1 ?? '',
			'bill_address2' => $address2 ?? '',
			'bill_city'     => $city ?? '',
			'bill_country'  => $country ?? '',
			'bill_state'    => $state ?? '',
			'bill_other_state'=> $bill_other_state ?? '',
			'bill_zip'      => $zip ?? '',
			'bill_phone'    => $phone ?? '',
			'bill_email'    => $guestEmail,
			'sameasbill'    => 'Yes',
		];

		foreach ($billingData as $key => $value) {
			$request[$key] = $value;
		}
		$allowUpdateDetails = 'Yes';	
		if ($customer) {
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', $customer->eusertype);
			Session::put('sess_useremail', $customer->email);

			$allowUpdateDetails = 'No';

			if ($customer->status == '0') {
				Customer::where('customer_id', $customer->customer_id)->update([
					'status'       => '1',
					'upd_datetime' => now(),
					'merge_log'    => 'Auto updated to Active from billing page'
				]);
			}

			Session::put(
				'ShoppingCart.merge_note',
				'Merge with ' . $customer->eusertype . ' (' . $registrationType . ') customer id: ' . $customer->customer_id
			);

			Session::put('ShoppingCart.is_registered_guest', 'Yes');
		} else {

			$customerData = [
				'first_name'        => $firstName,
				'last_name'         => $lastName,
				'phone'             => $phone,
				'email'             => $guestEmail,
				'registration_type' => 'G',
				'status'            => '1',
				'eusertype'         => 'Retailer',
				'customer_ip'       => request()->ip(),
				'customer_browser'  => request()->userAgent(),
			];

			$customer = Customer::create($customerData);

			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', 'Retailer');
			Session::put('sess_useremail', $guestEmail);
		}

		if ($allowUpdateDetails === 'No') {
			$this->Merge_Guest_Register(
				$guestEmail,
				Session::get('sess_icustomerid')
			);
		}

		$this->SetBillingAddress($request);
		$Billing = Session::get('ShoppingCart.BillingAddress');

		$newCustId = 0;
		if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid')!=''){
			$newCustId = Session::get('sess_icustomerid');
		}

		$load_yotpo_widget = "";// $this->initYotpoWidget(Session::get('sess_useremail'),Session::get('sess_icustomerid'));

		return response()->json(['success' => '1','Billing'=>$Billing, 'newcustid' => $newCustId, 'yotpo_widget' => $load_yotpo_widget]);
	}

	public function SetGuestCustomer_bk(Request $request)
	{
		if(Session::has('sess_icustomerid')){
			Session::forget('sess_icustomerid');
		}
		if(Session::has('etype')){
			Session::forget('etype');
		}
		if(Session::has('eusertype')){
			Session::forget('eusertype');
		}
		if(Session::has('sess_useremail')){
			Session::forget('sess_useremail');
		}

		$guestEmail = trim($request['guest_email'] ?? '');

		$firstName  = trim($request['firstname'] ?? '');
		$lastName   = trim($request['lastname'] ?? '');
		$phone      = trim($request['phonenumber'] ?? '');

		Session::put('ShoppingCart.Store.FirstName', $firstName);
		Session::put('ShoppingCart.Store.LastName', $lastName);
		Session::put('ShoppingCart.Store.Phone', $phone);
		Session::put('ShoppingCart.Store.Email', $guestEmail);

		$registrationType = 'Member';
		$customer = Customer::where('email', $guestEmail)
			->where('registration_type', 'M')
			->first();

		if (!$customer) {
			$customer = Customer::where('email', $guestEmail)
				->where('registration_type', 'G')
				->where('is_deleted', 'No')
				->first();

			$registrationType = 'Guest';
		}

		if (empty($firstName)) {
			$firstName = $customer->first_name ?? '';
			if(empty($firstName))
			{
			   $firstName = $store->store_name;
			}

		}
		if (empty($lastName)) {
			$lastName = $customer->last_name ?? '';
			if(empty($lastName))
			{
			   $lastName = $store->store_name;
			}
		}
		if (empty($phone)) {
			$phone = $customer->phone ?? '';
		}
		$address1 = $customer->address1 ?? '';
		if(empty($address1))
		{
			$address1 = $store->store_address1 ?? '';
		}
		$address2 = $customer->address2 ?? '';
		if(empty($address2))
		{
			$address2 = $store->store_address2 ?? '';
		}
		$city = $customer->city ?? '';
		if(empty($city))
		{
			$city = $store->store_city ?? '';
		}
		$country = $customer->country ?? '';
		if(empty($country))
		{
			$country = $store->store_country ?? '';
		}
		$state = $customer->state ?? '';

		if(empty($state))
		{
			$state = $store->store_state ?? '';

		}
		$zip = $customer->zip ?? '';
		if(empty($zip))
		{
			$zip = $store->store_zipcode ?? '';
		}
		if(empty($phone))
		{
			$phone  = $store->store_phone;
		}
		$bill_other_state = '';
		if($country != 'US')
		{
			$bill_other_state = $state;
			$state = "";
		}

		$billingData = [
			'bill_fname'    => $firstName ?? '',
			'bill_lname'    => $lastName ?? '',
			'bill_company'  => '',
			'bill_address1' => $address1 ?? '',
			'bill_address2' => $address2 ?? '',
			'bill_city'     => $city ?? '',
			'bill_country'  => $country ?? '',
			'bill_state'    => $state ?? '',
			'bill_other_state'=> $bill_other_state ?? '',
			'bill_zip'      => $zip ?? '',
			'bill_phone'    => $phone ?? '',
			'bill_email'    => $guestEmail,
			'sameasbill'    => 'Yes',
		];

		foreach ($billingData as $key => $value) {
			$request[$key] = $value;
		}

		if ($customer) {
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', $customer->eusertype);
			Session::put('sess_useremail', $customer->email);

			$allowUpdateDetails = 'No';

			if ($customer->status == '0') {
				Customer::where('customer_id', $customer->customer_id)->update([
					'status'       => '1',
					'upd_datetime' => now(),
					'merge_log'    => 'Auto updated to Active from billing page'
				]);
			}

			Session::put(
				'ShoppingCart.merge_note',
				'Merge with ' . $customer->eusertype . ' (' . $registrationType . ') customer id: ' . $customer->customer_id
			);

			Session::put('ShoppingCart.is_registered_guest', 'Yes');
		} else {

			$customerData = [
				'first_name'        => $firstName,
				'last_name'         => $lastName,
				'phone'             => $phone,
				'email'             => $guestEmail,
				'registration_type' => 'G',
				'status'            => '1',
				'eusertype'         => 'Retailer',
				'customer_ip'       => request()->ip(),
				'customer_browser'  => request()->userAgent(),
			];

			$customer = Customer::create($customerData);

			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', 'Retailer');
			Session::put('sess_useremail', $guestEmail);
		}

		if ($allowUpdateDetails === 'No') {
			$this->Merge_Guest_Register(
				$guestEmail,
				Session::get('sess_icustomerid')
			);
		}

		$this->SetBillingAddress($request);
		$Billing = Session::get('ShoppingCart.BillingAddress');

		return response()->json(['success' => '1','Billing'=>$Billing]);
	}

	public function SetSkipGuestCustomer(Request $request)
	{
		//$request->session()->regenerate();

		$storeUser = Auth::guard('store')->user();
		if (!$storeUser || config('global.IS_GUEST_CHECKOUT') !== 'Yes') {
			return response()->json(['success'=>'0']);
		}

		$store = DB::table('pu_stores')
			->where('store_id', $storeUser->store_id)
			->first();

		if (!$store) {
			return response()->json(['success'=>'0']);
		}

		$guestEmail = trim($request['guest_email'] ?? '');
		$firstName  = trim($request['firstname'] ?? '');
		$lastName   = trim($request['lastname'] ?? '');
		$phone      = trim($request['phonenumber'] ?? '');

		if (empty($guestEmail) || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
			$guestEmail = $store->store_email;
			//return response()->json(['success'=>'0','message'=>'Please enter valid email address']);
		}

		Session::forget('ShoppingCart.Store.FirstName');
		if(isset($firstName) && $firstName!='')
			Session::put('ShoppingCart.Store.FirstName', $firstName);

		Session::forget('ShoppingCart.Store.LastName');
		if(isset($lastName) && $lastName!='')
			Session::put('ShoppingCart.Store.LastName', $lastName);

		Session::forget('ShoppingCart.Store.Phone');
		if(isset($phone) && $phone!='')
			Session::put('ShoppingCart.Store.Phone', $phone);

		Session::forget('ShoppingCart.Store.Email');
		if(isset($guestEmail) && $guestEmail!='')
			Session::put('ShoppingCart.Store.Email', $guestEmail);

		if (empty($guestEmail)) {
			$guestEmail = $store->store_email;
		}

		Session::forget([
			'sess_icustomerid',
			'etype',
			'eusertype',
			'sess_useremail'
		]);
		$registrationType = 'Member';

		$customer = Customer::where('email', $guestEmail)
			->where('registration_type', 'M')
			->where('eusertype', 'Retailer')
			->where('is_deleted', 'No')
			->first();

		if (!$customer) {
			$customer = Customer::where('email', $guestEmail)
				->where('registration_type', 'G')
				->where('eusertype', 'Retailer')
				->where('is_deleted', 'No')
				->first();

			$registrationType = 'Guest';
		}

		if (empty($firstName)) {
			$firstName = $customer->first_name ?? '';
			if(empty($firstName))
			{
			   $firstName = $store->store_name; 
			}

		}
		if (empty($lastName)) {
			$lastName = $customer->last_name ?? '';
			if(empty($lastName))
			{
			   $lastName = $store->store_name;
			}
		}
		if (empty($phone)) {
			$phone = $customer->phone ?? '';
		}
		$address1 = $customer->address1 ?? '';
		if(empty($address1))
		{
			$address1 = $store->store_address1 ?? '';
		}
		$address2 = $customer->address2 ?? '';
		if(empty($address2))
		{
			$address2 = $store->store_address2 ?? '';
		}
		$city = $customer->city ?? '';
		if(empty($city))
		{
			$city = $store->store_city ?? '';
		}
		$country = $customer->country ?? '';
		if(empty($country))
		{
			$country = $store->store_country ?? '';
		}
		$state = $customer->state ?? '';

		if(empty($state))
		{
			$state = $store->store_state ?? '';

		}
		$zip = $customer->zip ?? '';
		if(empty($zip))
		{
			$zip = $store->store_zipcode ?? '';
		}
		if(empty($phone))
		{
			$phone  = $store->store_phone;
		}
			$bill_other_state = '';
			if($country != 'US')
			{
			  $bill_other_state = $state;
			  $state = "";
			}

		$allowUpdateDetails = 'Yes';
		$billingData = [
			'bill_fname'    => $firstName ?? '',
			'bill_lname'    => $lastName ?? '',
			'bill_company'  => '',
			'bill_address1' => $address1 ?? '',
			'bill_address2' => $address2 ?? '',
			'bill_city'     => $city ?? '',
			'bill_country'  => $country ?? '',
			'bill_state'    => $state ?? '',
			'bill_other_state'=> $bill_other_state ?? '',
			'bill_zip'      => $zip ?? '',
			'bill_phone'    => $phone ?? '',
			'bill_email'    => $guestEmail,
			'sameasbill'    => 'Yes',
		];

		foreach ($billingData as $key => $value) {
			$request[$key] = $value;
		}

		if ($customer) {
			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', $customer->eusertype);
			Session::put('sess_useremail', $customer->email);

			$allowUpdateDetails = 'No';

			if ($customer->status == '0') {
				Customer::where('customer_id', $customer->customer_id)->update([
					'status'       => '1',
					'upd_datetime' => now(),
					'merge_log'    => 'Auto updated to Active from billing page'
				]);
			}

			Session::put(
				'ShoppingCart.merge_note',
				'Merge with ' . $customer->eusertype . ' (' . $registrationType . ') customer id: ' . $customer->customer_id
			);

			Session::put('ShoppingCart.is_registered_guest', 'Yes');
		} else {

			$customerData = [
				'first_name'        => $firstName,
				'last_name'         => $lastName,
				'phone'             => $phone,
				'email'             => $guestEmail,
				'registration_type' => 'G',
				'status'            => '1',
				'eusertype'         => 'Retailer',
				'customer_ip'       => request()->ip(),
				'customer_browser'  => request()->userAgent(),
			];

			$customer = Customer::create($customerData);

			Session::put('sess_icustomerid', $customer->customer_id);
			Session::put('etype', 'G');
			Session::put('eusertype', 'Retailer');
			Session::put('sess_useremail', $guestEmail);
		}

		if ($allowUpdateDetails === 'No') {
			$this->Merge_Guest_Register(
				$guestEmail,
				Session::get('sess_icustomerid')
			);
		}

		$this->SetBillingAddress($request);
		$Billing = Session::get('ShoppingCart.BillingAddress');

		if (isset($request['newsletter']) && $request['newsletter'] == 'Yes' && trim($request['bill_email']) != '') {
			$check_news = NewsLetter::where('email', '=', trim($request['bill_email']))->get();
			if ($check_news && $check_news->count() <= 0) {
				$arrInsert = array(
					'first_name' => trim($request['bill_fname']),
					'last_name'  => trim($request['bill_lname']),
					'email' 	 => trim($request['bill_email']),
					'phone_no'	 => trim($request['bill_phone']),
					'status'	 => '1'
				);
				$News = NewsLetter::create($arrInsert);
				$NewsId = $News->news_letter_id;
				if ($NewsId) {
					$data["phone"] = trim($request['bill_phone']); //"+12679018713";
					$data["email"] = trim($request['guest_email']); //"test@gmail.com";
					$data["first_name"] = trim($request['bill_fname']);
					$data["visitorId"] = $NewsId;
					if (trim($data["phone"]) != "" && config('global.SITE_MODE') == 'Live') {
						AddAttentiveSubscriber($data);
					}
				}
			}
		}
		$normaluser = Auth::user();
		if (Auth::guard('store')->check()) {
			$normaluser = Auth::guard('web')->user();
		}

		//if(!Auth::user() && Session::get('sess_icustomerid') != '')
		if (!$normaluser && Session::get('sess_icustomerid') != '') {
			$CustomerQry = Customer::where('email', $request['bill_email'])
				->where('status', '1')
				->where('registration_type', 'M');

			$Customer = $CustomerQry->first();

			if ($Customer) {

				//$request->session()->regenerate();

				Session::put('sess_useremail', $Customer->email);
				Session::put('sess_username', $request['bill_fname']);
				Session::put('sess_icustomerid', $Customer->customer_id);
				Session::put('eusertype', $Customer->eusertype);
				Session::put('is_dropshipper', $Customer->is_dropshipper);
				Session::put('etype', 'M');
				Session::put('payment_amount', $Customer->payment_amount);

				$request['bill_fname'] 	 		= $request['bill_fname'] ?? $Customer->first_name;
				$request['bill_lname'] 	 		= $request['bill_lname'] ?? $Customer->last_name;
				$request['bill_company'] 		= $Customer->company_name ?? '';
				$request['bill_address1'] 	 	= $Customer->address1 ?? '';
				$request['bill_address2'] 	 	= $Customer->address2 ?? '';
				$request['bill_city'] 	 		= $Customer->city ?? '';
				$request['bill_country'] 	 	= $Customer->country ?? '';
				$request['bill_state'] 			= $Customer->state ?? '';
				$request['bill_zip']			= $Customer->zip ?? '';
				$request['bill_phone']			= $request['bill_phone'] ?? $Customer->phone;
				$request['bill_email']			= $request['guest_email'] ?? '';
				$request['sameasbill']			= "Yes";

				$custFname = '';
				if (isset($request['bill_fname']) &&  $request['bill_fname'] != '') {
					$custFname = $request['bill_fname'];
				}
				$custlname = '';
				if (isset($request['bill_lname']) && $request['bill_lname'] != '') {
					$custlname = $request['bill_lname'];
				}

				Session::put('sess_custname', $custFname . "|" . $custlname);
			//	Auth::guard('web')->login($Customer,false);

				return response()->json(['success' => '1','billing' => $Billing]);
			}
		}
		return response()->json(['success' => '1']);
	}

	public function OrderDetailPrint(Request $request)
	{
		$orders_id = (int)$request['id'];
		$path = public_path('images/logo.png');
		if(File::exists($path)) {
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
		} else {
			$logo = '';
		}
		$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%b %d, %Y') AS datetime"))
							->where('orders_id', '=', $orders_id)
							->first();

		$OrderDetailRs = OrderDetail::select('pu_order_detail.*', 'pu_products.UPC', 'pu_products.product_name as prodname')
								->join('pu_products', 'pu_products.products_id', '=', 'pu_order_detail.products_id')
								->where('orders_id', '=', $orders_id)
								->orderBy('orders_detail_id', 'DESC')
								->get();
		$this->PageData['logo']	= $logo;
		$this->PageData['OrderRs']	= $OrderRs;
		$this->PageData['OrderDetailRs'] = $OrderDetailRs;
		return view('pos.orderprint')->with($this->PageData);
	}

public function SetPosAutoLogin(Request $request)
{
    $guestEmail = trim($request->guest_email ?? '');

    $result = Customer::where(DB::raw('trim(email)'), '=', $guestEmail)
        ->where('registration_type', 'M')
        ->first();

    Session::forget([
        'sess_icustomerid',
        'etype',
        'eusertype',
        'sess_useremail'
    ]);

    $Billing = [];

    if ($result) {

        $country = $result->country;
        $state = $result->state;
        $bill_other_state = '';

        if ($country != 'US') {
            $bill_other_state = $state;
            $state = "";
        }

        $billingData = [
            'bill_fname'        => $result->first_name ?? '',
            'bill_lname'        => $result->last_name ?? '',
            'bill_company'      => $result->company_name ?? '',
            'bill_address1'     => $result->address1 ?? '',
            'bill_address2'     => $result->address2 ?? '',
            'bill_city'         => $result->city ?? '',
            'bill_country'      => $result->country ?? '',
            'bill_state'        => $state ?? '',
            'bill_other_state'  => $bill_other_state ?? '',
            'bill_zip'          => $result->zip ?? '',
            'bill_phone'        => $result->phone ?? '',
            'bill_email'        => $guestEmail,
            'sameasbill'        => 'Yes',
        ];

        foreach ($billingData as $key => $value) {
            $request->merge([$key => $value]);
        }

        $this->SetBillingAddress($request);

        $Billing = Session::get('ShoppingCart.BillingAddress');
    }

    return response()->json([
			'success' => '1',
			'billing' => $Billing
	]);
}
public function ShipToStore(Request $request)
{
    $ShipTostore = filter_var($request->ShipTostore, FILTER_VALIDATE_BOOLEAN);

	$storeUser = Auth::guard('store')->user();

	$store = DB::table('pu_stores')
	->where('store_id', $storeUser->store_id)
	->first();
	 $result = $store;

     $Billing = [
            'ship_fname'        => '',
            'ship_lname'        => '',
            'ship_company'      => '',
            'ship_address1'     => '',
            'ship_address2'     => '',
            'ship_city'         => '',
            'ship_country'      => '',
            'ship_state'        => '',
            'ship_other_state'  => '',
            'ship_zip'          => '',
            'ship_phone'        => '',
            'ship_email'        => '',
        ];

    if ($result && $ShipTostore) {

		$ship_other_state = '';
		$state = $result->store_state ?? '';
		if($result->store_country != 'US')
		{
		  $ship_other_state = $state;
		  $state = "";
		}

        $Billing = [
            'ship_fname'        => $result->store_name ?? '',
            'ship_lname'        => $result->store_name ?? '',
            'ship_company'      => '',
            'ship_address1'     => $result->store_address1 ?? '',
            'ship_address2'     => $result->store_address2 ?? '',
            'ship_city'         => $result->store_city ?? '',
            'ship_country'      => $result->store_country ?? '',
            'ship_state'        => $state ?? '',
            'ship_other_state'  => $ship_other_state ?? '',
            'ship_zip'          => $result->store_zipcode ?? '',
            'ship_phone'        => $result->store_phone ?? '',
            'ship_email'        => $result->store_email,
        ];

    }

    return response()->json([
			'success' => '1',
			'shipping' => $Billing
	]);
}

	public function getReceiptData(Request $request)
	{
		$storeAddressStr1 = $storeAddressStr2 = $storeAddressStr3 = $storeUserName = '';
		
		if(Auth::guard('store')->user()->first_name != ''){
			$storeUserName .= Auth::guard('store')->user()->first_name;
		}
		
		if(Auth::guard('store')->user()->last_name != ''){
			$storeUserName .= " ".Auth::guard('store')->user()->last_name;
		}
		
		if(Session::has('sess_store_id') && Session::get('sess_store_id')!=''){
			$StoreData = DB::table('pu_stores')->select('store_name','store_email','store_code','store_address1','store_address2','store_city','store_state','store_country','store_zipcode','store_phone','stores_prefix')->where('store_id', '=', Session::get('sess_store_id'))->first();

			$storePrefix = $StoreData->stores_prefix;
			
			$storeAddressStr1 = $StoreData->store_address1;
			if($StoreData->store_address2 != ''){
				$storeAddressStr1 .= ', '.$StoreData->store_address2;
			}
			if($StoreData->store_city != ''){
				$storeAddressStr2 = $StoreData->store_city;
			}
			if($StoreData->store_state != ''){
				$storeAddressStr2 .= ', '.$StoreData->store_state;
			}
			if($StoreData->store_zipcode != ''){
				$storeAddressStr2 .= ' '.$StoreData->store_zipcode;
			}
			if($StoreData->store_country != ''){
				$storeAddressStr3 = $StoreData->store_country;
			}
		}
		else{
			$storeAddressStr1 = '';
			$storeAddressStr2 = '';
			$storeAddressStr3 = '';
		}

		$OrderRs = Order::where('orders_id','=',$request->OrderID)->get();

		$orderTotal = 0;

		if(count($OrderRs)>0){
			$orderTotal = $OrderRs[0]['order_total'];
			$subTotal = (float)$OrderRs[0]['sub_total'];
		}

		$OrderDetailRs = OrderDetail::where('orders_id','=',$request->OrderID)->orderBy('orders_detail_id')->get();

		$orderItems = [];

		if(count($OrderDetailRs)>0){
			for($i=0;$i<count($OrderDetailRs);$i++){
				
				$prod_res = DB::table('pu_products as p')->select('p.product_name')->where(DB::raw('LOWER(TRIM(sku))'),'=',strtolower(trim($OrderDetailRs[$i]['sku'])))->limit(1)->get();
				
				$orderItems[] = [
					'SKU' => $OrderDetailRs[$i]['sku'],
					//'PName' => $OrderDetailRs[$i]['product_name'],
					'PName' => $prod_res[0]->product_name,
					'Qty' => $OrderDetailRs[$i]['quantity'],
					'ItemTotal' => $OrderDetailRs[$i]['total'],
				];
			}
		}

		$AutoDiscountLabel = (config('Settings.AUTO_DISCOUNT_FLAG') != '' ? config('Settings.AUTO_DISCOUNT_FLAG') : 'Auto Discount');
		$QuantityDiscountLabel = (config('Settings.QUANTITY_DISCOUNT_FLAG') != '' ? config('Settings.QUANTITY_DISCOUNT_FLAG') : 'Quantity Discount');
		$BogoDiscountLabel = (config('Settings.BOGO_DISCOUNT_FLAG') != '' ? config('Settings.BOGO_DISCOUNT_FLAG') : 'Bogo Discount');

		if (count($OrderDetailRs)>0) {
			return response()->json([
				'success' => '1',
				'orderNo' => $OrderRs[0]['orders_no'],
				'orderID' => $OrderRs[0]['orders_id'],
				'orderItems' => $orderItems,
				'storePrefix' => $storePrefix,
				'storeAddress1' => $storeAddressStr1,
				'storeAddress2' => $storeAddressStr2,
				'storeAddress3' => $storeAddressStr3,
				'storeUserName' => $storeUserName,
				'orderTotal' => $orderTotal,
				'subTotal' => $subTotal,
				'shippingAmt' => $OrderRs[0]['shipping_amt'],
				'tax' => $OrderRs[0]['tax'],
				'giftCharge' => $OrderRs[0]['gift_charge'],
				'routeShippingInsuranceCharge' => $OrderRs[0]['route_shipping_insurance_charge'],
				'shippingSignature' => $OrderRs[0]['shipping_signature'],
				'autoDiscount' => $OrderRs[0]['auto_discount'],
				'quantityDiscount' => $OrderRs[0]['quantity_discount'],
				'couponAmount' => $OrderRs[0]['coupon_amount'],
				'gcAmount' => $OrderRs[0]['gc_amount'],
				'referAmount' => $OrderRs[0]['refer_amount'],
				'bogoDiscount' => $OrderRs[0]['bogo_discount'],
				'applyCredit' => $OrderRs[0]['apply_credit'],
				'rewardDiscount' => $OrderRs[0]['reward_discount'],
				'AutoDiscountLabel' => $AutoDiscountLabel,
				'QuantityDiscountLabel' => $QuantityDiscountLabel,
				'BogoDiscountLabel' => $BogoDiscountLabel,
			]);
		} else {
			return response()->json([
				'success' => '0',
			]);
		}
	}

}
