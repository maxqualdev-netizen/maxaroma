<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\CartTrait;

use Hash;
use Session;
use App\Models\MetaInfo;
use App\Models\Customer;
use App\Models\AddressBook;
use App\Models\RewardPoint;
use App\Models\EmailTemplates;
use App\Models\ReferFriend;
use App\Models\RewardRule;
use App\Models\Order;
use App\Models\ProductsCategory;
use App\Models\Category;
use App\Models\Products;
use App\Models\OrderDetail;
use App\Models\GiftCertificate;
use App\Models\FreeGiftProduct;
use App\Models\ReturnOrders;
use App\Models\AdminCreditLog;
use App\Models\ProductsOne;
use App\Models\AdminFundLog;
use App\Models\Admin;
use App\Models\PaypalIpnLog;
use App\Models\AuthorizeFundLog;
use App\Models\AmazonFundLog;
use App\Models\DropshipperOrder;
use App\Models\PaymentMethod;
use App\Models\ImportDropshiporder;
use App\Models\DropshipperOrderDetail;
use App\Models\MarkupPrices;
use App\Models\Dealofweek;
use App\Models\Dealofweektitle;
use App\Models\ShippingMode;
use App\Models\ShippingRule;
use App\Models\ShippingRate;
use App\Models\WishlistCategory;
use App\Models\Wishlist;
use App\Models\Manufacture;
use App\Models\ProductsReview;

use App\Imports\ImportDropshiporderImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\Traits\CommonTrait;
use DB;
use Mail;

use Carbon\Carbon;
// use File;
use Illuminate\Support\Facades\File;

use PDF;

class CustomerController extends Controller
{
	use CommonTrait;
	use CartTrait;
	public $PageData;
	public function __construct()
    {
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
	}

