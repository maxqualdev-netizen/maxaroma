<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\StaticPages;
use App\Models\FAQS;
use App\Models\Currency;
use App\Models\MetaInfo;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\GiftCertificate;
use App\Models\Products;
use App\Models\CustRequest;
use App\Models\Customer;
use App\Models\Claim;

use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
	
use DB;
use Session;
use Cache;
use Illuminate\Support\Facades\Auth;

class StaticpageController extends Controller
{
	use CommonTrait;
	use VendorTrait;
	use CartTrait;
	public $PageData;
	
	public function __construct()
	{
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

	public function YotpoTempReview(Request $request){
		//$this->PageData['JSFILES'] = ['yotpo_reviews.js'];
		//$this->PageData['meta_title'] = "What People Are Saying";
		$this->PageData['PageTitle'] = "Site Reviews";
		$this->PageData['PageTitle_new'] = "What People Are Saying";
		return view('staticpages.temp-site-feedback')->with($this->PageData);	
	}

	public function YotpoReview(Request $request){		
		//$this->PageData['JSFILES'] = ['yotpo_reviews.js'];
		//$this->PageData['meta_title'] = "What People Are Saying";
		$this->PageData['PageTitle'] = "Site Reviews";
		$this->PageData['PageTitle_new'] = "What People Are Saying";		
		return view('staticpages.site-feedback')->with($this->PageData);	
	}
	
	public function show(Request $request)
	{   
		$Page = Route::currentRouteName();
		
		if($Page != '')
		{
            if($Page == 'coupon-code' || $Page == 'coupon_code'){
                return redirect('/coupons-promotional.html', 301);
            }
			if($Page == 'shipping_policy')
			{
				if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
					$Page = 'wholesaler_shipping_policy';
			}
			$PData = StaticPages::where('name','=',$Page)->where('status','=','1')->get();
			$Content = $PData[0]->content;
			$Content = remove_html_entities($Content);
			
			if (str_contains($Content, '{$Site_URL}')) {
				$Content = str_replace('{$Site_URL}',config('global.SITE_URL'),$Content);
				
			}
			
			if($Page == 'site_map')
			{
				$MainCatLink = "";
				$menu_array = Cache::get('menu_array');
				foreach($menu_array as $maincat)
				{
					if($maincat['parent_id'] == 0)
						$MainCatLink.='<li><a href="'.$maincat['menu_link'].'">'.ucwords(strtolower($maincat['menu_title'])).'</a></li>';
				}
				$Content = str_replace('{$site_map_cat_str}',$MainCatLink,$Content);
				//$Content = str_replace('{$Site_URL}',config('global.SITE_URL'),$Content);
			}
			
            $this->PageData['PageTitle'] = $PData[0]->title;
			$this->PageData['PageContent'] = $Content;
			$DefaultMetaPage = ['privacy_policy','terms_and_conditions'];
			if(!in_array($Page,$DefaultMetaPage))
			{
				$this->PageData['meta_title'] = $PData[0]->meta_title;
				$this->PageData['meta_description'] = $PData[0]->meta_description;
				$this->PageData['meta_keywords'] = $PData[0]->meta_keywords;
			}
            /*
            if($Page == 'shipping_service')
            {
                $PData = FAQS::where('status','=','1')->where('type','=','Dropshipper')->orderBy('rank')->get();
                if($PData->count() > 0)
                {
                    foreach($PData as $key => $Data)
                    {
                        $Data->question = ltrim($Data->question,"<p>");
                        $Data->question = rtrim($Data->question,"</p>");
                        $Data->answer = $Data->answer;
                    }
                }
                $this->PageData['PageTitle'] = 'Shipping Service';
                $this->PageData['PData'] = $PData;
                return view('staticpages.shipping_service')->with($this->PageData);	
            }
            */
		}
		return view('staticpages.main')->with($this->PageData);		
	}

	public function BrandPerfume(Request $request)
	{
		$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['JSFILES'] = ['brandlist.js'];
		$PageType = 'DP';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}	
		return view('staticpages.brandperfume')->with($this->PageData);		
	}

