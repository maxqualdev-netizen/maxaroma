<?php

namespace App\Http\Services;

use App\Models\SiteOffers;
use App\Models\WishlistCategory;
use App\Models\Products;
use App\Models\Customer;
use App\Http\Controllers\Traits\VendorTrait;
use Session;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Cache;

class PopUpService implements PopUpServiceContract
{
    use VendorTrait;

    public function getSiteOffers($sectionId)
    {
        return SiteOffers::where('section_id', '=', $sectionId)
            ->where('status', '=', '1')->where('expiry_date', '>=', date('Y-m-d'))->get();
    }

    public function getWishlistCategory($customerId)
    {
        return WishlistCategory::select('*')
            ->where('customer_id', '=', $customerId)
            ->orderBy('wishlist_category_id', 'DESC')
            ->get();
    }

    public function getProductsById($productId)
    {
        return Products::select('products_id', 'sku', 'product_name')
            ->where('products_id', '=', $productId)
            ->get();
    }

    public function getProductsDetailsById($productId)
    {
        $Product = Products::where('products_id', '=', $productId)
            ->get();
        if($Product->count() > 0)
        {
            return $this->SetProduct($Product[0]);
        }
    }

    public function StoreUserLoginpProcess($email,$otp){
        $Customer = Customer::where('email', $email)
            ->where('status', '1')
            ->where('registration_type', 'M')
            ->where('block_customer_flag', 'No')
            ->first();

        if ($Customer && $Customer->count() > 0 ) {
            return $this->LoginpProcess($email,$Customer->password);
        }
    }

    public function LoginpProcess($email, $password)
    {
        $Customer = Customer::where('email', $email)
            ->where('password', $password)
            ->where('status', '1')
            ->where('registration_type', 'M')
            ->where('block_customer_flag', 'No')
            ->first();

        if ($Customer && $Customer->count() > 0) {
            if ($Customer->eusertype == "Wholesaler Pending") {
                $Customer->eusertype = "Retailer";
            }

            Auth::login($Customer, false);
            //Cookie::queue(Cookie::make('omnisendContactID',$Customer->omnisend_accountid,time()+60*60*24*15));
            Cookie::make('omnisendContactID',$Customer->omnisend_accountid,time()+60*60*24*15);
            Session::put('sess_useremail', $Customer->email);
            Session::put('sess_username', $Customer->first_name);
            Session::put('sess_icustomerid', $Customer->customer_id);
            Session::put('eusertype', $Customer->eusertype);
            Session::put('is_dropshipper', $Customer->is_dropshipper);
			Session::put('SpecialCustomerFlag',$Customer->DownloadSpecialPricelist);
            Session::put('etype', 'M');
            Session::put('payment_amount', $Customer->payment_amount);
            SetCustomerLoyaltyTier($Customer->email);

            $custFname = '';
			if(isset($Customer->first_name) &&  $Customer->first_name!='' )
			{
				$custFname = $Customer->first_name;
			}
			$custlname = '';
			if(isset($Customer->last_name) && $Customer->last_name!='')
			{
				$custlname = $Customer->first_name;
			}
			Session::put('sess_custname',$custFname."|".$custlname);
			$CityVal = '';
			if(isset($Customer->city) &&  $Customer->city!='')
			{
				$CityVal = $Customer->city;
			}
			$StateVal = '';
			if(isset($Customer->state) &&  $Customer->state!='')
			{
				$StateVal = $Customer->city;
			}
			$CountryVal = '';
			if(isset($Customer->country) &&  $Customer->country!='')
			{
				$CountryVal = $Customer->country;
			}
			$ZipVal = '';
			if(isset($Customer->zip) &&  $Customer->zip!='')
			{
				$ZipVal = $Customer->zip;
			}
			$PhoneVal = '';
			if(isset($Customer->phone) &&  $Customer->phone!='')
			{
				$PhoneVal = $Customer->phone;
			}
			Session::put('sess_useraddress',$CityVal."|".$StateVal."|".$CountryVal."|".$ZipVal."|".$PhoneVal);

            if($Customer->email == 'gequaldev@gmail.com')
            {
                config(['global.YOTPO_PROG' => true ]);
            }
            /*
            $YotpoInfo = YotpoRequest('customer_detail',['email' => $Customer->email]);

            if(isset($YotpoInfo->third_party_id))
            {
                Session::put('UserYotpoID', $YotpoInfo->third_party_id);
            }
            */
            return true;
        } else {
            return false;
        }
    }

    public function sendInstantCouponMail($email)
    {
        $Template = GetMailTemplate("INSTANT_COUPON");

        $EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $Template[0]->mail_body);
        $EmailBody = str_replace('{$Site_URL}', config('Settings.Site_URL'), $EmailBody);
        $EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
        $EmailBody = str_replace('{$COUPON_CODE_VALUE}', config('Settings.COUPON_CODE_VALUE'), $EmailBody);
        $EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);

        $FreeShipping = "";
        if (config('Settings.FREESHIPPING_VALUE')) {
            $FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders</span>';
        }
        $EmailBody = str_replace('{$freeshippinginfo}', $FreeShipping, $EmailBody);

        $To = $email;
        $Subject = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $Template[0]->subject);
        $From = config('Settings.CONTACT_MAIL');
        SendMail($Subject, $EmailBody, $To, $From);
    }

    public function sendForgotPasswordMail($email, $password)
    {
        $Template = GetMailTemplate("FORGOT_PASSWORD");

        $EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $Template[0]->mail_body);
        $EmailBody = str_replace('{$vemail}', $email, $EmailBody);
        $EmailBody = str_replace('{$password}', $password, $EmailBody);
        $EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.CONTACT_PHONE_NO'), $EmailBody);
        $EmailBody = str_replace('{$Site_URL}', config('Settings.SITE_URL'), $EmailBody);

        $FreeShipping = "";
        if (config('Settings.FREESHIPPING_VALUE')) {
            $FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders</span>';
        }
        $EmailBody = str_replace('{$freeshippinginfo}', $FreeShipping, $EmailBody);

        $To = $email;
        $Subject = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $Template[0]->subject);
        $From = config('Settings.CONTACT_MAIL');
        SendMail($Subject, $EmailBody, $To, $From);
    }

    public function sendNicheFragranceMail($email, $coupon_code)
    {
        $Template = GetMailTemplate("NICHE_FRAGRANCES");
        $EmailBody = str_replace('{$Site_URL}', config('Settings.Site_URL'), $Template[0]->mail_body);
        $EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $EmailBody);
        $EmailBody = str_replace('{$sender}', '$sender', $EmailBody);
        $EmailBody = str_replace('{$refer_comment}', '$refer_comment', $EmailBody);
        $EmailBody = str_replace('{$cid}', '$cust_id', $EmailBody);
        $EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
        $EmailBody = str_replace('{$coupon_code}', $coupon_code, $EmailBody);

        $FreeShipping = "";
        if (config('Settings.FREESHIPPING_VALUE')) {
            $FreeShipping = '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $' . config('Settings.FREESHIPPING_VALUE') . ' or more Orders</span>';
        }
        $EmailBody = str_replace('{$freeshippinginfo}', $FreeShipping, $EmailBody);
        $To = $email;
        $Subject = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $Template[0]->subject);
        $From = config('Settings.CONTACT_MAIL');
        SendMail($Subject, $EmailBody, $To, $From);
    }
}