	public function Register(Request $request)
	{
		$this->PageData['Countries'] = GetCountries();
		$this->PageData['States'] = GetStates();
		$this->PageData['YoutubeAttrs'] = GetCustomerAttribute('Youtube');
		$this->PageData['InstagramAttrs'] = GetCustomerAttribute('Instagram');
		$this->PageData['SelCountry'] = 'US';
		
		if(isset($request['action']) && $request['action'] == 'signup')
		{
			$this->PageData['SelCountry'] = $request['country'];
	        $validatedData = $request->validate([
								'first_name' => 'required',
								'last_name'	=> 'required',
								'address1' => 'required',
								'city' => 'required',
								'zip' => 'required',
								'phone' => 'required',
					            'email' => 'required|email',
					            'password' => 'required|same:confirm_Password',
					            'confirm_Password' => 'required',
					            'instagram' => 'required_if:hear_about,Instagram',
					            'youtube' => 'required_if:hear_about,Youtube',
					            'other' => 'required_if:hear_about,Other',
					            'state' => 'required_if:country,US',
					            'other_state' => 'required_unless:country,US',
					        ], [
					            'first_name.required' => config('message.Validate.FirstName'),
					            'last_name.required' => config('message.Validate.LastName'),
					            'address1.required' => config('message.Validate.Address'),
					            'city.required' => config('message.Validate.City'),
					            'zip.required' => config('message.Validate.ZipCode'),
					            'phone.required' => config('message.Validate.Phone'),
					            'email.required' => config('message.Validate.ValidEmail'),
					            'email.email' => config('message.Validate.ValidEmail'),
					            'password.required' => config('message.Register.Password'),
					            'password.same' => config('message.Validate.ValidConfirmPassword'),
					            'confirm_Password.required' => config('message.Validate.ValidConfirmPassword'),
					            'instagram.required_if' => config('message.Register.Instagram'),
					            'youtube.required_if' => config('message.Register.Youtube'),
					            'other.required_if' => config('message.Register.OtherText'),
					            'state.required_if' => config('message.Validate.State'),
					            'other_state.required_unless' => config('message.Validate.OtherState'),
					        ]);

			$ChkEmail = Customer::where('email','=',$request['email'])->where('registration_type','=','M')->get();
			if($ChkEmail && $ChkEmail->count() > 0)
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'existing_email' => config('message.Register.ExistingEmail'),
				]);
			}
			// $ChkIP = Customer::where('customer_ip','=',$_SERVER['REMOTE_ADDR'])->where('registration_type','=','M')->get();
			// if($ChkIP && $ChkIP->count() >= 5)
			// {
			// 	return redirect()->back()
			// 	->withInput()
			// 	->withErrors([
			// 		'duplicate_ip' => config('message.Register.DuplicateIP'),
			// 	]);
			// }
			$State = $request['state'];
			if($request['country'] != 'US')
				$State = $request['other_state'];
			$UserData = array(
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'company_name' => ($request['company_name'] != '') ? $request['company_name'] : '',
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '') ? $request['address2'] : '',
				'phone' => $request['phone'],
				'email' => $request['email'],
				'password' => $request['password'],
				'city' => $request['city'],
				'state' => $State,
				'country' => $request['country'],
				'zip' => $request['zip'],
				'status' => '1',
				'eusertype' => 'Retailer',
				'customer_ip' => $_SERVER['REMOTE_ADDR'],
				'customer_browser' => $_SERVER['HTTP_USER_AGENT'],
				'is_dropshipper' => "No",
				'iRewardpoint' => '150',
				'upd_datetime' => date('Y-m-d H:i:s'),
				'merge_log' => 'Auto updated from guest to member on registration page',
				'birthday' => ($request['birthday'] != null) ? $request['birthday'] : null,
				
			);
			
			$User = Customer::create($UserData);
			if($User)
			{
				$iCustomerId = $User->customer_id;
				$AddressBook=array();
				$AddressBook['customer_id'] = $iCustomerId;
				$AddressBook['title'] = 'Self Address';
				$AddressBook['email'] = $User->email;
				$AddressBook['first_name'] = $User->first_name;
				$AddressBook['last_name'] = $User->last_name;
				$AddressBook['address1'] = $User->address1;
				$AddressBook['address2'] = $User->address2;
				$AddressBook['company_name'] = $User->company_name;
				$AddressBook['city'] = $User->city;
				$AddressBook['state'] = $User->state;
				$AddressBook['country'] = $User->country;
				$AddressBook['zip'] = $User->zip;
				$AddressBook['phone'] = $User->phone;
				$AddressBook['status'] = '1';
				$UserAddress = AddressBook::create($AddressBook);
				
				$RewardPoints = array();
				$RewardPoints["customer_id"] = $iCustomerId;
				$RewardPoints["note"] = "Reward Point Added By Register";
				$RewardPoints["iRewardpoint"] = 150;
				$UserRewardPoints = RewardPoint::create($RewardPoints);
				
				$request->session()->regenerate();
				Session::put('sess_useremail',$User->email);
				Session::put('sess_username',$User->first_name);
				Session::put('sess_icustomerid',$User->customer_id);
				Session::put('eusertype',$User->eusertype);
				Session::put('is_dropshipper',$User->is_dropshipper);
				Session::put('etype','M');
				Session::put('eusertype','Retailer');
				Session::put('payment_amount',$User->payment_amount);
				
				Session::flash('success', config('message.Register.Success'));
				
				
				$Template = GetMailTemplate("CUSTOMER_REGISTER");
				$EmailBody = str_replace('{$vFirstName}',$User->first_name,$Template[0]->mail_body);
				$EmailBody = str_replace('{$vLastName}',$User->last_name,$EmailBody);
				$EmailBody = str_replace('{$vemail}',$User->email,$EmailBody);
				$EmailBody = str_replace('{$password}',$User->password,$EmailBody);
				$EmailBody = str_replace('{$COUPON_CODE_VALUE}',config('Settings.COUPON_CODE_VALUE'),$EmailBody);
				$EmailBody = str_replace('{$CONTACT_MAIL}',config('Settings.CONTACT_MAIL'),$EmailBody);
				$FreeShipping = "";
				if(config('Settings.FREESHIPPING_VALUE')) {
					$FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
				}
				$EmailBody = str_replace('{$freeshippinginfo}',$FreeShipping,$EmailBody);
				
				$To = $User->email;
				// $To = "qqualdev@gmail";
				$Subject = $Template[0]->subject;
				$EmailBody = $Template[0]->mail_body;
				$From = config('Settings.ADMIN_MAIL');
				SendMail($Subject,$EmailBody,$To,$From);

				Auth::login($User);
				$this->GenerateShopCartFromCookieAfterLogin();
				$this->StoreShopCartInCookie();
				return redirect('/myaccount.html');	
			} else {
				Session::flash('failed', config('message.Register.Failed'));
				return redirect()->back();
			}
		}
		$GTMDATA = ['page' => 'register', 'pagetype' => 'other'];
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Registration';
		$this->PageData['JSFILES'] = ['register.js','moment.min.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css','register.css'];	
		return view('customer.register')->with($this->PageData);
	}
	
	public function Login(Request $request)
	{
		if((isset($request->automated_login) && $request->automated_login == 'yes') || (isset($request->action) && $request->action == 'signin'))
		{
			if(!$request->automated_login)
			{
				$validatedData = $request->validate([
					            'email' => 'required|email',
					            'password' => 'required'
					        ], [
					            'email.required' => config('message.Login.ValidEmail'),
					            'email.email' => config('message.Login.ValidEmail'),
					            'password.required' => config('message.Login.Password')
					        ]);
			}
			if($request->automated_login == 'yes')
			{
				if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0)
				{
					Auth::logout();
				}
				$request->email = $request->vemail;
				
				$CustomerQry = Customer::where('email', $request->email)
						->where('status','1')
						->where('registration_type','M');
				if($request->vpassword != 'null' && $request->vpassword != null && $request->vpassword != '')
				{		
					$CustomerQry->where('password',$request->vpassword);
				}
			} else {
				$CustomerQry = Customer::where('email', $request->email)
				->where('password',$request->password)
				->where('status','1')
				->where('registration_type','M');
			}
			
			$remember_me = $request->has('rememberMe') ? true : false; 
			
			$Customer = $CustomerQry->first();
			//$remember_me = $request->has('rememberMe') ? true : false; 	  
			$remember_me = false; 
			if($Customer && $Customer->count() > 0)      
			{
				if($Customer->eusertype=="Wholesaler Pending")
				{
				  $Customer->eusertype = "Retailer";
				}
				Auth::login($Customer,$remember_me);
				//Session::forget('ShoppingCart');
				$request->session()->regenerate();
				Session::put('sess_useremail',$Customer->email);
				Session::put('sess_username',$Customer->first_name);
				Session::put('sess_icustomerid',$Customer->customer_id);
				Session::put('eusertype',$Customer->eusertype);
				Session::put('is_dropshipper',$Customer->is_dropshipper);
				Session::put('SpecialCustomerFlag',$Customer->DownloadSpecialPricelist);
				Session::put('etype','M');
				Session::put('payment_amount',$Customer->payment_amount);
				Session::forget('ShoppingCart.BillingAddress');
				Session::forget('ShoppingCart.ShippingAddress');
				$this->GenerateShopCartFromCookieAfterLogin();
				$this->StoreShopCartInCookie();
				if(isset($request->page) && $request->page == 'billing')	  
					return redirect('/billing');
				else if(isset($request->page) && $request->page == 'paypal')	  
					return redirect('/billing/paypal');		
				else
					return redirect('/myaccount.html');	
			} else {
				return redirect()->back()
				->withInput()
				->withErrors([
					'Failed' => config('message.Login.Failed'),
				]);
			}
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Member Login';
		$this->PageData['JSFILES'] = ['login.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('customer.login')->with($this->PageData);
	}

	public function Logout(Request $request)
	{
		Auth::logout();
		$request->session()->forget('sess_useremail');
		$request->session()->forget('sess_username');
		$request->session()->forget('sess_icustomerid');
		$request->session()->forget('eusertype');
		$request->session()->forget('is_dropshipper');
		$request->session()->forget('etype');
		$request->session()->forget('payment_amount');
		$this->ReformatCartPrice();
		return redirect(config('global.SITE_URL'));
	}

	public function Myaccount(Request $request)
	{
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Account Overview';
		$GTMDATA = ['page' => 'myaccount', 'pagetype' => 'other'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		return view('myaccount.dashboard')->with($this->PageData);
	}

	public function ForgotPassword(Request $request)
	{
		if(isset($request->action) && $request->action == 'forgot_password')
		{
	        $validatedData = $request->validate([
					            'email' => 'required|email'
					        ], [
					            'email.required' => config('message.Forgot.ValidEmail'),
					            'email.email' => config('message.Forgot.ValidEmail')
					        ]);
			$ChkEmail = Customer::where('email','=',$request['email'])->where('status','=','1')->get();
			if($ChkEmail)
			{
				if($ChkEmail->count() == 0)
				{
					return redirect()->back()
					->withInput()
					->withErrors([
						'NotExistEmail' => config('message.Forgot.NotExistEmail'),
					]);
				} else {
					$FreeShipping = "";
					if(config('Settings.FREESHIPPING_VALUE'))	
						$FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
					$Template = GetMailTemplate("FORGOT_PASSWORD");
					$EmailBody = $Template[0]->mail_body;
					$EmailBody = str_replace('{$SITE_NAME}',config('Settings.SITE_TITLE'),$EmailBody);
					$EmailBody = str_replace('{$freeshippinginfo}',$FreeShipping,$EmailBody);
					$EmailBody = str_replace('{$vemail}',$ChkEmail[0]->email,$EmailBody);
					$EmailBody = str_replace('{$password}',$ChkEmail[0]->password,$EmailBody);
					$EmailBody = str_replace('{$TOLL_FREE_NO}',config('Settings.TOLL_FREE_NO'),$EmailBody);
					$EmailBody = str_replace('{$CONTACT_MAIL}',config('Settings.CONTACT_MAIL'),$EmailBody);
					
					$To = $ChkEmail[0]->email;
					$Subject = str_replace('{$SITE_NAME}',config('Settings.SITE_TITLE'),$Template[0]->subject);
					$From = config('Settings.CONTACT_MAIL');
					SendMail($Subject,$EmailBody,$To,$From);
					Session::flash('success', config('message.Forgot.Success'));
					return redirect()->back();
				}
			}
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Forgot Password';
		$this->PageData['JSFILES'] = ['forgotpassword.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('customer.forgotpassword')->with($this->PageData);
	}

	public function SendMails(Request $request)
	{
		$Template = GetMailTemplate("FORGOT_PASSWORD");
		$To = 'qqualdev@gmail.com';
		$Subject = $Template[0]->subject;
		$EmailBody = $Template[0]->mail_body;
		$From = "qualdev.devs@gmail.com";
		SendMail($Subject,$EmailBody,$To,$From);
	}

	public function EditProfile(Request $request)
	{	
		$this->PageData['Countries'] = GetCountries();
		$this->PageData['States'] = GetStates();
		$this->PageData['SelCountry'] = 'US';

		$Userdata = Customer::select('customer_id', 'email', 'first_name', 'last_name', 'address1', 'address2', 'city', 'country', 'state', 'zip', 'phone', 'company_name', 'birthday', 'eusertype', 'salestax_id', 'einnumber')
								->where('customer_id', '=', Auth::user()->customer_id)
								->get();
		if($request['action'] == 'update')
		{
			$this->PageData['SelCountry'] = $request['country'];

	        $validatedData = $request->validate([
								'first_name' => 'required',
								'last_name'	=> 'required',
								'address1' => 'required',
								'city' => 'required',
								'zip' => 'required',
								'phone' => 'required',
								'country' => 'required',
					            'state' => 'required_if:country,US',
					            'other_state' => 'required_unless:country,US',
					            // 'einnumber' => 'required_if:state,NY',	// Sometime means if 'einnumber' exists in request array then it will be required
					        ], [
					            'first_name.required' => config('message.Validate.FirstName'),
					            'last_name.required' => config('message.Validate.LastName'),
					            'address1.required' => config('message.Validate.Address'),
					            'city.required' => config('message.Validate.City'),
					            'zip.required' => config('message.Validate.ZipCode'),
					            'phone.required' => config('message.Validate.Phone'),
					            'country.required' => config('message.Validate.Country'),
					            'state.required_if' => config('message.Validate.State'),
					            'other_state.required_unless' => config('message.Validate.OtherState'),
					            // 'einnumber.required_if' => config('message.Register.EINnumber'),
					        ]);

			if($request['country'] == 'US' && $request['state'] == 'NY' && Session::get("eusertype")=="Wholesaler" && $request['einnumber'] == '')
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'einnumber' => config('message.Register.EINnumber'),
				]);
			}
			$state = $request['state'];
			if($request['country'] != 'US')
				$state = $request['other_state'];
			$UserDataArray = array(
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'company_name' => ($request['company_name'] != '') ? $request['company_name'] : '',
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '') ? $request['address2'] : '',
				'birthday' => ($request['birthday'] != null) ? $request['birthday'] : null,
				'phone' => $request['phone'],
				'city' => $request['city'],
				'state' => $state,
				'country' => $request['country'],
				'zip' => $request['zip'],
				'upd_datetime' => date('Y-m-d H:i:s')
			);

			$UserDataArray['einnumber'] = "";
			if(Session::get("eusertype")=="Wholesaler" && $UserDataArray['state'] == 'NY' && $UserDataArray['country'] == 'US' )
		    {
			 	// $UserDataArray['salestax_id'] = $UserDataArray["salestax_id"];
			 	$UserDataArray['einnumber'] = $request['einnumber'];				
			}

			$User = Customer::find(Auth::user()->customer_id);
			
			$User->update($UserDataArray); 
			if($User)
			{
				Session::flash('success', config('message.EditProfile.Success'));
				return redirect()->back();
			}
		}
		if($Userdata[0]->birthday != null) {
			$this->PageData['CSSFILES'] = ['myaccount.css'];	
		} else {
			$this->PageData['CSSFILES'] = ['myaccount.css', 'datepicker.css'];	
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Edit Profile';
		$this->PageData['JSFILES'] = ['moment.min.js', 'editprofile.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		$this->PageData['Userdata'] = $Userdata;	
		return view('myaccount.editprofile')->with($this->PageData);
	}

	public function ChangePassword(Request $request)
	{	
		if(isset($request['old_password']))
		{
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
	        if(!preg_match('/[a-z]/', $request->new_password) || !preg_match('/[A-Z]/', $request->new_password) || !preg_match('/[0-9]/', $request->new_password))
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'uppercase_number' => config('message.Validate.UpperCaseAndLetter')
				]);
			}
			$checkOldPassword = Customer::where('customer_id','=',Auth::user()->customer_id)->where('password','=',$request['old_password'])->get();
			
			if($checkOldPassword->count() <= 0)
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'wrong_password' => config('message.ChangePassword.WrongOldPassword')
				]);
			}
			else
			{
				$UserData = array(
					'password' => $request['new_password'],
				);
				$User = Customer::find(Auth::user()->customer_id);
				$User->update($UserData); 
				if($User)
				{
					Session::flash('success', config('message.ChangePassword.Success'));
					return redirect()->back();
				}
			}
		}	

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Change Password';
		$this->PageData['JSFILES'] = ['changepassword.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		return view('myaccount.changepassword')->with($this->PageData);
	}

	public function ReferralCustomer(Request $request)
	{
		$referral_customer = ReferFriend::select('receiver', 'refer_comment', 'is_sender_notified', DB::raw('DATE_FORMAT(refer_datetime, "%m/%d/%Y %H:%i") as datetime'))
							->where('customer_id', '=', Auth::user()->customer_id)
							->paginate(10);

		$this->PageData['referral_customer'] = $referral_customer;

		$reward_point = 0;
		if(strtolower(Session::get('eusertype')) == 'retailer') {
			$rewrd_cust = Customer::select('iRewardpoint')
								->where('customer_id', '=', Auth::user()->customer_id)
								->get();
			$reward_point = $rewrd_cust[0]['iRewardpoint'];
		}
		$this->PageData['reward_point'] = $reward_point;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Referral Customer List';	
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];	
		return view('myaccount.referralcustomer')->with($this->PageData);
	}

	public function AddressBook(Request $request)
	{
		$this->PageData['address_book'] = AddressBook::select('address_book_id', 'first_name', 'last_name', 'company_name', 'address1', 'address2', 'phone', 'city', 'zip', 'state', 'country')
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('status', '=', '1')
							->where('title', '!=', 'Self Address')
							->paginate(10);

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Address Book';
		$this->PageData['JSFILES'] = ['addressbook.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];
		return view('myaccount.addressbooklist')->with($this->PageData);
	}

	public function AddAddressbook(Request $request)
	{
		$this->PageData['Countries'] = GetCountries();
		$this->PageData['States'] = GetStates();
		if($request['action'] == 'submit') {

	        $validatedData = $request->validate([
								'first_name' => 'required',
								'last_name'	=> 'required',
								'address1' => 'required',
								'city' => 'required',
								'country' => 'required',
								'zip' => 'required',
								'phone' => 'required',
					            'email' => 'required|email',
					            'state' => 'required_if:country,US',
					            'other_state' => 'required_unless:country,US',
					        ], [
					            'first_name.required' => config('message.Validate.FirstName'),
					            'last_name.required' => config('message.Validate.LastName'),
					            'address1.required' => config('message.Validate.Address'),
					            'city.required' => config('message.Validate.City'),
					            'country.required' => config('message.Validate.Country'),
					            'zip.required' => config('message.Validate.ZipCode'),
					            'phone.required' => config('message.Validate.Phone'),
					            'email.required' => config('message.Validate.ValidEmail'),
					            'email.email' => config('message.Validate.ValidEmail'),
					            'state.required_if' => config('message.Validate.State'),
					            'other_state.required_unless' => config('message.Validate.OtherState'),
					        ]);

			$state = $request['state'];
			if($request['country'] != 'US')
				$state = $request['other_state'];
			$AddressDataArray = array(
				'customer_id' => Auth::user()->customer_id,
				// 'title' => $request['title'],
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '')?$request['address2']:'',
				'city' => $request['city'],
				'state' => $state,
				'zip' => $request['zip'],
				'country' => $request['country'],
				'email' => $request['email'],
				'phone' => $request['phone'],
				'status' => '1',
			);
			$Address = Addressbook::create($AddressDataArray);
			if($Address) {
				Session::flash('success', config('message.Addressbook.AddSuccess'));
				return redirect(config('global.SITE_URL').'addressbook.html');
			}
			
		}
		
		$this->PageData['SelCountry'] = 'US';
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Address Book';
		$this->PageData['JSFILES'] = ['addressbook.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		return view('myaccount.addaddressbook')->with($this->PageData);
	}

	public function EditAddressbook(Request $request)
	{	
		$this->PageData['Countries'] = GetCountries();
		$this->PageData['States'] = GetStates();
		$this->PageData['SelCountry'] = 'US';
		if($request['action'] == 'update') {

	        $validatedData = $request->validate([
								'first_name' => 'required',
								'last_name'	=> 'required',
								'address1' => 'required',
								'city' => 'required',
								'country' => 'required',
								'zip' => 'required',
								'phone' => 'required',
					            'email' => 'required|email',
					            'state' => 'required_if:country,US',
					            'other_state' => 'required_unless:country,US',
					        ], [
					            'first_name.required' => config('message.Validate.FirstName'),
					            'last_name.required' => config('message.Validate.LastName'),
					            'address1.required' => config('message.Validate.Address'),
					            'city.required' => config('message.Validate.City'),
					            'country.required' => config('message.Validate.Country'),
					            'zip.required' => config('message.Validate.ZipCode'),
					            'phone.required' => config('message.Validate.Phone'),
					            'email.required' => config('message.Validate.ValidEmail'),
					            'email.email' => config('message.Validate.ValidEmail'),
					            'state.required_if' => config('message.Validate.State'),
					            'other_state.required_unless' => config('message.Validate.OtherState'),
					        ]);

			$state = $request['state'];
			if($request['country'] != 'US')
				$state = $request['other_state'];
			$AddressDataArray = array(
				'customer_id' => Auth::user()->customer_id,
				// 'title' => $request['title'],
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '')?$request['address2']:'',
				'city' => $request['city'],
				'state' => $state,
				'zip' => $request['zip'],
				'country' => $request['country'],
				'phone' => $request['phone'],
				'status' => '1',
				'email' => $request['email']
			);
			$Address = Addressbook::find($request['id']);
			$Address->update($AddressDataArray);
			if($Address) {
				Session::flash('success', config('message.Addressbook.EditSuccess'));
				return redirect(config('global.SITE_URL').'addressbook.html');
			}
		}

		$this->PageData['addressdata'] = Addressbook::where('address_book_id','=',$request['id'])->where('status','=','1')->where('customer_id','=',Auth::user()->customer_id)->first();

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Address Book';
		$this->PageData['JSFILES'] = ['addressbook.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('myaccount.editaddressbook')->with($this->PageData);
	}

	public function RemoveAddressbook(Request $request)
	{
		if($request->ajax()){
			if(!Auth::user()) {
		        $response['status'] = 401;
		        $response['message'] = "Unauthorized request";
		        return response()->json($response, 400);
			} else {
				if(isset($request->id) && ($request->id) != '') {
					Addressbook::where('address_book_id',$request->id)->delete();
			        $response['status'] = 200;
			        $response['message'] = "Success";
			        return response()->json($response, 200);
				} else {
			        $response['status'] = 400;
			        $response['message'] = "Bad parameters";
			        return response()->json($response, 400);
				}
			}
		} else {
	        $response['status'] = 404;
	        $response['message'] = "Not found";
	        return response()->json($response, 400);
		}
	}

	public function MyRewardPoint(Request $request)
	{
		if(strtolower(Session::get('eusertype')) == 'retailer') {
			$rewrd_cust = Customer::select('iRewardpoint')
								->where('customer_id', '=', Auth::user()->customer_id)
								->get();
			$this->PageData['reward_point'] = $rewrd_cust[0]['iRewardpoint'];

			$max_reward = RewardRule::select('fcharge')
								->where('erewardrule', '=', 'max')
								->get();
			$this->PageData['maximum_reward_point'] = (int)$max_reward[0]['fcharge'];

			$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: My Reward Points';
			return view('myaccount.myrewardpoint')->with($this->PageData);
		} else {
			return redirect(config('global.SITE_URL'));
		}
	}

	public function OrderCancel(Request $request)
	{
		/*$status_array = ['Pending','Canceled','Request Cancellation','Cancellation Approved','Sent To Stripe'];
		$prodCntRes = Order::select('orders_id', DB::raw('DATE_FORMAT(order_datetime, "%m/%d/%Y %H:%i") as datetime, DATEDIFF(now(), order_datetime) as days'))
							->where('customer_id', '=', Auth::user()->customer_id)
							->whereIn('status', $status_array)
							->orderBy('order_datetime', 'DESC')
							->orderBy('orders_id', 'DESC')
							->get();
		$this->PageData['prodCntRes'] = $prodCntRes;*/
		//dd($prodCntRes);

		$order_status_array = ['Canceled','Request Cancellation','Cancellation Approved']; //'Pending'
		$OrdResult = Order::select('orders_id', 'orders_no', 'sub_total', 'order_total', 'refund_amount', 'pay_status', 'status', 'payment_type', 'use_credit_limit', 'payment_method', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime,DATEDIFF(now(), order_datetime) as days"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->whereIn('status', $order_status_array)
							->orderBy('order_datetime', 'DESC')
							->orderBy('orders_id', 'DESC')
							->paginate(50);
		if($OrdResult && $OrdResult->count() > 0) {
			foreach ($OrdResult as $order_result_key => $order_result_value) {
				// $order_detail_status_array = ['Pending','Completed','Return Requested','Return Approved','Return Rejected','Item Received'];
				$OrdDetailResult = OrderDetail::select('pu_order_detail.orders_detail_id', 'pu_order_detail.product_name', 'pu_order_detail.sku', 'pu_order_detail.price', 'pu_order_detail.ostatus', 'pu_order_detail.quantity', 'pu_order_detail.total')
									->where('pu_order_detail.orders_id', $order_result_value['orders_id'])
									// ->whereIn('pu_order_detail.ostatus', $order_detail_status_array)
									->whereRaw("(pu_order_detail.is_free_gift_products='No' || pu_order_detail.is_free_gift_products='')")
									->orderBy('pu_order_detail.orders_detail_id', 'DESC')
									->get();

				if(count($OrdDetailResult) > 0) {
					foreach ($OrdDetailResult as $order_details_key => $order_details_value) {
						if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->select('image')->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
							$OrdDetailResult[$order_details_key]['Image'] = $thumb_image;
						}

					}
				}

				$OrdResult[$order_result_key]['order_items'] = $OrdDetailResult;
			}
		}
		$this->PageData['OrdResult'] = $OrdResult;
		// dd($OrdResult);
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order Cancel';
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];

		return view('myaccount.ordercancel')->with($this->PageData);
	}

	public function OrderHistory(Request $request)
	{
		$isval		 = $request['isval'];
		$orders_no	 = isset($request['orders_no']) ? $request['orders_no'] : '';
		$this->PageData['orders_no'] = $orders_no;
		$FilterByOrderNo = isset($request['FilterByOrderNo']) ? $request['FilterByOrderNo'] : 'YourOrderNo';
		$this->PageData['FilterByOrderNo'] = $FilterByOrderNo;
		/*$prodCntResQuery = Order::select('orders_id')
								->where('customer_id', '=', Auth::user()->customer_id);
		$status_array = ['Pending','Canceled','Refund','Sent To Stripe'];
		if($isval == "yes")
		{
			$prodCntResQuery->whereIn('status', $status_array)
							->where('payment_type', '=', 'PAYMENT_DS');
		}
		if($orders_no != ""){
			$prodCntResQuery->where('orders_no', '=', $orders_no);
		}
		$prodCntRes = $prodCntResQuery->groupBy('orders_id')
									->orderBy('order_datetime', 'DESC')
									->orderBy('orders_id', 'DESC')
									->get();
		$this->PageData['prodCntRes'] = $prodCntRes;
		$total_count = count($prodCntRes);*/

		$OrdResultQuery = Order::select('orders_id', 'orders_no', 'sub_total', 'ship_status','order_total', 'refund_amount', 'pay_status', 'status', 'payment_type', 'use_credit_limit', 'payment_method', 'phoneorder_payby', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime,DATEDIFF(now(), order_datetime) as days"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('status', '!=', 'Declined');
		$status_array = ['Pending','Completed','Refund'];
		if($isval == "yes")
		{
			$OrdResultQuery->whereIn('status', $status_array)
							->where('payment_type', '=', 'PAYMENT_DS');
		}

		/*if($orders_no != ""){
			if($request['FilterByOrderNo'] == 'MaxaromaOrderNo') {
				$OrdResultQuery->where('orders_no', '=', $orders_no);
			}
			if($request['FilterByOrderNo'] == 'YourOrderNo') {
				$OrdResultQuery->where('orders_id', '=', $orders_no);
			}
		}*/

		if($FilterByOrderNo!='') {
			if($orders_no != "") {
				$OrdResultQuery->whereRaw("(orders_no = '".$orders_no."' OR dropshipper_order_no='".$orders_no."')");
			}
		}
		$OrdResult = $OrdResultQuery->orderBy('order_datetime', 'DESC')
									->orderBy('orders_id', 'DESC')
									->paginate(50);
		if(count($OrdResult) > 0) {
			foreach ($OrdResult as $order_result_key => $order_result_value) {
				$order_detail_status_array = ['Pending','Completed','Return Requested','Return Approved','Return Rejected','Item Received'];
				$OrdDetailResult = OrderDetail::select('pu_order_detail.orders_detail_id', 'pu_order_detail.product_name', 'pu_order_detail.sku', 'pu_order_detail.price', 'pu_order_detail.ostatus', 'pu_order_detail.quantity', 'pu_order_detail.total')
									->where('pu_order_detail.orders_id', $order_result_value['orders_id'])
									->whereIn('pu_order_detail.ostatus', $order_detail_status_array)
									->whereRaw("(pu_order_detail.is_free_gift_products='No' || pu_order_detail.is_free_gift_products='')")
									->orderBy('pu_order_detail.orders_detail_id', 'DESC')
									->get();

				if(count($OrdDetailResult) > 0) {
					foreach ($OrdDetailResult as $order_details_key => $order_details_value) {
						if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->select('image')->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
							$OrdDetailResult[$order_details_key]['Image'] = $thumb_image;
						}

                        $return_res = ReturnOrders::select('reason', 'quantity', 'orders_detail_id', 'return_shipping_label')
													->where('orders_id', '=', $order_result_value['orders_id'])
													->get();
						$tot_reqs = count($return_res);
						if($tot_reqs > 0)
						{
							for($d=0; $d < $tot_reqs; $d++){
								$reason_arr = explode("##",$return_res[$d]["reason"]);
								$qty_arr = explode("##",$return_res[$d]["quantity"]);
								$order_detail_arr = explode("##",$return_res[$d]["orders_detail_id"]);
								foreach($order_detail_arr as $key_ids => $detail_ids){
									$Return_data[$detail_ids]['reason'] = $reason_arr[$key_ids];
									$Return_data[$detail_ids]['qty'] = $qty_arr[$key_ids];
									
									$Return_data[$detail_ids]['base_orderdetailid'] = $order_detail_arr[0];
									$Return_data[$detail_ids]['returnlabel'] = $return_res[$d]["return_shipping_label"];
								}
							}	
						}
						$OrdDetailResult[$order_details_key]["Reason"]  = '';
						$OrdDetailResult[$order_details_key]["Qty"]  = 0;

						$OrdDetailResult[$order_details_key]["return_shipping_label"]  = '';

						if(!empty($Return_data[$order_details_value["orders_detail_id"]])){
							$OrdDetailResult[$order_details_key]["Qty"] = $Return_data[$order_details_value["orders_detail_id"]]['qty'];
							$OrdDetailResult[$order_details_key]["Reason"] = $Return_data[$order_details_value["orders_detail_id"]]['reason'];

							$OrdDetailResult[$order_details_key]["COrderNo"] = "Return-Authorization-Order-".$order_result_value["orders_no"]."-".$Return_data[$order_details_value["orders_detail_id"]]['base_orderdetailid'].".pdf";

							$ShippingLabel = '';
							
							$returnlabel = $Return_data[$order_details_value["orders_detail_id"]]['returnlabel'];
							if($returnlabel != "" && file_exists(config('global.RETURN_PDF_PATH').$returnlabel))
							{
								$ShippingLabel = '<a href="'.url('/returnord/'.$Return_data[$order_details_value["orders_detail_id"]]['returnlabel']).'" target="_blank" class="button btn-1 btn-small" style="padding-left:8px;">Print Shipping Label</a>';
								$OrdDetailResult[$order_details_key]["return_shipping_label"] =$ShippingLabel;
							}
							else
							{
								$OrdDetailResult[$order_details_key]["return_shipping_label"]  = '';
							}

						}

						if($order_result_value["ostatus"]!="Pending" && $order_result_value["ostatus"]!="Completed")
						{
							$OrdDetailResult[$order_details_key]["TotalPriceVal"] = $OrdDetailResult[$order_details_key]["Qty"] * $order_result_value["price"];
							$OrdDetailResult[$order_details_key]["TotalPriceVal"] = number_format($OrdDetailResult[$order_details_key]["TotalPriceVal"],2,'.','');
						}

						if($tot_reqs == 1 && !empty($Return_data[$order_result_value["orders_detail_id"]])){
							$Data_Return[$order_result_key]['COrderNo'] = $OrdDetailResult[$order_details_key]["COrderNo"];
							$Data_Return[$order_result_key]['return_shipping_label'] = $OrdDetailResult[$order_details_key]["return_shipping_label"];
						}

					}
				}

				$OrdResult[$order_result_key]['order_items'] = $OrdDetailResult;
			}
		}
		$this->PageData['OrdResult'] = $OrdResult;

		if(Session::has('SKULISTARR') && count(Session::get('SKULISTARR')) > 0) {
			$skuval = implode("','", Session::get('SKULISTARR'));
			$OutOfStockMsg = "Following products sku(s) are out of stock or inactive <br/>".$skuval;
			Session::forget('SKULISTARR');
		} else {
			$OutOfStockMsg = '';
		}
		// $OutOfStockMsg = 'Following products sku(s) are out of stock or inactive';
		$this->PageData['OutOfStockMsg'] = $OutOfStockMsg;
		if(Session::has('OrderNotProcess') && count(Session::get('OrderNotProcess')) > 0) {
			$skuval = implode("','", Session::get('OrderNotProcess'));
			$OrderProcessMsg = "Following order(s) are not processed,shipping method is not selected  <br/>".$skuval;
			Session::forget('OrderNotProcess');
		} else {
			$OrderProcessMsg = '';
		}
		// $OrderProcessMsg = 'Following order(s) are not processed,shipping method is not selected';
		$this->PageData['OrderProcessMsg'] = $OrderProcessMsg;

		$view_fileType = "";
		if(Session::get('eusertype') == "Wholesaler" || (Session::get('eusertype')=="Wholesaler" && Session::get('is_dropshipper')=="Yes")) {
			$view_fileType  = 'pdf';
		} else {
			$view_fileType = 'print';
		}
		$this->PageData['view_fileType'] = $view_fileType;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order History';
		$this->PageData['JSFILES'] = ['orderhistory.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];

		return view('myaccount.orderhistory')->with($this->PageData);
	}

    public function OrderReturnHistory(Request $request)
	{

		$ostatus_array = ['Return Requested','Return Approved','Return Rejected','Item Received'];
		$odstatus_array = ['Return Requested','Return Approved','Return Rejected','Item Received'];
	
		$OrderReturnsHistory = DB::table('pu_orders as o')
						->select('o.order_datetime','od.price as unitprice','od.ostatus as ostatus','od.orders_detail_id','od.quantity','od.total','od.product_name','od.sku','od.price','o.orders_id', 'o.orders_no', 'o.sub_total', 'o.order_total', 'o.refund_amount', 'o.pay_status', 'o.status', 'o.payment_type', 'o.use_credit_limit', 'o.payment_method', 'o.phoneorder_payby')
						->join('pu_order_detail as od', 'o.orders_id', '=', 'od.orders_id')
						->where('o.customer_id', '=', Auth::user()->customer_id)
						->whereIn('o.status', $ostatus_array)
						->whereIn('od.ostatus', $odstatus_array)
						->orderBy('order_datetime', 'DESC')
						->orderBy('orders_id', 'DESC')
						->paginate(10);

				if(count($OrderReturnsHistory) > 0) {
					foreach ($OrderReturnsHistory as $order_details_key => $order_details_value) {

						
						$return_res = ReturnOrders::select('reason', 'quantity', 'orders_detail_id', 'return_shipping_label')
													->where('orders_id', '=', $order_details_value->orders_id)
													->where('orders_detail_id', '=', $order_details_value->orders_detail_id)
													->get();

						$OrderReturnsHistory[$order_details_key]->Reason  = '';
						if(count($return_res) > 0)
						{
							$OrderReturnsHistory[$order_details_key]->Reason = $return_res[0]["reason"];
						}
				
						$OrderReturnsHistory[$order_details_key]->Qty	=	$return_res[0]["quantity"];
				
						$OrderReturnsHistory[$order_details_key]->COrderNo = "Return-Authorization-Order-".$OrderReturnsHistory[$order_details_key]->orders_no."-".$OrderReturnsHistory[$order_details_key]->orders_detail_id.".pdf";
				
						if($OrderReturnsHistory[$order_details_key]->ostatus!="Pending" && $OrderReturnsHistory[$order_details_key]->ostatus!="Completed")
						{
							$OrderReturnsHistory[$order_details_key]->TotalPriceVal =  $OrderReturnsHistory[$order_details_key]->Qty * $OrderReturnsHistory[$order_details_key]->price;
							$OrderReturnsHistory[$order_details_key]->TotalPriceVal = number_format($OrderReturnsHistory[$order_details_key]->TotalPriceVal,2,'.','');
				
						}
				
						$ShippingLabel = '';
						if(file_exists(config('global.RETURN_PDF_PATH').'Return-Shipping-Label-'.$OrderReturnsHistory[$order_details_key]->orders_no."-".$OrderReturnsHistory[$order_details_key]->orders_detail_id.".pdf"))
						{
							$ShippingLabel = '<a href="'.config('global.SITE_URL').'/returnord/Return-Shipping-Label-'.$OrderReturnsHistory[$order_details_key]->orders_no.'-'.$OrderReturnsHistory[$order_details_key]->orders_detail_id.'.pdf" target="_blank" class="button btn-1 btn-small" style="padding-left:8px;">Print Shipping Label</a>';
							$OrderReturnsHistory[$order_details_key]->return_shipping_label =$ShippingLabel;
						}
						else
						{
							$OrderReturnsHistory[$order_details_key]->return_shipping_label  = '';
						}
						
						if($order_details_value->sku==config('global.GIFT_CERTIFICATE_SKU'))
						{
							$GCRs = GiftCertificate::where('orders_detail_id', '=', $order_details_value->orders_detail_id)
								->where('customer_id', '=', Auth::user()->customer_id)
								->first();
							if($GCRs && $GCRs->count() > 0) {
								$OrderReturnsHistory[$order_details_key]->RecipientName  	= $GCRs->recipient_name;
								$OrderReturnsHistory[$order_details_key]->RecipientEmail 	= $GCRs->recipient_email;
								$OrderReturnsHistory[$order_details_key]->SenderName  	= $GCRs->your_name;
								$OrderReturnsHistory[$order_details_key]->SenderEmail 	= $GCRs->your_email;
								$OrderReturnsHistory[$order_details_key]->Image			= config('global.GC_IMAGE_URL');
							}
						}
						else if($order_details_value->sku==config('global.GIFT_CERTIFICATE_SKU1'))
						{
							$GCRs = GiftCertificate::where('orders_detail_id', '=', $order_details_value->orders_detail_id)
								->where('customer_id', '=', Auth::user()->customer_id)
								->first();
							if($GCRs && $GCRs->count() > 0) {
								$OrderReturnsHistory[$order_details_key]->RecipientName  	= $GCRs->recipient_name;
								$OrderReturnsHistory[$order_details_key]->RecipientEmail 	= $GCRs->recipient_email;
								$OrderReturnsHistory[$order_details_key]->SenderName  	= $GCRs->your_name;
								$OrderReturnsHistory[$order_details_key]->SenderEmail 	= $GCRs->your_email;
								$OrderReturnsHistory[$order_details_key]->Image			= config('global.GC_IMAGE_URL1');
							}
						}
						else if($order_details_value->sku==config('global.GIFT_CERTIFICATE_SKU2'))
						{
							$GCRs = GiftCertificate::where('orders_detail_id', '=', $order_details_value->orders_detail_id)
								->where('customer_id', '=', Auth::user()->customer_id)
								->first();
							if($GCRs && $GCRs->count() > 0) {
								$OrderReturnsHistory[$order_details_key]->RecipientName  	= $GCRs->recipient_name;
								$OrderReturnsHistory[$order_details_key]->RecipientEmail 	= $GCRs->recipient_email;
								$OrderReturnsHistory[$order_details_key]->SenderName  	= $GCRs->your_name;
								$OrderReturnsHistory[$order_details_key]->SenderEmail 	= $GCRs->your_email;
								$OrderReturnsHistory[$order_details_key]->Image			= config('global.GC_IMAGE_URL2');
							}
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value->sku))."'")->select('image')->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
							$OrderReturnsHistory[$order_details_key]->Image = $thumb_image;
						}

					}
				}

		$this->PageData['OrdReturnHistory'] = $OrderReturnsHistory;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order History';
		$this->PageData['JSFILES'] = ['orderhistory.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];

		return view('myaccount.orderreturnhistory')->with($this->PageData);
	}

	public function OrderDetailPrint(Request $request)
	{
		$orders_id = (int)$request['id'];

		if(!Auth::user()){
			$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
							->where('orders_id', '=', $orders_id)
							->first();
		}else{
			$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('orders_id', '=', $orders_id)
							->first();
		}
		$GC_Only = 0;
		if($OrderRs && $OrderRs->count() <= 0) {
			return redirect('/myaccount.html');
		} else {
			$this->PageData['OrderRs'] = $OrderRs;
			$track_flag=1;
			$OrderDetailRs = OrderDetail::select('*')
								->where('orders_id', '=', $OrderRs->orders_id)
								->orderBy('orders_detail_id', 'DESC')
								->get();
			if(count($OrderDetailRs) > 0) {

				foreach ($OrderDetailRs as $order_details_key => $order_details_value) {
					if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
					}
					else
					{
						if($order_details_value["is_free_gift_products"]=="Yes")
						{
							$prod_res = FreeGiftProduct::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->product_image) && !empty($prod_res->product_image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->product_image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						$OrderDetailRs[$order_details_key]['Image'] = $thumb_image;
					}
				}

			}
			if($OrderRs->is_only_gc==1) {
				$GC_Only = 1;
			}
		}
		$this->PageData['GC_Only'] = $GC_Only;
		$this->PageData['OrderDetailRs'] = $OrderDetailRs;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order Detail';	
		$this->PageData['JSFILES'] = ['printpage.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css'];

		return view('myaccount.orderdetailprint')->with($this->PageData);
	}

	public function OrderDetail(Request $request)
	{
		$orders_id = (int)$request['id'];

		$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('orders_id', '=', $orders_id)
							->first();
		$GC_Only = 0;
    
		if($OrderRs && $OrderRs->count() <= 0) {
			return redirect('/myaccount.html');
		} else {
			$this->PageData['OrderRs'] = $OrderRs;
			$OrderDetailRs = OrderDetail::select('*')
								->where('orders_id', '=', $OrderRs->orders_id)
								->orderBy('orders_detail_id', 'DESC')
								->get();
			if(count($OrderDetailRs) > 0) {

				foreach ($OrderDetailRs as $order_details_key => $order_details_value) {
					if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
					}
					else
					{
						if($order_details_value["is_free_gift_products"]=="Yes")
						{
							$prod_res = FreeGiftProduct::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->product_image) && !empty($prod_res->product_image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->product_image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if($prod_res && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						$OrderDetailRs[$order_details_key]['Image'] = $thumb_image;
					}
				}

			}
			if($OrderRs->is_only_gc==1) {
				$GC_Only = 1;
			}
		}
		$this->PageData['GC_Only'] = $GC_Only;

		$this->PageData['OrderDetailRs'] = $OrderDetailRs;

		$view_fileType = "";
		if(Session::get('eusertype') == "Wholesaler" || (Session::get('eusertype')=="Wholesaler" && Session::get('is_dropshipper')=="Yes")) {
			$view_fileType  = 'pdf';
		} else {
			$view_fileType = 'print';
		}
		$this->PageData['view_fileType'] = $view_fileType;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order Detail';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		return view('myaccount.orderdetail')->with($this->PageData);
	}

	public function WishCategory(Request $request)
	{
		if($request['action'] == 'DeleteCat')
		{
			$checked = count($request['ch']);
			$ch = $request['ch'];
			if($checked > 0)
			{
				$result = WishlistCategory::whereIn('wishlist_category_id', $request['ch'])->delete();
				$result = Wishlist::whereIn('wishlist_category_id', $request['ch'])->delete();
				Session::flash('success', config('message.WishCategory.DeleteSuccess'));
			}
			else
			{
				Session::flash('error', config('message.WishCategory.CheckToDelete'));
			}
			return redirect('wish-category.html');
		}

		$WishCatRS = WishlistCategory::select('wishlist_category_id', 'name', 'description')
									->where('customer_id', '=', Auth::user()->customer_id)
									->paginate(10);

		$this->PageData['WishCatRS'] =  $WishCatRS;
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];
		$this->PageData['JSFILES'] = ['wishcategory.js'];
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Wish List';
		return view('myaccount.wishcategory')->with($this->PageData);
	}

	public function WishCategoryEdit(Request $request)
	{	
		$category_id = $request['category_id'];

		if($request['action'] == 'EditCat')	
		{ 	
	        $validatedData = $request->validate([
								'name' => 'required',
								'description'	=> 'required'
					        ], [
					            'name.required' => config('message.WishCategory.Name'),
					            'description.required' => config('message.WishCategory.Description')
					        ]);

			$name_exist = WishlistCategory::where('customer_id', '=', Auth::user()->customer_id)
										->where('wishlist_category_id', '!=', $category_id)
										->where('name', '=', trim($request['name']))
										->count();
			if($name_exist > 0) {
				Session::flash('error', config('message.WishListCategory.ExistCategory'));
				return redirect()->back();
			}

			$WishCatInsertAry =	array( 	
										'name'			=> $request['name'],
										'description'	=> $request['description']
									);

			$updateQry = WishlistCategory::where('wishlist_category_id', '=', $category_id)
										->where('customer_id', '=', Auth::user()->customer_id)
			              				->update($WishCatInsertAry);
		  
			Session::flash('success', config('message.WishCategory.UpdateSuccess'));
			return redirect('wish-category.html');			
		}

		$WishCat = WishlistCategory::select('wishlist_category_id', 'name', 'description')
									->where('customer_id', '=', Auth::user()->customer_id)
									->where('wishlist_category_id', '=', $category_id)
									->first();
		
		$this->PageData['WishCat'] =  $WishCat;

		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['JSFILES'] = ['wishcategory.js'];
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Wish List';
		return view('myaccount.wishcategoryedit')->with($this->PageData);
	}

	public function WishProduct(Request $request)
	{	
		$category_id = $request['category_id'];

		######### Set Wish category id ############
		if($request['category_id'] != '') {
			$wishlist_category_id = (int)$request['category_id'];
			Session::put('Wish_CategoryID', $wishlist_category_id);
		} else {
			$wishlist_category_id = (int)Session::get('Wish_CategoryID');
		}
		$this->PageData['wishlist_category_id'] =  $wishlist_category_id;

		######### Set Wish category id ############

		######### Delete Wish Product ############
		if($request['action'] == 'DeleteWishProd')	
		{
			$checked = count($request['ch']);
			$ch = $request['ch'];
			if($checked > 0)
			{
				$result = Wishlist::whereIn('wishlist_id', $request['ch'])->where('customer_id', '=', Auth::user()->customer_id)->delete();
				Session::flash('success', config('message.WishProduct.DeleteSuccess'));
			}
			else
			{
				Session::flash('error', config('message.WishProduct.CheckToDelete'));
			}
			return redirect()->back();
		}

		######### Delete Wish Product ############

		######## Get Wish Category Start ##########
		$WishCatRS = WishlistCategory::select('name')
									->where('customer_id', '=', Auth::user()->customer_id)
									->where('wishlist_category_id', '=', $wishlist_category_id)
									->first();
		$this->PageData['WishCatRS'] =  $WishCatRS;
		######## Get Wish Category Start ##########

		######## Get Wish Product Start ##########
		$wish_res_prod = Wishlist::select('wishlist_id', 'products_id', 'sku', 'description')
								->where('wishlist_category_id', '=', $wishlist_category_id)
								->where('customer_id', '=', Auth::user()->customer_id)
								->orderBy('wishlist_id', 'ASC')
								->get();

		$arr_products = array();
		if(count($wish_res_prod) > 0) {
			$casewhenprice  = $this->GetSystemProductPrice("`pu_products`");
			foreach ($wish_res_prod as $wish_res_prod_key => $wish_res_prod_value) {
				$sku = $wish_res_prod_value['sku'];
				$products_id = $wish_res_prod_value['products_id'];
				$arr_product = Products::select('pu_products.sku', 'pu_products.products_id', 'pu_products.product_name', 'pu_products.short_description', 'pu_products.image', 'pu_products_category.category_id', 'pu_manufacture.vmanufacture', 'pu_products.minimum_stock', 'pu_products.current_stock', 'pu_products.cosmo_current_stock', 'pu_products.cosmo_sku', 'pu_products.pca_current_stock', 'pu_products.pca_sku', 'pu_products.nandansons_current_stock', 'pu_products.nandansons_sku')
										->addSelect(DB::raw($casewhenprice))
										->join('pu_products_category', 'pu_products_category.products_id', '=', 'pu_products.products_id')
										->join('pu_category', 'pu_category.category_id', '=', 'pu_products_category.category_id')
										->join('pu_manufacture', 'pu_manufacture.imanufactureid', '=', 'pu_products.imanufactureid')
										->where('pu_products.products_id', '=', $products_id)
										->where('pu_products.status', '=', '1')
										->where('pu_category.status', '=', '1')
										->having('product_price', '>', 0)
										->groupBy('pu_products.products_id')
										->first();
				if($arr_product && $arr_product->count() > 0) {
					$file_path = config('global.PRD_MEDIUM_IMG_PATH').stripslashes($arr_product->image);
					if(File::exists($file_path) && $arr_product->image != '') {
						// File::delete($file_path);
						$thumb_image = config('global.PRD_MEDIUM_IMG_URL').rawurlencode(stripslashes($arr_product->image));
					} else {
						$thumb_image = config('global.NO_IMAGE_THUMB');
					}
					$arr_product->thumb_image = $thumb_image;

		 			$arr_product->product_url = $this->getProductRewriteURL($arr_product->products_id, $arr_product->product_name, $arr_product->category_id, $arr_product->vmanufacture);

					$products_sku 	  = $arr_product->sku;
					$product_name 	  = $arr_product->product_name;
					$short_description  = $arr_product->short_description;
					$our_price 		  = $arr_product->product_price;
					$description	= $wish_res_prod_value['description'];

					$arr_products[] = array(
											"wishlist_id"	=> $wish_res_prod_value['wishlist_id'],
											"sku"			=> $products_sku,
											"product_name"	=> $product_name,
											"short_description"	=> $short_description,
											"description"	=> $description,
											'sale_price'	=> $our_price,
											'thumb_image'	=> $arr_product->thumb_image,
											'p_link'		=> $arr_product->product_url
										);

				}

			}
		}

		$this->PageData['WishProdRS'] =  $arr_products;
		######## Get Wish Product End ##########

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Wish Products';
		$this->PageData['CSSFILES'] = ['myaccount.css', 'listing.css'];
		$this->PageData['JSFILES'] = ['wishproduct.js'];
		return view('myaccount.wishproduct')->with($this->PageData);
	}

	public function OrderItemReturn(Request $request)
	{
		if($request['action'] == 'return') {
			$ord_data = Order::select('customer_id')
								->where('orders_id', '=', $request['orderno'])
								->first();
			if(empty($ord_data) || $ord_data->customer_id != Auth::user()->customer_id) {
				return redirect()->back()
				->withInput()
				->withErrors([
					'something_went_wrong' => config('message.General.SomethingWentWrong')
				]);
			}
			
			$selected_arr = $request["chk"];
			$reason_return = "";
			$order_detail_id_return = "";
			$quantity_return = "";
			$return_details = "";
			$order_detail_ids = "";
			$order_detail_ids_array = [];
			if(!empty($selected_arr)) {
				foreach($selected_arr as $key_selected => $orders_detail_id) {
					$no = $orders_detail_id;
					if($request['customerReturnReason'.$no]!='Other') {
						$request['otherreason'.$no] = "";
					}
					if($request['otherreason'.$no]!='' && $request['customerReturnReason'.$no]=="Other") {
						$request['customerReturnReason'.$no] = $request['otherreason'.$no];
					}
					$quantity = $request['quantity'.$no];
					
					$reason_return .= trim($request['customerReturnReason'.$no])."##";
					$order_detail_id_return .= $no."##";
					$quantity_return .= $quantity."##";
					
					$return_details .= "<tr>
											<td>".$request['sku'.$no]."</td>
											<td>".trim($request['customerReturnReason'.$no])."</td>
										</tr>";
					$order_detail_ids .= "'".$no."',";	
					$order_detail_ids_array[] = $no;
				}
				/*$insertArr = array(
										"customer_id"  		=> Auth::user()->customer_id,
										"orders_id"	   		=> $request["orderno"],
										"reason"	   		=> rtrim($reason_return,"##"),
										"is_rma_scan"  		=> "No",
										"status"	   		=> 'Return Requested',
										"orders_detail_id"	=> rtrim($order_detail_id_return,"##"),
										"quantity"			=> rtrim($quantity_return,"##")
								  );
				$returnOrderData = ReturnOrders::create($insertArr);*/
				$returnOrderData = new ReturnOrders;
				$returnOrderData->customer_id = Auth::user()->customer_id;
				$returnOrderData->orders_id = $request["orderno"];
				$returnOrderData->reason = rtrim($reason_return,"##");
				$returnOrderData->is_rma_scan = 'No';
				$returnOrderData->status = 'Return Requested';
				$returnOrderData->orders_detail_id = rtrim($order_detail_id_return,"##");
				$returnOrderData->quantity = rtrim($quantity_return,"##");
				$returnOrderData->save();

				$updateQry = Order::where('orders_id', (int)$request["orderno"])
				              		->update(['status' => 'Return Requested']);
          		//dd($updateQry);
				// if($updateQry)
				// {
					$return_info = "&nbsp;(<a href='".config('global.SITE_ADMIN_URL')."index.php?f=viewreturnorder&return_id=".$returnOrderData->return_id."' target='_blank'>Return Info</a>)";
					
					$order_detail_ids = substr($order_detail_ids,0,-1);	
					$updateQry = OrderDetail::whereIn('orders_detail_id', $order_detail_ids_array)
				              			->update(['ostatus' => 'Return Requested']);
				// }
				
				Session::flash('success', config('message.Order.ReturnRequestSubmit'));

				$orderRes = Order::where('orders_id', (int)$request["orderno"])
				              		->select('orders_no', 'bill_email')
				              		->first();
          		if($orderRes  && $orderRes->count() > 0) {
					$mail = GetMailTemplate("ORDER_RETURN_NOTIFICATION");
					$EmailBody = str_replace('{$return_details}',$return_details,$mail[0]->mail_body);
					$EmailBody = str_replace('{$return_info}',$return_info,$EmailBody);
					$EmailBody = str_replace('{$order_no}',$orderRes->order_no,$EmailBody);
					$EmailBody = str_replace('{$SITE_NAME}',config('Settings.SITE_TITLE'),$EmailBody);
					$EmailBody = str_replace('{$Site_URL}',config('global.Site_URL'),$EmailBody);
					$EmailBody = str_replace('{$CONTACT_MAIL}',config('Settings.CONTACT_MAIL'),$EmailBody);
					$freeshippinginfo = '';
					if(config('Settings.FREESHIPPING_VALUE')!="")
					{
						$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
					}
					$EmailBody = str_replace('{$freeshippinginfo}',$freeshippinginfo,$EmailBody);
					$Subject = $mail[0]->subject;
					$To = config('Settings.ADMIN_MAIL');
					$From = config('Settings.CONTACT_MAIL');
					SendMail($Subject,$EmailBody,$To,$From);
          		}
				return redirect()->back();
			} else {
				return redirect()->back()
				->withInput()
				->withErrors([
					'order_not_returned' => config('message.Order.OrderNotReturned')
				]);
			}
		}
		/*$order_status_array = ['Pending','Completed','Return Requested','Return Approved','Return Rejected','Item Received'];
		$prodCntRes = Order::select('pu_orders.orders_id', 'pu_order_detail.orders_detail_id', 'pu_order_detail.product_name', 'pu_order_detail.sku', 'pu_order_detail.price', 'pu_order_detail.ostatus','pu_orders.status', DB::raw('DATE_FORMAT(pu_orders.order_datetime, "%m/%d/%Y %H:%i") as datetime,DATEDIFF(now(), pu_orders.order_datetime) as days'))
							->join('pu_order_detail', 'pu_order_detail.orders_id', '=', 'pu_orders.orders_id')
							->where('pu_orders.customer_id', '=', Auth::user()->customer_id)
							->whereIn('pu_orders.status', '=', $order_status_array)
							->where('pu_orders.ship_status', '=', 'Shipped')
							->whereRaw("(pu_order_detail.is_free_gift_products='No' || pu_order_detail.is_free_gift_products='')")
							->having('days', '<=', 30)
							->orderBy('pu_orders.order_datetime', 'DESC')
							->orderBy('pu_orders.orders_id', 'DESC')
							->get();
		$total_count = count($prodCntRes);*/

		$order_status_array = ['Completed','Return Requested','Return Approved','Return Rejected','Item Received'];
		$OrdResult = Order::select('pu_orders.orders_id', 'pu_orders.orders_no', 'pu_orders.payment_type', 'pu_orders.use_credit_limit', 'pu_orders.payment_method', 'pu_orders.sub_total', 'pu_orders.order_total', 'pu_orders.status', DB::raw('DATE_FORMAT(pu_orders.order_datetime, "%m/%d/%Y") as datetime,DATEDIFF(now(), pu_orders.order_datetime) as days'))
							->where('pu_orders.customer_id', '=', Auth::user()->customer_id)
							->whereIn('pu_orders.status', $order_status_array)
							->where('pu_orders.ship_status', '=', 'Shipped')
							->having('days', '<=', 30)
							->orderBy('pu_orders.order_datetime', 'DESC')
							->orderBy('pu_orders.orders_id', 'DESC')
							->paginate(50);
		// dd($OrdResult);
		$Data_Return = [];
		if(count($OrdResult) > 0) {
			foreach ($OrdResult as $order_result_key => $order_result_value) {
				$order_detail_status_array = ['Pending','Completed','Return Requested','Return Approved','Return Rejected','Item Received'];
				$OrdDetailResult = OrderDetail::select('pu_order_detail.orders_detail_id', 'pu_order_detail.product_name', 'pu_order_detail.sku', 'pu_order_detail.price', 'pu_order_detail.ostatus', 'pu_order_detail.quantity', 'pu_order_detail.total')
									->where('pu_order_detail.orders_id', $order_result_value['orders_id'])
									->whereIn('pu_order_detail.ostatus', $order_detail_status_array)
									->whereRaw("(pu_order_detail.is_free_gift_products='No' || pu_order_detail.is_free_gift_products='')")
									->orderBy('pu_order_detail.orders_detail_id', 'DESC')
									->get();

				if(count($OrdDetailResult) > 0) {
					foreach ($OrdDetailResult as $order_details_key => $order_details_value) {
						if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
						}
						else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
						{
							$this->GetGiftCertificateData($OrdDetailResult, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
						}
						else
						{
							$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->select('image')->first();
							if($prod_res  && $prod_res->count() > 0) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
							$OrdDetailResult[$order_details_key]['Image'] = $thumb_image;
						}

						$return_res = ReturnOrders::select('reason', 'quantity', 'orders_detail_id', 'return_shipping_label')
													->where('orders_id', '=', $order_result_value['orders_id'])
													->get();
						$tot_reqs = count($return_res);
						if($tot_reqs > 0)
						{
							for($d=0; $d < $tot_reqs; $d++){
								$reason_arr = explode("##",$return_res[$d]["reason"]);
								$qty_arr = explode("##",$return_res[$d]["quantity"]);
								$order_detail_arr = explode("##",$return_res[$d]["orders_detail_id"]);
								foreach($order_detail_arr as $key_ids => $detail_ids){
									$Return_data[$detail_ids]['reason'] = $reason_arr[$key_ids];
									$Return_data[$detail_ids]['qty'] = $qty_arr[$key_ids];
									
									$Return_data[$detail_ids]['base_orderdetailid'] = $order_detail_arr[0];
									$Return_data[$detail_ids]['returnlabel'] = $return_res[$d]["return_shipping_label"];
								}
							}	
						}
						$OrdDetailResult[$order_details_key]["Reason"]  = '';
						$OrdDetailResult[$order_details_key]["Qty"]  = 0;

						$OrdDetailResult[$order_details_key]["return_shipping_label"]  = '';

						if(!empty($Return_data[$order_details_value["orders_detail_id"]])){
							$OrdDetailResult[$order_details_key]["Qty"] = $Return_data[$order_details_value["orders_detail_id"]]['qty'];
							$OrdDetailResult[$order_details_key]["Reason"] = $Return_data[$order_details_value["orders_detail_id"]]['reason'];

							$OrdDetailResult[$order_details_key]["COrderNo"] = "Return-Authorization-Order-".$order_result_value["orders_no"]."-".$Return_data[$order_details_value["orders_detail_id"]]['base_orderdetailid'].".pdf";

							$ShippingLabel = '';
							
							$returnlabel = $Return_data[$order_details_value["orders_detail_id"]]['returnlabel'];
							if($returnlabel != "" && file_exists(config('global.RETURN_PDF_PATH').$returnlabel))
							{
								$ShippingLabel = '<a href="'.url('/returnord/'.$Return_data[$order_details_value["orders_detail_id"]]['returnlabel']).'" target="_blank" class="button btn-1 btn-small" style="padding-left:8px;">Print Shipping Label</a>';
								$OrdDetailResult[$order_details_key]["return_shipping_label"] =$ShippingLabel;
							}
							else
							{
								$OrdDetailResult[$order_details_key]["return_shipping_label"]  = '';
							}

						}

						if($order_result_value["ostatus"]!="Pending" && $order_result_value["ostatus"]!="Completed")
						{
							$OrdDetailResult[$order_details_key]["TotalPriceVal"] = $OrdDetailResult[$order_details_key]["Qty"] * $order_result_value["price"];
							$OrdDetailResult[$order_details_key]["TotalPriceVal"] = number_format($OrdDetailResult[$order_details_key]["TotalPriceVal"],2,'.','');

						}

						if($tot_reqs == 1 && !empty($Return_data[$order_result_value["orders_detail_id"]])){
							$Data_Return[$order_result_key]['COrderNo'] = $OrdDetailResult[$order_details_key]["COrderNo"];
							$Data_Return[$order_result_key]['return_shipping_label'] = $OrdDetailResult[$order_details_key]["return_shipping_label"];
						}

					}
				}

				$OrdResult[$order_result_key]['order_items'] = $OrdDetailResult;
			}
		}
		// $this->PageData['OrdResult'] =  $Data_order;
		$this->PageData['OrdResult'] =  $OrdResult;
		$this->PageData['Data_Return'] =  $Data_Return;
		$this->PageData['JSFILES'] = ['orderitemreturn.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css', 'orderitemreturn.css', 'pagination.css'];
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order Return';
		return view('myaccount.orderitemreturn')->with($this->PageData);
	}

	public function WholeSaleRegister(Request $request)
	{	
		$this->PageData['Countries'] = GetCountries();
		$this->PageData['States'] = GetStates();
		$this->PageData['SelCountry'] = 'US';
		
		if(isset($request['action']) && $request['action'] == 'signup')
		{
			$this->PageData['SelCountry'] = $request['country'];

	        $validatedData = $request->validate([
								'company_name' => 'required',
								'first_name' => 'required',
								'last_name'	=> 'required',
								'address1' => 'required',
								'city' => 'required',
								'country' => 'required',
								'zip' => 'required',
								'phone' => 'required',
					            'email' => 'required|email',
					            'password' => 'required|same:confirm_password|min:6',
					            'confirm_password' => 'required|min:6',
								'hear_about' => 'required',
					            'youtube' => 'required_if:hear_about,Youtube',
					            'other' => 'required_if:hear_about,Other',
					            'state' => 'required_if:country,US',
					            'other_state' => 'required_unless:country,US',
					            'einnumber' => 'required_if:state,NY',
					            'salestax_id' => 'required_if:state,NY',
					        ], [
					            'company_name.required' => config('message.Register.CompanyName'),
					            'first_name.required' => config('message.Validate.FirstName'),
					            'last_name.required' => config('message.Validate.LastName'),
					            'address1.required' => config('message.Validate.Address'),
					            'city.required' => config('message.Validate.City'),
					            'country.required' => config('message.Validate.Country'),
					            'zip.required' => config('message.Validate.ZipCode'),
					            'phone.required' => config('message.Validate.Phone'),
					            'email.required' => config('message.Validate.ValidEmail'),
					            'email.email' => config('message.Validate.ValidEmail'),
					            'password.required' => config('message.Validate.WholeSalerPassword'),
					            'password.same' => config('message.Validate.WholeSalerPassword'),
					            'password.min' => config('message.Validate.MinLengthPassword'),
					            'confirm_password.required' => config('message.Validate.WholeSalerValidConfirmPassword'),
					            'confirm_password.min' => config('message.Validate.MinLengthConfirmPassword'),
					            'hear_about.required' => config('message.Register.HearAbout'),
					            'youtube.required_if' => config('message.Register.Youtube'),
					            'other.required_if' => config('message.Register.OtherText'),
					            'state.required_if' => config('message.Validate.State'),
					            'other_state.required_unless' => config('message.Validate.OtherState'),
					            'einnumber.required_if' => config('message.Register.EINnumber'),
					            'salestax_id.required_if' => config('message.Register.SalesTaxID'),
					        ]);

			$ChkEmail = Customer::where('email','=',$request['email'])->where('registration_type','=','M')->get();
			if($ChkEmail && $ChkEmail->count() > 0)
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'existing_email' => config('message.Register.ExistingEmail'),
				]);
			}
			$ChkIP = Customer::where('customer_ip','=',$_SERVER['REMOTE_ADDR'])->where('registration_type','=','M')->get();
			if($ChkIP && $ChkIP->count() >= 5)
			{
				return redirect()->back()
				->withInput()
				->withErrors([
					'duplicate_ip' => config('message.Register.DuplicateIP'),
				]);
			}
			$State = $request['state'];
			if($request['country'] != 'US')
				$State = $request['other_state'];
			$UserData = array(
				'company_name' => $request['company_name'],
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '') ? $request['address2'] : '',
				'phone' => $request['phone'],
				'email' => $request['email'],
				'password' => $request['password'],
				'city' => $request['city'],
				'state' => $State,
				'country' => $request['country'],
				'zip' => $request['zip'],
				'status' => '1',
				'eusertype' => 'Wholesaler Pending', // Whole sale or normal customer
				'registration_type' => 'M', // member or guest customer
				'customer_ip' => $_SERVER['REMOTE_ADDR'],
				'customer_browser' => $_SERVER['HTTP_USER_AGENT'],
				'hear_about' => $request['hear_about'],
				'is_dropshipper' => $request['is_dropshipper'],
				'iRewardpoint' => '0',
				'upd_datetime' => date('Y-m-d H:i:s'),
				'merge_log' => 'Auto updated from guest to member on registration page',
				
			);
			
			$UserData['hear_text']  = ($request["hear_about"] == 'Other') ? $request["other"] : '';
			$UserData['einnumber']  = ($request["state"] == 'NY') ? $request["einnumber"] : '';
			$UserData['youtube_text']  = ($request["hear_about"] == 'Youtube') ? $request["youtube"] : '';

			$UserData['company_website']  = ($request["company_website"] != '') ? $request["company_website"] : '';
			$UserData['stores']  = ($request["stores"] != '') ? $request["stores"] : '';
			$UserData['sell_online']  = $request["sell_online"];
			$UserData['website']  = $request["website"];
			$UserData['webiste_url']  = ($request["webiste_url"] != '') ? $request["webiste_url"] : '';
			$UserData['policy']  = ($request["policy"] != '') ? $request["policy"] : '';
			$UserData['knowledge']  = ($request["knowledge"] != '') ? $request["knowledge"] : '';
			$UserData['salestax_id']  = ($request["country"] == 'US' && $request["state"] == 'NY') ? $request["salestax_id"] : '';

			$User = Customer::create($UserData);
			if($User)
			{
				$iCustomerId = $User->customer_id;
				$AddressBook=array();
				$AddressBook['customer_id'] = $iCustomerId;
				$AddressBook['title'] = 'Self Address';
				$AddressBook['email'] = $User->email;
				$AddressBook['first_name'] = $User->first_name;
				$AddressBook['last_name'] = $User->last_name;
				$AddressBook['address1'] = $User->address1;
				$AddressBook['address2'] = $User->address2;
				$AddressBook['company_name'] = $User->company_name;
				$AddressBook['city'] = $User->city;
				$AddressBook['state'] = $User->state;
				$AddressBook['country'] = $User->country;
				$AddressBook['zip'] = $User->zip;
				$AddressBook['phone'] = $User->phone;
				$AddressBook['status'] = '1';
				$UserAddress = AddressBook::create($AddressBook);
				
				if($request->hasFile('upload_sales_tax')){
					// $path_large = config('global.HOME_IMAGE_PATH');
			        $image = $request->file('upload_sales_tax');
			        $image_name = $image->getClientOriginalName();
			        $destinationPath = base_path('images/homeimg/');
			        $upload = $image->move($destinationPath, $image_name);

					$result = Customer::where('customer_id', $iCustomerId)
					              		->update(['upload_sales_tax' => $image_name]);

				}
				// $request->session()->regenerate();
				Session::put('sess_useremail',$User->email);
				Session::put('sess_username',$User->first_name);
				Session::put('sess_icustomerid',$User->customer_id);
				Session::put('eusertype',$User->eusertype);
				Session::put('is_dropshipper',$User->is_dropshipper);
				Session::put('etype','M');
				Session::put('eusertype','Retailer');
				
				Session::flash('success', config('message.Register.Success'));
				

				$mail = GetMailTemplate("WHOLESALER");
				$EmailBody = str_replace('{$customer_id}',$iCustomerId,$mail[0]->mail_body);
				$EmailBody = str_replace('{$vemail}',$request->email,$EmailBody);
				$EmailBody = str_replace('{$password}',$request->password,$EmailBody);
				$EmailBody = str_replace('{$vFirstName}',$request->first_name,$EmailBody);
				$EmailBody = str_replace('{$vLastName}',$request->last_name,$EmailBody);
				$EmailBody = str_replace('{$SITE_NAME}',config('Settings.SITE_TITLE'),$EmailBody);
				$EmailBody = str_replace('{$TOLL_FREE_NO}',config('Settings.CONTACT_PHONE_NO'),$EmailBody);
				$EmailBody = str_replace('{$Site_URL}',config('global.Site_URL'),$EmailBody);

				$freeshippinginfo = '';
				if(config('Settings.FREESHIPPING_VALUE')!="")
				{
					$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
				}
				$EmailBody = str_replace('{$freeshippinginfo}',$freeshippinginfo,$EmailBody);

				/* First Mail */
				$To1 = $request->email;
				$Subject = $mail[0]->subject;
				$From1 = config('Settings.ADMIN_MAIL');
				SendMail($Subject,$EmailBody,$To1,$From1);

				/* Second Mail */
				$To2 = config('Settings.ADMIN_MAIL');
				$From2 = config('Settings.CONTACT_MAIL');
				SendMail($Subject,$EmailBody,$To2,$From2);

				if($request['is_dropshipper']=="Yes")
				{
					$dropshipmail = GetMailTemplate("DROPSHIPPER_CUSTOMER");
					$dropShipMailBody = str_replace('{$customer_id}',$iCustomerId,$dropshipmail[0]->mail_body);
					$dropShipMailBody = str_replace('{$vemail}',$request->email,$dropShipMailBody);
					$dropShipMailBody = str_replace('{$password}',$request->password,$dropShipMailBody);
					$dropShipMailBody = str_replace('{$vFirstName}',$request->first_name,$dropShipMailBody);
					$dropShipMailBody = str_replace('{$vLastName}',$request->last_name,$dropShipMailBody);
					$dropShipMailBody = str_replace('{$SITE_NAME}',config('Settings.SITE_TITLE'),$dropShipMailBody);
					$dropShipMailBody = str_replace('{$TOLL_FREE_NO}',config('Settings.CONTACT_PHONE_NO'),$dropShipMailBody);
					$dropShipMailBody = str_replace('{$Site_URL}',config('global.Site_URL'),$dropShipMailBody);

					$freeshippinginfo = '';
					if(config('Settings.FREESHIPPING_VALUE')!="")
					{
						$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
					}
					$dropShipMailBody = str_replace('{$freeshippinginfo}',$freeshippinginfo,$dropShipMailBody);

					$Subject = $dropshopmail[0]->subject;
					$To3 = config('Settings.ADMIN_MAIL');
					$From3 = config('Settings.CONTACT_MAIL');
					SendMail($Subject,$dropShipMailBody,$To3,$From3);
				}

				return redirect()->back();
			} else {
				Session::flash('failed', config('message.Register.Failed'));
				return redirect()->back();
			}
		}
		$GTMDATA = ['page' => 'wholesaleregister', 'pagetype' => 'other'];
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Registration';
		$this->PageData['JSFILES'] = ['register.js', 'wholesaleregister.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css','register.css'];	
		return view('customer.wholesaleregister')->with($this->PageData);
	}
	
	public function CreditSummary(Request $request)
	{
		$result1 = Order::select(DB::raw("CONCAT('Used in Order# ',`orders_no`) as orders_no, apply_credit, remaining_credit, DATE_FORMAT(order_datetime,'%Y-%m-%d %h:%i:%s') AS date_time, '' as oldnew, '' as oldnew1, '--' as note"), 'cust_current_credit_limit as cust_current_credit_limit')
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('apply_credit', '>', 0);

		$credit_customer = AdminCreditLog::select(DB::raw("CONCAT('Added by Maxaroma') as orders_no, '' as apply_credit, '' as remaining_credit, DATE_FORMAT(date,'%Y-%m-%d %h:%i:%s') AS date_time, old_amount as oldnew, new_amount as oldnew1, note"), 'credited_amount as cust_current_credit_limit' )
							->where('customer_id', '=', Auth::user()->customer_id)
							->orderBy('date_time', 'DESC')
							->union($result1)
							->paginate(10);

		$this->PageData['credit_customer'] = $credit_customer;

		$credit_limit = Customer::select('credit_limit')->where('customer_id', '=', Auth::user()->customer_id)->first();
		if($credit_limit['credit_limit'] == null) {
			$credit_limit['credit_limit'] = 0;
		}
		$this->PageData['credit_limit'] = $credit_limit['credit_limit'];
		
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: New Added Credit Limit Report';	
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];	
		return view('myaccount.creditsummary')->with($this->PageData);
	}

	public function DropshipperFundSummary(Request $request)
	{
		if(Session::get('eusertype') != 'Wholesaler' || Session::get('is_dropshipper') != 'Yes' || config('Settings.DROPSHIPPER_SYSTEM_FLAG') != 'Yes')
			return redirect('/login.html');

		$result1 = Order::select(DB::raw("CONCAT('Used in Order# ',pu_orders.orders_id) as orders_no,DATE_FORMAT(pu_orders.order_datetime,'%Y-%m-%d %h:%i:%s') as date_time,CONCAT('$-',pu_orders.order_total) as added_fund,'order' as oldnew,'--' as note"))
							->join('pu_customer', 'pu_customer.customer_id', '=', 'pu_orders.customer_id')
							->where('pu_orders.customer_id', '=', Auth::user()->customer_id)
							->where('pu_orders.payment_method', '=', 'Dropshipper Fund')
							->where('pu_customer.eusertype', '=', 'Wholesaler')
							->where('pu_customer.registration_type', '=', 'M');

		$result2 = AdminFundLog::select(DB::raw("'Added by Maxaroma' as orders_no, DATE_FORMAT(pu_admin_fund_log.date,'%Y-%m-%d %h:%i:%s') as date_time,CONCAT('$',pu_admin_fund_log.funded_amount) as added_fund, CONCAT('$',pu_admin_fund_log.old_fund_value,' | $',pu_admin_fund_log.new_fund_value) as oldnew,pu_admin_fund_log.note"))
							->join('pu_admin', 'pu_admin.admin_id', '=', 'pu_admin_fund_log.admin_id')
							->where('pu_admin_fund_log.customer_id', '=', Auth::user()->customer_id);

		$result3 = PaypalIpnLog::select(DB::raw("'Added by You' as orders_no, DATE_FORMAT(pu_paypal_ipn_log.order_date,'%Y-%m-%d %h:%i:%s') as date_time,CONCAT('$',pu_paypal_ipn_log.cust_requested_fund) as added_fund, '--' as oldnew,'--' as note"))
							->join('pu_customer', 'pu_customer.customer_id', '=', 'pu_paypal_ipn_log.customer_id')
							->where('pu_paypal_ipn_log.customer_id', '=', Auth::user()->customer_id);

		$result4 = AuthorizeFundLog::select(DB::raw("'Added by You' as orders_no, DATE_FORMAT(pu_authorize_fund_logs.order_date,'%Y-%m-%d %h:%i:%s') as date_time,CONCAT('$',pu_authorize_fund_logs.fund_amount) as added_fund,'--' as oldnew,'--' as note"))
							->join('pu_customer', 'pu_customer.customer_id', '=', 'pu_authorize_fund_logs.customer_id')
							->where('pu_authorize_fund_logs.customer_id', '=', Auth::user()->customer_id)
							->where('pu_authorize_fund_logs.status', '!=', 'Sent To Stripe');

		$DropShipper_Customer = AmazonFundLog::select(DB::raw("'Added by You' as orders_no, DATE_FORMAT(pu_amazon_fund_logs.order_date,'%Y-%m-%d %h:%i:%s') as date_time,CONCAT('$',pu_amazon_fund_logs.fund_amount) as added_fund,'--' as oldnew,'--' as note"))
							->join('pu_customer', 'pu_customer.customer_id', '=', 'pu_amazon_fund_logs.customer_id')
							->where('pu_amazon_fund_logs.customer_id', '=', Auth::user()->customer_id)
							->where('pu_amazon_fund_logs.status', '!=', 'Declined')
							->orderBy('date_time', 'DESC')
							->union($result1)
							->union($result2)
							->union($result3)
							->union($result4)
							->paginate(10);
		// dd($DropShipper_Customer);
		$total_records = count($DropShipper_Customer);
		if($total_records > 0) {
			for($i=0;$i<$total_records;$i++) {
				if($DropShipper_Customer[$i]['added_fund'] != '') {
					$oldamt = $newamt = $totamt = 0;
					if($DropShipper_Customer[$i]['oldnew']!='--' && $DropShipper_Customer[$i]['oldnew']!='order') {
						$a = explode("|", $DropShipper_Customer[$i-1]['oldnew']);
						$newamt = str_replace(" ","",$a[1]);
						$newamt = str_replace("$","",$a[1]);
						$newamt = abs($newamt);
					}
					if($newamt=='') {
						$newamt = 0;
						$newamt = abs($newamt);
					}
					$fund = str_replace("$","",$DropShipper_Customer[$i]['added_fund']);
					$fund = abs($fund);
					if($DropShipper_Customer[$i]['oldnew']=='--') {
						$totamt = abs($newamt + $fund);
						$valueToAssign = "$".str_replace(" ","",$newamt)." | $".$totamt;
						$valueToAssign1 = "$".$totamt;
						$DropShipper_Customer[$i]['oldnew'] = $valueToAssign;
						$DropShipper_Customer[$i]['oldnew1'] = $valueToAssign1;
					} elseif($DropShipper_Customer[$i]['oldnew']=='order') {
						$totamt = abs($newamt - $fund);
								if($totamt==$fund) {
									$valueToAssign = "$0 | $0";
									$valueToAssign1 = "$0";
								} else {
									$valueToAssign = "$".str_replace(" ","",$newamt)." | $".$totamt;
									$valueToAssign1 = "$".$totamt;
								}
						//$valueToAssign = "$".str_replace(" ","",$newamt)." | $".$totamt;
						$DropShipper_Customer[$i]['oldnew'] = $valueToAssign;
						$DropShipper_Customer[$i]['oldnew1'] = $valueToAssign1;
					} else {
						$a1 = explode("|", $DropShipper_Customer[$i]['oldnew']);
						$newamt11 = $a1[1];
						$valueToAssign1 = $newamt11;
								
						$DropShipper_Customer[$i]['oldnew1'] = $valueToAssign1;
					}
				}
			}
		}
		$this->PageData['DropShipper_Customer'] = $DropShipper_Customer;

		$fund = Customer::select('pu_customer.dropshipperfund_history', 'pu_customer.available_funds')
							// ->join('pu_orders', 'pu_orders.customer_id', '=', 'pu_customer.customer_id')
							->where('pu_customer.is_dropshipper', '=', 'Yes')
							->where('pu_customer.customer_id', '=', Auth::user()->customer_id)
							->where('pu_customer.eusertype', '=', 'Wholesaler')
							->where('pu_customer.registration_type', '=', 'M')
							->first();
		if(!is_null($fund)) {
			$history = preg_replace('#Updated By.*?On#si', 'updated by Maxaroma on', $fund->dropshipperfund_history);
		} else {
			$history = '';
		}
		$this->SetAmazonConfig('fund');
		$this->PageData['available_funds'] = (!is_null($fund)) ? $fund->available_funds : '';
		$this->PageData['history'] = $history;
		$this->PageData['eusertype'] = strtolower(Session::get('eusertype'));
		/*$this->PageData['isExport'] = $request->isExport;
		$export_file_name = "fund_history.csv";
		$export_file_url = config('global.DOWNLOAD_CSV_URL').$export_file_name."?ver".time();
		$this->PageData['export_file_url'] = $export_file_url;
		$this->PageData['export_file_path'] = $request->isExport;*/
		// dd($DropShipper_Customer);
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Dropshipper Fund Report';	
		$this->PageData['CSSFILES'] = ['myaccount.css', 'pagination.css'];	
		return view('myaccount.dropshipperfundsummary')->with($this->PageData);
	}

	public function EditFtp(Request $request)
	{
		if(Session::get('eusertype') != 'Wholesaler' || Session::get('is_dropshipper') != 'Yes' || config('Settings.DROPSHIPPER_SYSTEM_FLAG') != 'Yes')
			return redirect('/login.html');

		$aCustomer = Customer::select('dropshipper_ftp_type', 'dropshipper_ftp_host', 'dropshipper_ftp_username', 'dropshipper_ftp_password', 'dropshipper_ftp_port', 'dropshipper_ftp_file_path', 'dropshipper_ftp_file_name', 'dropshipper_ftp_picktime_order', 'dropshipper_ftp_flag', 'dropshipper_ftp_timestamp')
									->where('customer_id', '=', Auth::user()->customer_id)
									->where('registration_type', '=', 'M')
									->where('eusertype', '=', Session::get('eusertype'))
									->where('is_dropshipper', '=', Session::get('is_dropshipper'))
									->where('status', '=', '1')
									->first();

		$this->PageData['aCustomer'] = $aCustomer;
		$this->PageData['eusertype'] = Session::get('eusertype');


		if($request['action'] == 'update') {

	        $validatedData = $request->validate([
								'dropshipper_ftp_host' => 'required',
								'dropshipper_ftp_username'	=> 'required',
								'dropshipper_ftp_password' => 'required',
								'dropshipper_ftp_port' => 'required',
								'dropshipper_ftp_file_path' => 'required',
								'dropshipper_ftp_file_name' => 'required'
					        ], [
					            'dropshipper_ftp_host.required' => config('message.FTP.Host'),
					            'dropshipper_ftp_username.required' => config('message.FTP.Username'),
					            'dropshipper_ftp_password.required' => config('message.FTP.Password'),
					            'dropshipper_ftp_port.required' => config('message.FTP.Port'),
					            'dropshipper_ftp_file_path.required' => config('message.FTP.Path'),
					            'dropshipper_ftp_file_name.required' => config('message.FTP.NamePrefix')
					        ]);

			$custDataAry = array(
								'dropshipper_ftp_type'  		=> $request['dropshipper_ftp_type'],
								'dropshipper_ftp_host'    		=> $request['dropshipper_ftp_host'],
								'dropshipper_ftp_username'     	=> $request['dropshipper_ftp_username'],
								'dropshipper_ftp_password'     	=> $request['dropshipper_ftp_password'],
								'dropshipper_ftp_port' 			=> $request['dropshipper_ftp_port'],
								'dropshipper_ftp_file_path' 	=> $request['dropshipper_ftp_file_path'],
								'dropshipper_ftp_file_name' 	=> $request['dropshipper_ftp_file_name'],
								'dropshipper_ftp_picktime_order'=> $request['dropshipper_ftp_picktime_order'],
								'dropshipper_ftp_flag' 		   	=> $request['dropshipper_ftp_flag'],
								'dropshipper_ftp_timestamp' 	=> date('Y-m-d H:i:s')
								);

			$EditFTP = Customer::find(Auth::user()->customer_id);
			$EditFTP->update($custDataAry);
			if($EditFTP) {
				Session::flash('success', config('message.FTP.Success'));
			} else {
				Session::flash('error', config('message.FTP.Failed'));
			}
				return redirect()->back();
		}

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: FTP Details';
		$this->PageData['JSFILES'] = ['editftp.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('myaccount.editftp')->with($this->PageData);
	}

	public function testFunction(Request $request)
	{
		if(is_null($request->args)) {
			dd('You are in testFunction. There are no args available in the current URL.');
		} else {
		   	$args = explode('/', $request->args);
			dd($args);
		}
	}

	public function ImportOrder(Request $request)
	{
		$OutOfStockMsg = '';
		$HalfOutOfStockMsg = '';
		if(Session::get('eusertype')=='Wholesaler' && Session::get('is_dropshipper')=='Yes' && config('Settings.DROPSHIPPER_SYSTEM_FLAG')=='Yes') {
			if(Session::has('aOutOfStockItems') && count(Session::get('aOutOfStockItems')) > 0) {
				$skuval = implode("','", Session::get('aOutOfStockItems'));
				$OutOfStockMsg = "Following products sku(s) are out of stock<br/>".$skuval;
				Session::forget('aOutOfStockItems');
				Session::forget('aOutOfStockItemsfull');
			}
			if(Session::has('HalfaOutOfStockItems') && count(Session::get('HalfaOutOfStockItems')) > 0) {
				$skuval = implode("','", Session::get('HalfaOutOfStockItems'));
				$HalfOutOfStockMsg = "Following products sku(s) have some product stock<br/>".$skuval;
				Session::forget('HalfaOutOfStockItems');
			}

		}

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Import Order';	
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['JSFILES'] = ['additional-methods.min.js', 'importorder.js'];	
		return view('myaccount.importorder')->with($this->PageData);
	}

	public function ImportedOrderList(Request $request)
	{
		if(Session::has('fund_from')) {
		    Session::forget('fund_from');
		}
		if(Session::has('fund_flag')) {
		    Session::forget('fund_flag');
		}
		$this->PageData['PaymentCheck'] = 'production';

		$OutOfStockMsg = '';
		$OrderProcessMsg = '';
		if(Session::get('eusertype')=='Wholesaler' && Session::get('is_dropshipper')=='Yes' && config('Settings.DROPSHIPPER_SYSTEM_FLAG')=='Yes') {
			## Pay With Amazon ##
			$this->PageData['AMAZON_ORDER_REFERENCE_ID'] = Session::get('AMAZON_ORDER_REFERENCE_ID');
			$this->PageData['is_dropshipper'] = Session::get('is_dropshipper');

			$OrdResult = DropshipperOrder::select('orders_id', 'orders_no', 'customer_id', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y' ) as order_date,DATE_FORMAT(order_datetime, '%H:%i:%s' ) as order_time"), 'sub_total', 'shipping_amt', 'tax' , 'order_total', 'status', 'bill_first_name', 'bill_last_name', 'ship_phone', 'is_outofstock', 'list_outofstock', 'shippingModeId')
										->where('customer_id', '=', Auth::user()->customer_id)
										->where('is_order_deleted', '=', 'No')
										->orderBy('order_datetime', 'DESC')
										->get();
			$this->PageData['OrdResult'] = $OrdResult;

			if(Session::has('SKULISTARR') && count(Session::get('SKULISTARR')) > 0) {
				$skuval = implode("','", Session::get('SKULISTARR'));
				$OutOfStockMsg = "Following products sku(s) are out of stock or inactive <br/>".$skuval;
				Session::forget('SKULISTARR');
			}
			if(Session::has('OrderNotProcess') && count(Session::get('OrderNotProcess')) > 0) {
				$skuval = implode("','", Session::get('OrderNotProcess'));
				$OrderProcessMsg = "Following order(s) are not processed,shipping method is not selected  <br/>".$skuval;
				Session::forget('OrderNotProcess');
			}

			## Paypal Display Setting
			$paypalEC = PaymentMethod::select('pm_status')
										->where('pm_group_name', '=', 'PAYMENT_PAYPALEC')
										->first();
			if(!empty($paypalEC) && $paypalEC->pm_status == 'Active') {
				$IsPaypalExpressCheckout ='Yes';
			}

			if(Session::has('order_amount')) {
				Session::forget('order_amount');
			}
			if(Session::has('tempShippingAdd1Val')) {
				Session::forget('tempShippingAdd1Val');
			}
			if(Session::has('tempBillingAdd1Val')) {
				Session::forget('tempBillingAdd1Val');
			}
			if(Session::has('UpdateOrderNoArr')) {
				Session::forget('UpdateOrderNoArr');
			}
			if(Session::has('UpdateOrderNumber')) {
				Session::forget('UpdateOrderNumber');
			}
		}

		$this->PageData['OutOfStockMsg'] = $OutOfStockMsg;
		$this->PageData['OrderProcessMsg'] = $OrderProcessMsg;
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Imported Dropship Order List';
		$this->PageData['JSFILES'] = ['importedorderlist.js'];
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('myaccount.importedorderlist')->with($this->PageData);
	}

	public function DeleteImportedOrderList(Request $request)
	{
		if($request->ajax()){
			if(!Auth::user()) {
		        $response['status'] = 401;
		        $response['message'] = "Unauthorized request";
		        return response()->json($response, 400);
			} else {
				if(isset($request->orders_id) && ($request->orders_id) != '') {
					$orders_id = $request["orders_id"];
					$updateAdmin = array(
											'is_order_deleted' => "Yes"
										);

					$updateDropshipperOrder = DropshipperOrder::find($orders_id);
					$updateDropshipperOrder->update($updateAdmin); 
			        $response['status'] = 200;
			        $response['message'] = "Success";
			        return response()->json($response, 200);
				} else {
			        $response['status'] = 400;
			        $response['message'] = "Bad parameters";
			        return response()->json($response, 400);
				}
			}
		} else {
	        $response['status'] = 404;
	        $response['message'] = "Not found";
	        return response()->json($response, 400);
		}
	}

	public function OrderDetailPdf(Request $request)
	{
		$orders_id = (int)$request['id'];

		$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%b %d, %Y') AS datetime"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('orders_id', '=', $orders_id)
							->first();
		$GC_Only = 0;
		if($OrderRs && $OrderRs->count() <= 0) {
			// Session::flash('error', config('message.FTP.Failed'));
			Session::flash('error', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->back();
		} else {
			$this->PageData['OrderRs'] = $OrderRs;
			$OrderDetailRs = OrderDetail::select('pu_order_detail.*', 'pu_products.UPC')
								->join('pu_products', 'pu_products.products_id', '=', 'pu_order_detail.products_id')
								->where('orders_id', '=', $OrderRs->orders_id)
								->orderBy('orders_detail_id', 'DESC')
								->get();
			if(count($OrderDetailRs) > 0) {
				foreach ($OrderDetailRs as $order_details_key => $order_details_value) {
					if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU1'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL1'));
					}
					else if($order_details_value["sku"]==config('global.GIFT_CERTIFICATE_SKU2'))
					{
						$this->GetGiftCertificateData($OrderDetailRs, $order_details_key, $order_details_value['orders_detail_id'], config('global.GC_IMAGE_URL2'));
					}
					else
					{
						if($order_details_value["is_free_gift_products"]=="Yes")
						{
							$prod_res = FreeGiftProduct::select('product_image')->whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if(!empty($prod_res)) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->product_image) && !empty($prod_res->product_image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->product_image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						else
						{
							$prod_res = Products::select('image')->whereRaw("LOWER(TRIM(sku))='".strtolower(trim($order_details_value['sku']))."'")->first();
							if(!empty($prod_res)) {
								if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
									$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
								 else
									$thumb_image = config('global.NO_IMAGE_THUMB');
							} else {
								$thumb_image = config('global.NO_IMAGE_THUMB');
							}
						}
						$OrderDetailRs[$order_details_key]['Image'] = $thumb_image;
					}
				}

			}
			if($OrderRs->is_only_gc==1) {
				$GC_Only = 1;
			}
		}
		/* Logo coding starts here */
		$path = public_path('images/logo.png');
		if(File::exists($path)) {
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
		} else {
			$logo = '';
		}
		/* Logo coding ends here */
		return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('myaccount.orderdetailpdf', compact('OrderDetailRs', 'OrderRs', 'GC_Only', 'logo'))->download('Invoice-'.$OrderRs->orders_no.'.pdf');
	}


	public function ReOrderDetail(Request $request)
	{
		$orders_id = (int)$request['id'];

		$OrderRs = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i %p') AS datetime"))
							->where('customer_id', '=', Auth::user()->customer_id)
							->where('orders_id', '=', $orders_id)
							->first();
		$ProdRS = [];
		if(empty($OrderRs)) {
			Session::flash('error', 'Wrong order selected, please choose a order from list to reorder products.');
			return redirect()->back();
		} else {
			$this->PageData['OrderRs'] = $OrderRs;
			$OrderDetailRs = OrderDetail::select('sku')
								->where('orders_id', '=', $OrderRs->orders_id)
								->where('sku', '!=', 'GIFT-CERTIFICATE')
								->orderBy('orders_detail_id', 'DESC')
								->get();

			if(count($OrderDetailRs) > 0) {
		   
				foreach ($OrderDetailRs as $order_details_key => $order_details_value) {
					$product_item_sku = $order_details_value["sku"];
					if(strtolower(Session::get('eusertype'))=='wholesaler') {
						$fetch = ' IF(pu_products.wholesale_price!=0 ,pu_products.wholesale_price, pu_products.our_price) AS our_price ';
					} else {
						$fetch = ' IF(pu_products.sale_price!=0 AND pu_products.sale_price < pu_products.our_price,pu_products.sale_price,pu_products.our_price) AS our_price '; 
					}

					$prod_info = Products::select('pu_products.sku', 'pu_products.products_id', 'pu_products.product_name', 'pu_products.product_description', 'pu_products.short_description', 'pu_products.sale_price', 'pu_products.retail_price', 'pu_products_category.category_id', 'pu_products.image', 'pu_products.status', 'pu_products.product_type', 'pu_products.minimum_stock', 'pu_products.current_stock')->addSelect(DB::raw($fetch))
											->join('pu_brand', 'pu_brand.brand_id', '=', 'pu_products.brand_id')
											->join('pu_manufacture', 'pu_manufacture.imanufactureid', '=', 'pu_products.imanufactureid')
											->join('pu_products_category', 'pu_products_category.products_id', '=', 'pu_products.products_id')
											->join('pu_category', 'pu_category.category_id', '=', 'pu_products_category.category_id')
											->where('pu_products.status', '=', '1')
											->where('pu_products.sku', '=', $product_item_sku)
											->having('our_price', '>', 0)
											->groupBy('pu_products.products_id')
											->first();
					if($prod_info && $prod_info->count() > 0) {

				   		if($prod_info->minimum_stock > $prod_info->current_stock || $prod_info->current_stock == 0) {
				   			$stock = 'Out';
				   		} else {
				   			$stock = 'In';
				   		}
						$dealofdayRS = Dealofweek::select('pu_dealofweek.product_sku', 'pu_dealofweek.deal_price', 'pu_dealofweek.description')
												->join('pu_dealofweektitle', 'pu_dealofweektitle.did', '=', 'pu_dealofweek.did')
												->join('pu_products', 'pu_products.sku', '=', 'pu_dealofweek.product_sku')
												->where('pu_products.status', '=', '1')
												->where('pu_dealofweek.status', '=', '1')
												->where('pu_dealofweek.start_date', '<=', date('Y-m-d'))
												->where('pu_dealofweek.end_date', '>=', date('Y-m-d'))
												->orderBy('pu_dealofweek.dealofweek_id', 'DESC')
												->first();
						if($dealofdayRS && $dealofdayRS->count() > 0) {
							if(trim($dealofdayRS->product_sku)==trim($prod_info->sku)) {
								if($dealofdayRS->deal_price!='' && $dealofdayRS->deal_price < $prod_info->our_price) {
									$dealprice = number_format($dealofdayRS->deal_price);
									$prod_info->our_price = $dealprice;
									if($dealofdayRS->description!='') {
										$prod_info->short_description =  $dealofdayRS->description;
									}
								}	
							}
						}

						$description = '';
						if(trim($prod_info->product_description)!='')
						{
							$description = "<br><br>".$prod_info->product_description;
						}

						if(File::exists(config('global.PRD_THUMB_IMG_PATH').$prod_info->image) && $prod_info->image != '') {
							$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_info->image;
						} else {
							$thumb_image = config('global.NO_IMAGE_THUMB');
						}

			 			$p_link = $this->getProductRewriteURL($prod_info->products_id, $prod_info->product_name, $prod_info->category_id, $prod_info->sku);


						$ProdRS[] = array(
											"sku"				=> $prod_info->sku,
											"products_id"       => $prod_info->products_id,
											"product_name"	    => $prod_info->product_name,
											"description"	    => $description,
											"short_description" => $prod_info->short_description,
											"quantity"          => 1,
								   			"our_price"	    	=> $prod_info->our_price,
											"thumb_image"	    => $thumb_image,
											"p_link"		    => $p_link,
											"current_stock"     => $stock,
											"product_type"      => $prod_info->product_type,
											
									);
			                
					}

				}

			}
		}
		// dd($ProdRS);
		$this->PageData['ProdRS'] = $ProdRS;

		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Reorder Products';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['JSFILES'] = ['reorderdetail.js'];

		return view('myaccount.reorderdetail')->with($this->PageData);
	}

	public function ExportOrders(Request $request)
	{
		$OutOfStockMsg = '';
		$HalfOutOfStockMsg = '';
		if(Session::get('eusertype')=='Wholesaler' && Session::get('is_dropshipper')=='Yes') {
			$err_msg = $request["err_msg"];
			
			$d_start_date = $request["d_start_date"];
			$d_end_date	= $request["d_end_date"];
			
			if($d_start_date!='')
			{
				$d_start_date = date("Y-m-d",strtotime($d_start_date));
				$d_start_date_disp = date("Y-m-d",strtotime($d_start_date));
			}
			if($d_end_date!='')
			{
				$d_end_date	  = date("Y-m-d",strtotime($d_end_date));
				$d_end_date_disp = date("Y-m-d",strtotime($d_end_date));
			}

			if(!isset($d_start_date) && !isset($d_end_date))
			{
				$d_start_date = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
				$d_end_date   = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));

				$d_start_date_disp = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
				$d_end_date_disp = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));
			}

			$this->PageData['d_start_date_disp'] = $d_start_date_disp;
			$this->PageData['d_end_date_disp'] = $d_end_date_disp;

			$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Download Tracking Numbers';	
			$this->PageData['CSSFILES'] = ['myaccount.css'];
			$this->PageData['JSFILES'] = ['additional-methods.min.js', 'exportorder.js'];	
			return view('myaccount.exportorder')->with($this->PageData);
		} else {
			return redirect('/myaccount.html');
		}
	}

	public function CustomerReviews(Request $request)
	{
		$this->PageData['Customer_Reviews'] = $this->Get_Customer_Reviews($request);
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Customer Reviews';
		$this->PageData['CSSFILES'] = ['pagination.css'];	
		// dd($Customer_Reviews);
		return view('myaccount.customerreviews')->with($this->PageData);
	}
	
	/* Below functions are common functions which are used in above functions */

	public function GetGiftCertificateData($orderArray, $key, $order_detail_id, $image_url)
	{
		if(!Auth::user()){
			$GCRs = GiftCertificate::where('orders_detail_id', '=', $order_detail_id)
								->first();
		}else{
			$GCRs = GiftCertificate::where('orders_detail_id', '=', $order_detail_id)
								->where('customer_id', '=', Auth::user()->customer_id)
								->first();
		}
		if($GCRs && $GCRs->count() > 0) {
			$orderArray[$key]['RecipientName']  	= $GCRs->recipient_name;
			$orderArray[$key]['RecipientEmail'] 	= $GCRs->recipient_email;
			$orderArray[$key]['SenderName']  		= $GCRs->your_name;
			$orderArray[$key]['SenderEmail'] 		= $GCRs->your_email;
			$orderArray[$key]['Image']				= $image_url;
		}
		return $orderArray;
	}

	public function GetSystemProductPrice($aliasname="",$pricename="") 
	{
		if($aliasname=="") {
			$aliasname="p";
		}


		if($pricename=="") {
			$pricename = "AS product_price";
		}

		if($pricename=="blank") {
			$pricename = "";
		}

		if(strtolower(Session::get('eusertype')) == 'wholesaler'  && !strpos($_SERVER['PHP_SELF'],config('global.CONTROL_PANEL_NAME'))) {
			$casewhenprice = " CASE
					 WHEN ".$aliasname.".current_stock > 0 THEN IF(".$aliasname.".wholesale_price > 0 ,".$aliasname.".wholesale_price, ".$aliasname.".our_price)
					 ELSE
						CASE
						WHEN ".$aliasname.".cosmo_sku!='' AND ".$aliasname.".cosmo_current_stock > 0 THEN IF(".$aliasname.".cosmo_wholesale_price > 0 ,".$aliasname.".cosmo_wholesale_price, ".$aliasname.".cosmo_our_price)
						ELSE
								CASE
								WHEN ".$aliasname.".pca_sku!='' AND ".$aliasname.".pca_current_stock > 0 THEN IF(".$aliasname.".pca_wholesale_price > 0 ,".$aliasname.".pca_wholesale_price, ".$aliasname.".pca_our_price)
									ELSE
									CASE
									WHEN ".$aliasname.".nandansons_sku!='' AND ".$aliasname.".nandansons_current_stock > 0 THEN IF(".$aliasname.".nandansons_wholesale_price > 0 ,".$aliasname.".nandansons_wholesale_price, ".$aliasname.".nandansons_our_price)
									ELSE
									IF(".$aliasname.".wholesale_price > 0 ,".$aliasname.".wholesale_price, ".$aliasname.".our_price)
								END
							 END
						   END
						END   ".$pricename."
					";
					if($pricename!='')
					$casewhenprice .= ", '0' AS sale_item";
        } else {
			$casewhenprice = " CASE
					 WHEN ".$aliasname.".current_stock > 0 THEN IF(".$aliasname.".sale_price > 0 AND ".$aliasname.".sale_price < ".$aliasname.".our_price,".$aliasname.".sale_price,".$aliasname.".our_price)
					 ELSE
						CASE
						WHEN ".$aliasname.".cosmo_sku!='' AND ".$aliasname.".cosmo_current_stock > 0 THEN ".$aliasname.".cosmo_our_price
						ELSE
							
							  CASE
							  WHEN ".$aliasname.".pca_sku!='' AND ".$aliasname.".pca_current_stock > 0 THEN ".$aliasname.".pca_our_price
							  ELSE
							  CASE
							  WHEN ".$aliasname.".nandansons_sku!='' AND ".$aliasname.".nandansons_current_stock > 0 THEN ".$aliasname.".nandansons_our_price
							  ELSE
							  IF(".$aliasname.".sale_price > 0 AND ".$aliasname.".sale_price < ".$aliasname.".our_price,".$aliasname.".sale_price,".$aliasname.".our_price)
							 End
						   End
					    END
					END   ".$pricename."
					";

					if($pricename!='')
					$casewhenprice .= ", IF(".$aliasname.".sale_price > 0 ,'1','0') AS sale_item";
        }
        return $casewhenprice;
	}

	public function Get_Customer_Reviews($request)
	{
		$Reviews = ProductsReview::select('products_id', 'star_rate', 'user_review', 'first_name', 'date')
								->where('approved', '=', 'Yes')
								->orderBy('date', 'DESC')
								->paginate(10);
		// dd($Reviews);
		$rcount = count($Reviews);
		$Reviews_res= array();
		if($rcount > 0) {

			for($i=0; $i<$rcount; $i++)
			{
				$Product_res = Products::select('pu_products.sku', 'pu_products.products_id', 'pu_products.product_name', 'pu_products_category.category_id', 'pu_manufacture.vmanufacture', )
										->join('pu_products_category', 'pu_products_category.products_id', '=', 'pu_products.products_id')
										->join('pu_category', 'pu_category.category_id', '=', 'pu_products_category.category_id')
										->join('pu_manufacture', 'pu_manufacture.imanufactureid', '=', 'pu_products.imanufactureid')
										->where('pu_products.products_id', '=', $Reviews[$i]['products_id'])
										->first();

	 			$product_url = $this->getProductRewriteURL($Product_res->products_id, $Product_res->product_name, $Product_res->category_id, $Product_res->vmanufacture, $Product_res->sku);

				$Reviews[$i]['product_name'] = $Product_res->product_name;
				$Reviews[$i]['product_star'] = $Reviews[$i]['star_rate'];
				$Reviews[$i]['user_review'] = $Reviews[$i]['user_review'];
				$Reviews[$i]['product_url'] = $product_url;
				$Reviews[$i]['first_name'] = $Reviews[$i]['first_name'];
				$Reviews[$i]['date'] = date("m/d/Y", strtotime($Reviews[$i]['date']));
			}
		}
		return $Reviews;
	}
	public function OrderTracking(Request $request)
	{
		$this->PageData['tracking_no'] = $request['id'];
		return view('myaccount.tracking')->with($this->PageData); 
	}


}
?>