	/*public function GiftCertificateOld(Request $request)
	{
		$minimum_giftcertificate_amount = config('Settings.MINIMUM_GIFTCERTIFICATE_AMOUNT');
		$maximum_giftcertificate_amount = config('Settings.MAXIMUM_GIFTCERTIFICATE_AMOUNT');
		
		//echo  $minimum_giftcertificate_amount."".Make_Price($minimum_giftcertificate_amount, false);exit;
		if(isset($request['action']) && $request['action'] == 'add_gift_certificate')
		{
			if($request["customflag"] == "Custom")
			{
				//$request["gc_value"] = $request["cardamount"];
			}
			$currency_code = session('currency_code');
			
			$validatedData = $request->validate([
								'gc_value' => 'required|Numeric|Min:'.$minimum_giftcertificate_amount.'',
								'cardamount'	=> 'exclude_unless:customflag,Custom|required|Numeric|between:'.$minimum_giftcertificate_amount.','.$maximum_giftcertificate_amount.'',
								'recname' => 'required',
								'recemail' => 'required|email',
								'recconfemail' => 'required|email|Same:recemail',
								'yourname' => 'required',
					            'youremail' => 'required|email'
					        ], [
					            'gc_value.required' => config('message.Gift_Certi.GiftAmount'),
					            'gc_value.Numeric' => config('message.Gift_Certi.WrongGiftAmount'),
					            'gc_value.min' => 'Amount must be between '.$this->Make_Price($minimum_giftcertificate_amount, true).' and '.$this->Make_Price($maximum_giftcertificate_amount, true),
					            'cardamount.required_if' => config('message.Gift_Certi.GiftAmount'),
					            'cardamount.Numeric' => config('message.Gift_Certi.WrongGiftAmount'),
					            'cardamount.between' => 'Please enter custom amount between '.$this->Make_Price($minimum_giftcertificate_amount, true).' and '.$this->Make_Price($maximum_giftcertificate_amount, true),
					            'cardamount.required' => config('message.Gift_Certi.GiftAmount'),
					            'recname.required' => config('message.Gift_Certi.RecName'),
					            'recemail.required' => config('message.Gift_Certi.RecEmail'),
					            'recemail.email' => config('message.Gift_Certi.ValidEmail'),
					            'recconfemail.required' => config('message.Gift_Certi.ReRecEmail'),
					            'recconfemail.email' => config('message.Gift_Certi.ValidEmail'),
					            'recconfemail.Same' => config('message.Gift_Certi.ReRecEmail'),
					            'yourname.required' => config('message.Gift_Certi.Name'),
					            'youremail.required' => config('message.Gift_Certi.Email'),
					            'youremail.email' => config('message.Gift_Certi.ValidEmail'),
					        ]);
			
			if($request["gc_value"] > 0 && $currency_code!='USD')
			{
				$res_currency = Currency::where('status','=','1')->where('currency_code','=',$currency_code)->get();
				if($res_currency->count() > 0 )
				{
					$curr_rate = $res_currency[0]->exchange_rate;
					
					$request["gc_value"]	   = $request["gc_value"]/$curr_rate;
					if($request["customflag"] == "Custom")
					{
					  $request["customamount"] = $request["gc_value"];
					}
				}
			}
			$a = $this->insertGiftCertificate($request);
			if($a)
			{
				Session::flash('gift_success','GiftCertificate added successfully.');
			} else {
				Session::flash('gift_error','Error in adding GiftCertificate.');
			}
			return redirect('/gift-certificate.html');
		}
		
		
		if($minimum_giftcertificate_amount<=0)
		{
			$minimum_giftcertificate_amount = 1;
			$minimum_giftcertificate_amount_msg = 1;
		}
		if($minimum_giftcertificate_amount > 0 &&  $maximum_giftcertificate_amount > 0)
		{
			if($minimum_giftcertificate_amount <= $maximum_giftcertificate_amount)
			{
				$tabstr = '';
				
				$min_gc_amt = $minimum_giftcertificate_amount;
				for($i=0;$i<4;$i++)
				{ 
					$class="";
					$priceVal = $this->Make_Price($min_gc_amt, true);
					
					if($i==0)
					{
						$class="class='active'";
					}
					if($min_gc_amt <= $maximum_giftcertificate_amount)
					{
						$tabstr.= '<a href="javascript:void(0);" onclick="GiftChangeAmount('.$this->Make_Price($min_gc_amt,false).',this)" '.$class.'>'.$priceVal.'</a>';
					}
					$min_gc_amt = $min_gc_amt + 10;
				}
				
				if(config('Settings.MINIMUM_GIFTCERTIFICATE_AMOUNT') < config('Settings.MAXIMUM_GIFTCERTIFICATE_AMOUNT'))
				{
					$tabstr.=  '<a href="javascript:void(0);" onclick=GiftChangeAmount("Custom",this)>Custom</a>'; 
				}
			}
		}
		$this->PageData['Min_Amt_Msg'] = $this->Make_Price($minimum_giftcertificate_amount, true);
		$this->PageData['Min_Amt'] = $this->Make_Price($minimum_giftcertificate_amount, false);
		$this->PageData['Max_Amt_Msg'] = $this->Make_Price($maximum_giftcertificate_amount, true);
		$this->PageData['Max_Amt'] = $this->Make_Price($maximum_giftcertificate_amount, false);
		$this->PageData['TabStr'] = $tabstr;
		$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['meta_title'] = config('global.META_TITLE').' :: Gift Certificate';
		$this->PageData['CSSFILES'] = ['static.css','slick.css'];
		$this->PageData['JSFILES'] = ['slick.js','moment.min.js','giftcertificate.js'];	
		return view('staticpages.giftcertificate')->with($this->PageData);
	}*/
	
