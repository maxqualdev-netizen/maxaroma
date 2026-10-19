<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Cache;
use Illuminate\Support\Facades\Artisan;

class FrontCacheController extends Controller
{
	public function ClearFrontCache(Request $request)
	{
		$DateVaRnEWvAL = 'DealProd'.date("Y-m-d");
		
		$cacheArray = array('categories','menu_array','brandlist','popular_brands','arr_currency','BottomHtmlText','HomeBanners','HomeCategoryBanners','HOMEMETAINFO','DefaultMetaInfo','DealBrands','settingvars_cache','generalsettingvars_cache','HomeBannersVideo','HomePageProductss','HomePageProductssTop','DealProductCache','HomeDealBanners','RecommendedProductCache','homepage_newarrival_cache','homepage_bestseller_cache','AllTheCategory','VariationProductCache','CATEMETAINFO','BLMetaInfo',$DateVaRnEWvAL,'ShippingStaticCache','Afterpay_payCache','PRODUCTBANNERCache');
		if (in_array($request->cachevarialbe, $cacheArray)) {
			Cache::forget($request->cachevarialbe);
			$array = array(
				'message' => $request->cachevarialbe.'cache clear sucessfully',
			);

			return response()->json($array);
		}
	}
	public function CreateFrontCache(Request $request)
	{
		$cacheArray = array('VariationProductCacheCreate');
		if (in_array($request->cachevarialbe, $cacheArray)) {
			Cache::put('variation_cache', json_decode($request->creatcachevar,true));
			$array = array(
				'message' => $request->cachevarialbe.'cache clear sucessfully',
			);

			return response()->json($array);
		}
	}
}
