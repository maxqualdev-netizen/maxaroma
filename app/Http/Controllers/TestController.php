<?php 
namespace App\Http\Controllers;

/* use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use URL;

use Hash;
use Session;
use App\Models\MetaInfo;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;

use DB;
use Mail; 

use Illuminate\Support\Facades\Log;
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\AfterpayTrait;
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
use Cache;


class TestController extends Controller
{
	
	use CommonTrait;
	use VendorTrait;
	use CartTrait;
	public function __construct()
    {

		$sampleProducts = explode("#","2ML5695006#2ML5695037#2ML695068#BOCS#BOSLS");
		$totalConfiguredFreeSamples = 6;

		if($totalConfiguredFreeSamples > count($sampleProducts)){
			$newlim = $totalConfiguredFreeSamples - count($sampleProducts);
			//$newProducts = $this->GetProductsNewFilters("",252);
			$Filters['isSampleProduct'] = 'Y';
			$newProducts = $this->GetProductsNewFilters("",252,12,$Filters);
			//$newProducts = $this->GetProductsNewFilters("",252,$newlim,$Filters);
			$originalProducts = $sampleProducts;
			if (isset($newProducts['Products']) && is_array($newProducts['Products']) && count($newProducts['Products']) > 0) {
				$skus = array_column($newProducts['Products'], 'sku');
				$skus = array_diff($skus, $originalProducts); 
				
				$sampleProducts = array_merge($originalProducts, $skus);
				$cntSampleProducts = count($sampleProducts);
				
				$numToPick = $totalConfiguredFreeSamples - count($originalProducts);
				if ($numToPick > 0) {
					$remainingSkus = array_diff($sampleProducts, $originalProducts);
					$numToPick = min($numToPick, count($remainingSkus));

					if ($numToPick > 0) {
						$randomKeys = array_rand($remainingSkus, $numToPick);
						$randomKeys = (array)$randomKeys;
						$randomSkus = array_intersect_key($remainingSkus, array_flip($randomKeys));
						
						$sampleProducts = array_merge($originalProducts, $randomSkus);
					} else {
						$sampleProducts = $originalProducts;
					}
				} else {
					$sampleProducts = $originalProducts;
				}
				echo count($sampleProducts);
				echo "<pre>";
				print_r($sampleProducts);exit;
			}
			
			// if(isset($newProducts['Products']) && is_array($newProducts['Products']) && count($newProducts['Products']) > 0){
			// 	$skus = array_column($newProducts['Products'], 'sku');
			// 	$sampleProducts = array_unique(array_merge($sampleProducts,$skus));

			// 	$cntSampleProducts = count($sampleProducts);

			// 	$numToPick = min($totalConfiguredFreeSamples, $cntSampleProducts);
			// 	$randomKeys = array_rand($sampleProducts, $numToPick);

			// 	//$randomKeys = array_rand($sampleProducts,$totalConfiguredFreeSamples);
			// 	$randomKeys = (array) $randomKeys;
			// 	$sampleProducts = array_intersect_key($sampleProducts, array_flip($randomKeys));
			// 	echo count();
			// 	echo "<pre>";
			// 	print_r($sampleProducts);exit;
				
			// }

		}


		exit;
		$Filters['isSampleProduct'] = 'Y';
		$nPro = $this->GetProductsNewFilters("",252,1,$Filters);
		// echo "<pre>";
		// print_r($nPro);
		// echo "</pre>";
		// echo count($nPro['Products']);
		// echo "<pre>";print_r($nPro);
		// exit;
		// $nPro = array();
		// for($n = 0; $n < count($nProducts['Products']); $n++){
		// 	if($nProducts['Products'][$n]->category_id == 252 || $nProducts['Products'][$n]->category_id == 253 || $nProducts['Products'][$n]->category_id == 254 || $nProducts['Products'][$n]->category_id == 255){
		// 		$nPro['Products'][] = $nProducts['Products'][$n];
		// 	}
		// }
		for($i = 0; $i < count($nPro['Products']); $i++){
			//if($nPro['Products'][$i]->category_id == 252 || $nPro['Products'][$i]->category_id == 253 || $nPro['Products'][$i]->category_id == 254 || $nPro['Products'][$i]->category_id == 255){
				echo "<br>";
				$pro = $i."------".$nPro['Products'][$i]->current_stock."------".$nPro['Products'][$i]->products_id."-----".$nPro['Products'][$i]->sku."-----".$nPro['Products'][$i]->product_name."-----".$nPro['Products'][$i]->category_id."-----".$nPro['Products'][$i]->product_url;
				$pro .= '<br><a href="'.$nPro['Products'][$i]->product_url.'">'.$nPro['Products'][$i]->product_url.'</a>';
				echo $pro;
			//}			
		}
		exit;
		echo count($nPro['Products']);
		echo "<pre>";print_r($nPro);
		exit;

		$newProducts = $this->GetProductsNewFilters("",252,100);
		$prod = $newProducts['Products'];
		echo count($prod);
		for($i = 0; $i < count($prod); $i++){
			if($prod[$i]->category_id == 252 || $prod[$i]->category_id == 253 || $prod[$i]->category_id == 254 || $prod[$i]->category_id == 255){
				echo "<br>";
				echo $prod[$i]->products_id."-----".$prod[$i]->sku."-----".$prod[$i]->product_name."-----".$prod[$i]->category_id;
			}			
		}
		echo "<pre>";
		print_r($prod);
		
			// dd(Session::all());
		 echo "<br><br>Current Time===== ".date("Y-m-d H:i:s");//exit;
		 
		 $results = DB::select('select now() as time1');
		 echo "<br><br>DB Time===== ".$results[0]->time1;exit;
		 
		// echo "<pre>";print_r($results);exit;
	}
	
	public function DemoFunction()
    {
		 // echo "<br><br>Current Time===== ".date("Y-m-d H:i:s");exit;
	}
}
?>