	public function GiftCertificate(Request $request)
	{
		$minimum_giftcertificate_amount = config('Settings.MINIMUM_GIFTCERTIFICATE_AMOUNT');
		$maximum_giftcertificate_amount = config('Settings.MAXIMUM_GIFTCERTIFICATE_AMOUNT');
		
		//echo  $minimum_giftcertificate_amount."".Make_Price($minimum_giftcertificate_amount, false);exit;
		if(isset($request['action']) && $request['action'] == 'add_gift_certificate')
		{
			if($request["customflag"] == "Custom")
			{
				//$request["gc_value"] = $request["cardamount"];
			}
			$currency_code = session('currency_code');
			
			$validatedData = $request->validate([
								'gc_value' => 'required|Numeric|Min:'.$minimum_giftcertificate_amount.'',
								'cardamount'	=> 'exclude_unless:customflag,Custom|required|Numeric|between:'.$minimum_giftcertificate_amount.','.$maximum_giftcertificate_amount.'',
								'recname' => 'required',
								'recemail' => 'required|email',
								'recconfemail' => 'required|email|Same:recemail',
								'yourname' => 'required',
					            'youremail' => 'required|email'
					        ], [
					            'gc_value.required' => config('message.Gift_Certi.GiftAmount'),
					            'gc_value.Numeric' => config('message.Gift_Certi.WrongGiftAmount'),
					            'gc_value.min' => 'Amount must be between '.$this->Make_Price($minimum_giftcertificate_amount, true).' and '.$this->Make_Price($maximum_giftcertificate_amount, true),
					            'cardamount.required_if' => config('message.Gift_Certi.GiftAmount'),
					            'cardamount.Numeric' => config('message.Gift_Certi.WrongGiftAmount'),
					            'cardamount.between' => 'Please enter custom amount between '.$this->Make_Price($minimum_giftcertificate_amount, true).' and '.$this->Make_Price($maximum_giftcertificate_amount, true),
					            'cardamount.required' => config('message.Gift_Certi.GiftAmount'),
					            'recname.required' => config('message.Gift_Certi.RecName'),
					            'recemail.required' => config('message.Gift_Certi.RecEmail'),
					            'recemail.email' => config('message.Gift_Certi.ValidEmail'),
					            'recconfemail.required' => config('message.Gift_Certi.ReRecEmail'),
					            'recconfemail.email' => config('message.Gift_Certi.ValidEmail'),
					            'recconfemail.Same' => config('message.Gift_Certi.ReRecEmail'),
					            'yourname.required' => config('message.Gift_Certi.Name'),
					            'youremail.required' => config('message.Gift_Certi.Email'),
					            'youremail.email' => config('message.Gift_Certi.ValidEmail'),
					        ]);
			
			if($request["gc_value"] > 0 && $currency_code!='USD')
			{
				$res_currency = Currency::where('status','=','1')->where('currency_code','=',$currency_code)->get();
				if($res_currency->count() > 0 )
				{
					$curr_rate = $res_currency[0]->exchange_rate;
					
					$request["gc_value"]	   = $request["gc_value"]/$curr_rate;
					if($request["customflag"] == "Custom")
					{
					  $request["customamount"] = $request["gc_value"];
					}
				}
			}
			$a = $this->insertGiftCertificate($request);
			if($a)
			{
				//Session::flash('gift_success','GiftCertificate added successfully.');
				return response()->json(array('Added' => 1));
			} else {
				//Session::flash('gift_error','Error in adding GiftCertificate.');
				return response()->json(array('Added' => 0));
			}
			//return redirect('/gift-certificate.html');
		}
		
		
		if($minimum_giftcertificate_amount<=0)
		{
			$minimum_giftcertificate_amount = 1;
			$minimum_giftcertificate_amount_msg = 1;
		}
		if($minimum_giftcertificate_amount > 0 &&  $maximum_giftcertificate_amount > 0)
		{
			if($minimum_giftcertificate_amount <= $maximum_giftcertificate_amount)
			{
				$tabstr = '';
				
				$min_gc_amt = $minimum_giftcertificate_amount;
				for($i=0;$i<4;$i++)
				{ 
					$class="";
					$priceVal = $this->Make_Price($min_gc_amt, true);
					
					if($i==0)
					{
						$class="class='active'";
					}
					if($min_gc_amt <= $maximum_giftcertificate_amount)
					{
						$tabstr.= '<a href="javascript:void(0);" onclick="GiftChangeAmount('.$this->Make_Price($min_gc_amt,false).',this)" '.$class.'>'.$priceVal.'</a>';
					}
					$min_gc_amt = $min_gc_amt + 10;
				}
				
				if(config('Settings.MINIMUM_GIFTCERTIFICATE_AMOUNT') < config('Settings.MAXIMUM_GIFTCERTIFICATE_AMOUNT'))
				{
					$tabstr.=  '<a href="javascript:void(0);" onclick=GiftChangeAmount("Custom",this)>Custom</a>'; 
				}
			}
		}
		
		$gift_certificate_images = array();
		
		$gift_certificate_image = $gift_certificate_image_title = '';
		
		$gift_certificate_image_path = config('global.SITE_URL').'images/gift-cards/';
		for($p=1;$p<=4;$p++)
		{
			
			$gift_certificate_image = generalsetting('GIFT_CERTIFICATE_IMAGE'.$p,1);
			
			if(isset($gift_certificate_image) && file_exists(config('global.SITE_DIR').'images/gift-cards/'.$gift_certificate_image) and !empty($gift_certificate_image)){
				
				$gift_certificate_images['image'][] = $gift_certificate_image_path.$gift_certificate_image.'?ver='.time();
				$gift_certificate_images['imageName'][] = $gift_certificate_image;
				
				$gift_certificate_image_title = generalsetting('GIFT_CERTIFICATE_IMAGE_TITLE'.$p,1);
				$gift_certificate_images['title'][] = $gift_certificate_image_title;
			}
		}
		
		$this->PageData['gift_certificate_images'] = $gift_certificate_images;
		
		
		$this->PageData['Min_Amt_Msg'] = $this->Make_Price($minimum_giftcertificate_amount, true);
		$this->PageData['Min_Amt'] = $this->Make_Price($minimum_giftcertificate_amount, false);
		$this->PageData['Max_Amt_Msg'] = $this->Make_Price($maximum_giftcertificate_amount, true);
		$this->PageData['Max_Amt'] = $this->Make_Price($maximum_giftcertificate_amount, false);
		$this->PageData['TabStr'] = $tabstr;
		$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['meta_title'] = config('global.META_TITLE').' :: Gift Certificate';
		$this->PageData['CSSFILES'] = ['static.css','slick.css'];
		$this->PageData['JSFILES'] = ['slick.js','moment.min.js','giftcertificate.js'];	
		return view('staticpages.giftcertificate')->with($this->PageData);
	}

