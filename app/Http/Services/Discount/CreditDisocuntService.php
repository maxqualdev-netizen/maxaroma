<?php
namespace App\Services\Discount;

use App\Constants\CheckoutConstants;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
class CreditDisocuntService
{

    public function ApplyCreditDiscount($check = 0,$CurrentNetTotal=0)
	{
		Session::put('ShoppingCart.credit_limit_discount',0);
		Session::put('ShoppingCart.customer_remaining_credit_amount',0);
		$NetTotal = $CurrentNetTotal - Session::get('shipping_insurance_charge');
		$log[] = "";

		//$user = Auth::guard('web')->user();
		$user = Auth::user();
		$creditLimit = $user->credit_limit ?? 0;

		if($user && Session::get('etype') == "M" && $user->is_dropshipper !='Yes' && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes')
		{
			if($creditLimit > 0 && $check==1)
			{
				if($creditLimit > $NetTotal)
				{
					$credit_limit_discount = $CurrentNetTotal;
					$rem_amt = $creditLimit - $NetTotal;
				}
				else
				{
					$credit_limit_discount = $creditLimit;
					$rem_amt = 0.00;
				}
				Session::put('ShoppingCart.credit_limit_discount',$credit_limit_discount);
				$log['credit_limit_discount'] = $credit_limit_discount;
				$CartInfo = Session::get('ShoppingCart.Cart');
				$TotalAmount = 0;
				for($i=0;$i<count($CartInfo);$i++)
				{
					$FreeGift = '';
					if(isset($CartInfo[$i]["IS_Free_Gift"]))
						$FreeGift = $CartInfo[$i]["IS_Free_Gift"];

					$FreeSample = '';
					if(isset($CartInfo[$i]["Is_Free_Sample"]))
						$FreeSample = $CartInfo[$i]["Is_Free_Sample"];

					if(isset($CartInfo[$i]["IsDealProducts"]) && $CartInfo[$i]["IsDealProducts"]!="Yes" && $FreeGift!="Yes" && $FreeSample != "Yes")
					{
						$TotalAmount = $TotalAmount + $CartInfo[$i]['TotPrice'];
					}

				}
				if($TotalAmount  > 0)
				{
					for($i=0;$i<count($CartInfo);$i++)
					{
						$FreeGift = '';
						if(isset($CartInfo[$i]["IS_Free_Gift"]))
							$FreeGift = $CartInfo[$i]["IS_Free_Gift"];

						$FreeSample = '';
						if(isset($CartInfo[$i]["Is_Free_Sample"]))
							$FreeSample = $CartInfo[$i]["Is_Free_Sample"];
						if((isset($CartInfo[$i]["IsDealProducts"]) && $CartInfo[$i]["IsDealProducts"]!="Yes") && $FreeGift!="Yes" && $FreeSample != "Yes")
						{

							$CreditLimitDiscountItemWise = (($credit_limit_discount*100)/$TotalAmount);
							$CreditLimitDiscountCal = (($CartInfo[$i]['TotPrice']  * $CreditLimitDiscountItemWise)/100);

							Session::put('ShoppingCart.Cart.'.$i.'.CreditLimitItemWiseDiscout',$CreditLimitDiscountCal);
						}

					}
				}

			}
			else
			{
				Session::put('ShoppingCart.credit_limit_discount',0.00);
				$rem_amt = 0.00;
			}
		}
		else
		{
			Session::put('ShoppingCart.credit_limit_discount',0.00);
			$rem_amt = 0.00;
		}
		//echo  Session::get('ShoppingCart.credit_limit_discount'); exit;
		Session::put('ShoppingCart.customer_remaining_credit_amount',$rem_amt);
		// $log['customer_remaining_credit_amount'] = $rem_amt;
		// addLog('ApplyCreditDiscount',$log);
		// return NumberFormat($rem_amt)."###".$this->GetNetTotal();
	}
    public function GetCreditLimitAmount($NetTotal=0)
	{
		$CreditAmt = 0;
		$RemainCreditLimit = 0;
		$CreditLimitFlag = 0;

		$normaluser = Auth::user();
		if($normaluser && $CreditAmt = $normaluser->credit_limit > 0 && $normaluser->registration_type == 'M' && config('Settings.WHOLESALE_CREDIT_LIMIT') == 'Yes' && $normaluser->is_dropshipper != 'Yes')
		{
            $CreditDiscount = 0;
            if(Session::has('ShoppingCart.credit_limit_discount') && Session::get('ShoppingCart.credit_limit_discount') > 0)
			    $CreditDiscount = number_format(Session::get('ShoppingCart.credit_limit_discount'),2,'.','');
			if(Session::has('ShoppingCart.customer_remaining_credit_amount'))
				$RemainCreditLimit = number_format(Session::get('ShoppingCart.customer_remaining_credit_amount'),2,'.','');

			$CreditAmt = $normaluser->credit_limit;
			if($CreditDiscount <= 0 && $NetTotal > 0){
				$CreditLimitFlag = 1;
			}elseif($CreditDiscount > 0){
				$CreditLimitFlag = 2;
			}else{
				$CreditAmt = 0;
			}
		}
		return ['CreditLimitFlag' => $CreditLimitFlag, 'CreditLimit' => $CreditAmt, 'RemainCreditLimit' => $RemainCreditLimit];
	}
}