	public function ContactUsNew(Request $request){
		if($request['action'] == 'submit') {
	        $validatedData = $request->validate([
								'your_name' => 'required',
								'your_email' => 'required|email',
								'your_subject' => 'required',
								'your_comment' => 'required'
					        ], [
					            'your_name.required' => config('message.ContactUs.YourName'),
					            'your_email.required' => config('message.ContactUs.YourEmail'),
					            'your_email.email' => config('message.ContactUs.YourEmail'),
					            'your_subject.required' => config('message.ContactUs.YourSubject'),
					            'your_comment.required' => config('message.ContactUs.YourComment')
					        ]);
			$comments  = stripslashes(nl2br(strtr($request['your_comment'], array('\r' => chr(13), '\n' => chr(10)))));
			$comments  = str_replace("<br />","",strip_tags($comments));
			$request['your_comment'] = $comments;
			$to_email 	= config('Settings.CONTACT_MAIL'); 
			$from_email = $request['your_email']; //CONTACT_MAIL; 
			
			$mail_subject = " Contact From MaxAroma Customer";
			$mail_body = $this->GetContactUsEmailTemplate($request);
			$freeshippinginfo = "";
			
			if(config('Settings.FREESHIPPING_VALUE')!="")
			{
				$freeshippinginfo = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
			}
			
			
			$details = [
			'Site_URL' 			=> config('global.SITE_URL'),
			'your_name' 		=> $request['your_name'], 
			'your_email' 		=> $request['your_email'],
			'your_comment' 		=> $request['your_comment'],
			'your_subject' 		=> $request['your_subject'],
			'ContactEmail' 	  	=> config('Settings.CONTACT_MAIL'),
			'FreeShippingVal' 	=> $freeshippinginfo
			  
			];
   
			//Mail::to($to_email)->send(new NotifyMail($details));

			// echo $mail_body; exit; 
            //$to_email = 'gequaldev@gmail.com';
            //SendMail($mail_subject,$mail_body,$to_email,$from_email);
            /*$data= ["name" => $mail_body];
            $user["to"] = "naresh.qualdev@gmail.com";
            Mail::send(['html'=>'mail'],$data,function($messages) use($user){
				$messages->to($user["to"]);
				$messages->subject("Contact From MaxAroma Customer");
				});
            /*
            if(config('global.OMNISEND_PROG') == false)
			{
                SendMail($mail_subject,$mail_body,$to_email,$from_email);
            } else {
                OmanisendRequest('6201253fb86552001e977a7b',$request);   
            }
            */
			Session::flash('success', config('message.ContactUs.Success'));
			return redirect()->back();
			
		}
		$PData = StaticPages::where('name','=','contactus')->where('status','=','1')->get();
		if(count($PData) > 0) {
			$Content = $PData[0]->content;
			$this->PageData['PageTitle'] = $PData[0]->title;
			$this->PageData['PageContent'] = $PData[0]->content;
			$this->PageData['meta_title'] = $PData[0]->meta_title;
			$this->PageData['meta_description'] = $PData[0]->meta_description;
			$this->PageData['meta_keywords'] = $PData[0]->meta_keywords;
			
			$Content = stripslashes($Content);
			$Content = str_replace('{$Site_URL}',config('global.SITE_URL'),$Content);
			$Content = str_replace('src="images/','src="'.config('global.SITE_URL').'images/',$Content);
			$Content = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $Content);	
			$Content = str_replace('{$ADMIN_MAIL}', config('Settings.ADMIN_MAIL'), $Content);	
			$Content = str_replace('{$CONTACT_PHONE_NO}', config('Settings.CONTACT_PHONE_NO'), $Content);

			$this->PageData['Content'] = $Content;
		} else {
			return redirect('/');
		}
		$GTMDATA = ['page' => 'contact_us', 'pagetype' => 'other'];
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		$this->PageData['JSFILES'] = ['contactus.js'];
		$this->PageData['contactus_url'] = config('global.SITE_URL')."contact-us-new.html";
		return view('staticpages.contactusnew')->with($this->PageData);

	}

	public function ContactUs(Request $request)
	{
		if($request['action'] == 'submit') {
	        $validatedData = $request->validate([
								'your_name' => 'required',
								'your_email' => 'required|email',
								'your_subject' => 'required',
								'your_comment' => 'required',
								'g-recaptcha-response' => 'required'
					        ], [
					            'your_name.required' => config('message.ContactUs.YourName'),
					            'your_email.required' => config('message.ContactUs.YourEmail'),
					            'your_email.email' => config('message.ContactUs.YourEmail'),
					            'your_subject.required' => config('message.ContactUs.YourSubject'),
					            'your_comment.required' => config('message.ContactUs.YourComment'),
								'g-recaptcha-response.required' => config('message.captcha.captcha_msg')
					        ]);
			$comments  = stripslashes(nl2br(strtr($request['your_comment'], array('\r' => chr(13), '\n' => chr(10)))));
			$comments  = str_replace("<br />","",strip_tags($comments));
			$request['your_comment'] = $comments;
			$to_email 	= config('Settings.CONTACT_MAIL'); 
			$from_email = $request['your_email']; //CONTACT_MAIL; 
			
			$mail_subject = " Contact From MaxAroma Customer";
			$mail_body = $this->GetContactUsEmailTemplate($request);
			// echo $mail_body; exit; 
            //$to_email = 'gequaldev@gmail.com'; 
            //$to_email = "qualdev.devs@gmail.com";
			//$to_email = "test-durp0907m@srv1.mail-tester.com";
			$result = SendMail($mail_subject,$mail_body,$to_email,$from_email);
            //dd($result);
			/*
            if(config('global.OMNISEND_PROG') == false)
			{
                SendMail($mail_subject,$mail_body,$to_email,$from_email);
            } else {
                OmanisendRequest('6201253fb86552001e977a7b',$request);   
            }
            */
			Session::flash('success', config('message.ContactUs.Success'));
			return redirect()->back();
			
		}
		$PData = StaticPages::where('name','=','contactus')->where('status','=','1')->get();
		if(count($PData) > 0) {
			$Content = $PData[0]->content;
			$this->PageData['PageTitle'] = $PData[0]->title;
			$this->PageData['PageContent'] = $PData[0]->content;
			$this->PageData['meta_title'] = $PData[0]->meta_title;
			$this->PageData['meta_description'] = $PData[0]->meta_description;
			$this->PageData['meta_keywords'] = $PData[0]->meta_keywords;
			
			$Content = stripslashes($Content);
			$Content = str_replace('{$Site_URL}',config('global.SITE_URL'),$Content);
			$Content = str_replace('src="images/','src="'.config('global.SITE_URL').'images/',$Content);
			$Content = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $Content);	
			$Content = str_replace('{$ADMIN_MAIL}', config('Settings.ADMIN_MAIL'), $Content);	
			$Content = str_replace('{$CONTACT_PHONE_NO}', config('Settings.CONTACT_PHONE_NO'), $Content);

			$this->PageData['Content'] = $Content;
		} else {
			return redirect('/');
		}
		$GTMDATA = ['page' => 'contact_us', 'pagetype' => 'other'];
		$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
		$this->PageData['JSFILES'] = ['contactus.js'];	
		return view('staticpages.contactus')->with($this->PageData);
	}
	
	public function CheckMail()
	{
		config(['app.debug' => true]);
		$mail_subject = "Test";
		$mail_body = "This is test";
		$to_email = "gequaldev@gmail.com";
		$from_email = "gequaldev@gmail.com";
		SendMail($mail_subject,$mail_body,$to_email,$from_email);
	}

	public function GetContactUsEmailTemplate($request)
	{
		return $html = view('email_templates.contactUsEmailTemplate', compact('request'))->render();
	}

	public function GetBrands(Request $request)
	{
		if(isset($request->Char) && $request->Char != '')
		{
			$BrandList = BrandsList($request->Char);
			$BrandStr='<a href="#" class="act">'.$request->Char.'</a>';
			foreach($BrandList as $Brand)
			{
				$BrandStr.='<a href="'.$Brand['Link'].'">'.$Brand['Name'].'</a>';
			}
			return response()->json(array('Brands' => $BrandStr));
		}
	}

	public function TrackOrder(Request $request)
	{
		
		$IsGiftCertificateItem = '';
		
		if(Auth::user()) {
			return redirect('/order-history.html');
		}

		if(isset($request->action) && $request->action == 'submit')
		{

	        $validatedData = $request->validate([
					            'bill_email' => 'required|email',
					            'orders_id' => 'required',
								'g-recaptcha-response' => 'required'
					        ], [
					            'bill_email.required' => config('message.TrackOrder.ValidEmail'),
					            'bill_email.email' => config('message.TrackOrder.ValidEmail'),
					            'orders_id.required' => config('message.TrackOrder.OrderID'),
								'g-recaptcha-response.required' => config('message.Validate.Captcha')
					        ]);

			$orders_id  = trim($request->orders_id);
			$optionname = trim($request->optionname);


			if(trim($orders_id) !='') {
				Session::put('orders_idnew', $orders_id);
			}

			if(trim($optionname) !='') {
				Session::put('optionnamenew', $optionname);
			}
			
			$track_flag=0;

			if($optionname=="check_order_zip")
			{
				$bill_email=trim($request->bill_email);
				if($orders_id!='')
				{
					$order_res = $this->GetOrderinfo($orders_id,$bill_email);
					if(count($order_res) >0)
					{
						$track_flag=1;
						Session::put('track_flag', 1);
					}
					else
					{
						return redirect()->back()
						->withInput()
						->withErrors([
							'incorrect' => 'Unfortunately, we could not locate an order with the information you provided. Please make sure you entered the correct billing email address and order number is correct.',
						]);
					}
				}
				else
				{
					return redirect()->back()
					->withInput()
					->withErrors([
						'incorrect' => 'Unfortunately, we could not locate an order with the information you provided. Please make sure you entered the correct billing email address and order number is correct.',
					]);
				}
			}

			if($track_flag==1 || (Session::has('track_flag') && Session::has('track_flag') == 1))
			{
				$OrderRs = Order::select('*')->addSelect(DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime,DATEDIFF(now(), order_datetime) as days,DATE_FORMAT(order_datetime, '%d-%m-%Y') AS newdatetime"))
									->where('orders_no', '=', $orders_id)
									->where('bill_email', '=', $bill_email)
									->first();
			}
			else
			{
				return redirect('/login.html');
			}

			$GC_Only = $quantity = 0;

			$OrderDetailRs = OrderDetail::select('*')
								->where('orders_id', '=', $OrderRs->orders_id)
								->orderBy('orders_detail_id', 'DESC')
								->get();

			if(count($OrderDetailRs) > 0) {
				$is_returnable		= 'no';
				$policeReportAttach	= '';
				
				$claimPoliceReport = Claim::select('police_report_document')
									->where('orders_id', '=', $OrderRs->orders_id)
									->first();
				
				$reportDoc = '';
				if($claimPoliceReport && $claimPoliceReport->count() > 0){
					$reportDoc	= $claimPoliceReport->police_report_document;
				}
				
				if(isset($reportDoc) && trim($reportDoc) != '' && file_exists(config('global.RETURN_PDF_PATH').'claims/'.$reportDoc)){
					$policeReportAttach	= config('global.RETURN_PDF_URL').'claims/'.$reportDoc;
				}
							
				for($p=0;$p<count($OrderDetailRs);$p++)
				{
					$quantity	= $quantity + $OrderDetailRs[$p]['quantity'];
					
					$IsGiftCertificateItem = $this->checkGiftCertificateItem('IsGiftCertificateItem',$OrderDetailRs[$p],'No');
					
					//if($OrderDetailRs[$p]["sku"]==config('global.GIFT_CERTIFICATE_SKU'))
					if($IsGiftCertificateItem == 'Yes')
					{
						if(!empty(Auth::user()->customer_id) && Auth::user()->customer_id >0)
						{
							$GCRs = GiftCertificate::where('orders_detail_id', '=', $OrderDetailRs[$p]['orders_detail_id'])
													->where('customer_id', '=', Auth::user()->customer_id)
													->first();
							if($GCRs && $GCRs->count() > 0) {
								$OrderDetailRs[$p]['RecipientName']  	= $GCRs->recipient_name;
								$OrderDetailRs[$p]['RecipientEmail'] 	= $GCRs->recipient_email;
								//$OrderDetailRs[$p]['Image']				= config('global.GC_IMAGE_URL');
								$OrderDetailRs[$p]['Image'] = $this->checkGiftCertificateItem('SetGiftCertificateImageURL',$OrderDetailRs[$p],'No');
							}
						}
					}
					else
					{

						$prod_res = Products::whereRaw("LOWER(TRIM(sku))='".strtolower(trim($OrderDetailRs[$p]['sku']))."'")->select('image')->first();
						if($prod_res && $prod_res->count() > 0) {
							if(file_exists(config('global.PRD_THUMB_IMG_PATH').$prod_res->image) && !empty($prod_res->image))
								$thumb_image = config('global.PRD_THUMB_IMG_URL').$prod_res->image;
							 else
								$thumb_image = config('global.NO_IMAGE_THUMB');
						} else {
							$thumb_image = config('global.NO_IMAGE_THUMB');
						}
						$OrderDetailRs[$p]['Image'] = $thumb_image;

					}
					
					if($OrderDetailRs[$p]['is_free_gift_products'] !='Yes' && $OrderRs->days <= 30 && $OrderRs->ship_status =='Shipped' && in_array($OrderRs->status, ['Completed','Return Requested','Return Approved','Return Rejected','Item Received']) && in_array($OrderDetailRs[$p]['ostatus'],['Pending','Completed'])){
						$is_returnable	= 'yes';
					}
				}

				if($OrderRs->is_only_gc==1) {
					$GC_Only = 1;
				}
				$this->PageData['OrderRs'] = $OrderRs;
				$this->PageData['OrderDetailRs'] = $OrderDetailRs;
				$this->PageData['GC_Only'] = $GC_Only;
				$this->PageData['quantity'] = $quantity;
				$this->PageData['policeReportAttach'] = $policeReportAttach;
				$this->PageData['orders_detail_id'] = $OrderDetailRs[0]['orders_detail_id'];
				$this->PageData['is_returnable'] = $is_returnable;
				$view_fileType = "";
				if(Session::get('eusertype') == "Wholesaler" || (Session::get('eusertype')=="Wholesaler" && Session::get('is_dropshipper')=="Yes")) {
					$view_fileType  = 'pdf';
				} else {
					$view_fileType = 'print';
				}
				$this->PageData['view_fileType'] = $view_fileType;
				$this->PageData['trackorder'] = "trackorder";
				$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Order Detail';
				$this->PageData['CSSFILES'] = ['myaccount.css'];
				return view('myaccount.orderdetail')->with($this->PageData);
			}	

		}

		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Track Order';
		$this->PageData['JSFILES'] = ['login.js', 'trackorder.js'];	
		return view('staticpages.trackorder')->with($this->PageData);
	}
	public function GetOrderinfo($orders_id,$bill_email)
	{
		$order_res = Order::select('orders_id')
							->where('orders_no', '=', $orders_id)
							->where('bill_email', '=', $bill_email)
							->get();
		return $order_res;
	}

	public function DontSeeRequest(Request $request)
	{

		if($request['action'] == 'submit') {

	        $validatedData = $request->validate([
								'custname' => 'required',
								'email'	=> 'required|email',
								// 'comments' => 'required|alpha_num',
								'comments' => 'required',
								'product_price' => 'required',
								'product_qty' => 'required',
								'delivery_date' => 'required'
					        ], [
					            'custname.required' => config('message.DontSeeRequest.CustName'),
					            'email.required' => config('message.DontSeeRequest.ValidEmail'),
					            'email.email' => config('message.DontSeeRequest.ValidEmail'),
					            'comments.required' => config('message.DontSeeRequest.Comments'),
					            // 'comments.alpha_num' => "Only alpha_num allowed.",
					            'product_price.required' => config('message.DontSeeRequest.ProductPrice'),
					            'product_qty.required' => config('message.DontSeeRequest.ProductQty'),
					            'delivery_date.required' => config('message.DontSeeRequest.DeliveryDate')
					        ]);

			$comments  = stripslashes(nl2br(strtr(trim($request['comments']), array('\r' => chr(13), '\n' => chr(10)))));
			$comments  = str_replace("<br />","",strip_tags($comments));

			$datetime = date('Y-m-d');
			$saveData['vname'] = $request['custname'];
			$saveData['vemail'] = $request['email'];
			$saveData['vproduct'] = $comments;
			$saveData['ddateadded'] = $datetime;
			$saveData['product_price'] = $request['product_price'];
			$saveData['product_qty'] = $request['product_qty'];
			$saveData['delivery_date'] = date("Y-m-d",strtotime($request['delivery_date']));

			$CustRequest = CustRequest::create($saveData);
			if($CustRequest) {
				$to_email 	= config('Settings.CONTACT_MAIL');
				$mail = GetMailTemplate("DONOT_SEE_REQUEST");

				$subject = stripslashes($mail[0]->subject);

				$message = stripslashes($mail[0]->mail_body);
				$message = str_replace('{$Site_URL}', config('global.Site_URL'), $message);
				$message = str_replace('{$CUST_NAME}', $request['custname'], $message);
				$message = str_replace('{$comments}', $comments, $message); 
				$message = str_replace('{$CONTACT_MAIL}',config('Settings.CONTACT_MAIL'),$message);
				$freeshippinginfo = '';
				if(config('Settings.FREESHIPPING_VALUE')!="")
				{
					$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
				}
				$message = str_replace('{$freeshippinginfo}',$freeshippinginfo,$message);
				$Subject = $mail[0]->subject;
				$To 	= config('Settings.CONTACT_MAIL'); 
				$From = $request['email'];
				// echo $message; exit;
				SendMail($Subject,$message,$To,$From);
				Session::flash('success', config('message.DontSeeRequest.Success'));
			} else {
				Session::flash('error', config('message.DontSeeRequest.Error'));
			}
			return redirect()->back();
		}

		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['meta_title'] =  config('Settings.SITE_TITLE')." :: Don't See Request";
		$this->PageData['JSFILES'] = ['dontseerequest.js','moment.min.js'];	
		return view('staticpages.dontseerequest')->with($this->PageData);		
	}
    
    public function ResetPassword(Request $request)
    {
        if(!isset($request->code) || trim($request->code) == '')
            return redirect('/');
        
        
        if(isset($request['action']) && $request['action'] == 'reset')
		{
            $ChkCustomer = Customer::where('code','=',$request->code)->get();
            if($ChkCustomer->count() > 0)
            {
                $validatedData = $request->validate([
                                    'password' => 'required|same:confirm_Password',
                                    'confirm_Password' => 'required',
                                ], [
                                    'password.required' => config('message.Register.Password'),
                                    'password.same' => config('message.Validate.ValidConfirmPassword')
                                ]);
                $UserData = array('password' => $request['password'],'code' => '');
                $Update = Customer::where('customer_id','=',$ChkCustomer[0]['customer_id'])->update($UserData);
                if($Update)
                    Session::flash('success', 'Your password updated successfully.');
                else
                    Session::flash('failed', 'Error in password updation.');
                return redirect()->back(); 
            } else {
                Session::flash('failed', 'Customer not found.');
                return redirect()->back(); 
            }
        }
        $this->PageData['code'] = $request->code;
        $this->PageData['meta_title'] =  config('Settings.SITE_TITLE').' :: Reset Password';
		$this->PageData['JSFILES'] = ['reset-password.js'];	
		$this->PageData['CSSFILES'] = ['myaccount.css'];	
		return view('customer.reset-password')->with($this->PageData);
    }
    
    public function FAQ(Request $request)
	{   
		$PData = FAQS::where('status','=','1')->where('type','=','Retailer')->orderBy('rank')->get();
		
		if($PData->count() > 0)
		{
			foreach($PData as $key => $Data)
			{
				//dd($Data->question);
				$Data->question = ltrim($Data->question,"<p>");
				$Data->question = rtrim($Data->question,"</p>");
				$Data->answer = $Data->answer;
				/*$Data->question = stripslashes(ltrim($Data->question,"<p>"));
				$Data->question = stripslashes(rtrim($Data->question,"</p>"));
				$Data->answer = stripslashes(ltrim($Data->answer,"<p>"));
				$Data->answer = stripslashes(rtrim($Data->answer,"</p>"));*/
			}
		}
		
		$this->PageData['PageTitle'] = 'FAQS';
		$this->PageData['PData'] = $PData;

		return view('staticpages.faq')->with($this->PageData);		
	}
	
	public function SHIPPING_SERVICE(Request $request)
	{   
		$PData = FAQS::where('status','=','1')->where('type','=','Dropshipper')->orderBy('rank')->get();
		
		if($PData->count() > 0)
		{
			foreach($PData as $key => $Data)
			{
				//dd($Data->question);
				$Data->question = ltrim($Data->question,"<p>");
				$Data->question = rtrim($Data->question,"</p>");
				$Data->answer = $Data->answer;
				/*$Data->question = stripslashes(ltrim($Data->question,"<p>"));
				$Data->question = stripslashes(rtrim($Data->question,"</p>"));
				$Data->answer = stripslashes(ltrim($Data->answer,"<p>"));
				$Data->answer = stripslashes(rtrim($Data->answer,"</p>"));*/
			}
		}
		
		$this->PageData['PageTitle'] = 'DROPSHIPPING FAQS';
		$this->PageData['PData'] = $PData;

		return view('staticpages.shipping_service')->with($this->PageData);		
	}

}
?>
