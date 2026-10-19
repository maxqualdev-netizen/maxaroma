<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Traits\CommonTrait;
use Session;
use App\Models\MetaInfo;
use App\Models\Manufacture;
use App\Models\Submanufacture;
use App\Models\Promotional;
use App\Models\SiteOffers;
use App\Models\PromotionalBanner;
use App\Models\BrandLandling;
use App\Models\MainbrandLanding;
use App\Models\Order;
use DB;
use Cache;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
	use CommonTrait;
	public $PageData;
	public function __construct()
    {
        /*
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
        */
	}
	public function BrandPage(Request $request)
	{
        if(Cache::has('DefaultMetaInfo'))
        {
            $MetaInfo = Cache::get('DefaultMetaInfo');
            $this->PageData['meta_title'] = getMetaTitleDescription($MetaInfo->meta_title);//$MetaInfo->meta_title;
            $this->PageData['meta_description'] = getMetaTitleDescription($MetaInfo->meta_description);//$MetaInfo->meta_description;
            $this->PageData['meta_keywords'] = getMetaTitleDescription($MetaInfo->meta_keywords);//$MetaInfo->meta_keywords;
        }

        $this->PageData['CanonicalURL'] = url()->current();
		$imanufactureid = $request->brand_id;
		$category_id = 0;
		$BrandDetail = Manufacture::where('imanufactureid', '=', $imanufactureid)
			->where('status', '=', '1')->get();
		if ($BrandDetail && $BrandDetail->count() > 0)
		{
			$BrandDetail[0]->brand_history  = str_replace('\\r\n','<br/>',nl2br($BrandDetail[0]->brand_history));

	//		$BrandDetail[0]->brand_history = strip_tags($BrandDetail[0]->brand_history);

			$BrandDetail[0]->brand_history  = str_replace('&#8217;',"'",$BrandDetail[0]->brand_history);

			$BrandDetail[0]->brand_history  = remove_html_entities($BrandDetail[0]->brand_history);
		//	$BrandDetail[0]->brand_history  = stripslashes(utf8_encode($BrandDetail[0]->brand_history));

			$this->PageData['BrandDetail'] = $BrandDetail;
			$vmanufacture = str_replace("'","",stripcslashes($BrandDetail[0]->vmanufacture));
			//echo $vmanufacture; exit;
			//echo stripcslashes(remove_special_chars($BrandDetail[0]->vmanufacture)); exit;

			//$Meta = MetaInfo::where('type', '=', 'DP')->get();
			if (!Cache::has('DPMetaInfo')){
				$PageType = 'DP';
				$Meta = MetaInfo::where('type','=',$PageType)->get();
				Cache::put('DPMetaInfo', $Meta);
			} else {
				$Meta = Cache::get('DPMetaInfo');
			}

			$this->PageData['meta_title'] = getMetaTitleDescription($Meta[0]->meta_title);
			$this->PageData['meta_keywords'] = getMetaTitleDescription($Meta[0]->meta_keywords);
			$this->PageData['meta_description'] = getMetaTitleDescription($Meta[0]->meta_description);
			if ($BrandDetail[0]->vpage_header != '')
				$this->PageData['meta_title'] = getMetaTitleDescription($BrandDetail[0]->vpage_header);
			if ($BrandDetail[0]->vmeta_keyword != '')
				$this->PageData['meta_keywords'] = getMetaTitleDescription($BrandDetail[0]->vmeta_keyword);
			if ($BrandDetail[0]->vmeta_description != '')
				$this->PageData['meta_description'] = getMetaTitleDescription($BrandDetail[0]->vmeta_description);

			if($BrandDetail[0]->template == 'Maxaroma-Edit' && $BrandDetail[0]->status == '1')
			{
				$BrandDetail[0]->brand_logo = $this->setImageName($BrandDetail[0]->brand_logo, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->top_sec_image = $this->setImageName($BrandDetail[0]->top_sec_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->top_sec_slider_image1 = $this->setImageName($BrandDetail[0]->top_sec_slider_image1, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->top_sec_slider_image2 = $this->setImageName($BrandDetail[0]->top_sec_slider_image2, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->top_sec_slider_image3 = $this->setImageName($BrandDetail[0]->top_sec_slider_image3, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));

				if(preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//',$BrandDetail[0]->brand_video))
				{
					if(preg_match('/(youtu\.be\/|youtube\.com\/(watch\?v=|embed\/|shorts\/))([^&\n?#]+)/',$BrandDetail[0]->brand_video,$matches))
					{
						if (!empty($matches[3]))
						{
							//$BrandDetail[0]->brand_video = "https://www.youtube.com/embed/" . $matches[3]."?autoplay=1";
							$BrandDetail[0]->brand_video = "https://www.youtube.com/embed/" . $matches[3];
						}
					}
					$BrandDetail[0]->brand_video_type = 'youtube';
				} else {
					$BrandDetail[0]->brand_video = $this->setImageName($BrandDetail[0]->brand_video, config('global.HOME_IMAGE_PATH').'videos/', config('global.HOME_IMAGE_URL').'videos/', "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
					$BrandDetail[0]->brand_video_type = 'default';
				}

				$BrandDetail[0]->list_sec_image = $this->setImageName($BrandDetail[0]->list_sec_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->list_sec_mob_image = $this->setImageName($BrandDetail[0]->list_sec_mob_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->history_sec_image = $this->setImageName($BrandDetail[0]->history_sec_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->bottom_sec_image = $this->setImageName($BrandDetail[0]->bottom_sec_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec5_image_1 = $this->setImageName($BrandDetail[0]->sec5_image_1, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec5_image_2 = $this->setImageName($BrandDetail[0]->sec5_image_2, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec8_logo = $this->setImageName($BrandDetail[0]->sec8_logo, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec8_image = $this->setImageName($BrandDetail[0]->sec8_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec9_image = $this->setImageName($BrandDetail[0]->sec9_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec9_mob_image = $this->setImageName($BrandDetail[0]->sec9_mob_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec11_image = $this->setImageName($BrandDetail[0]->sec11_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->sec12_image = $this->setImageName($BrandDetail[0]->sec12_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));

				$ProductSKUs = [];
				$ListSecSku = $MiddleSecSku = $BottomSecSku = $Sec9Sku = $Sec10Sku = $Sec11Sku = $Sec12Sku = [];
				$ListSecProducts = $MiddleSecProducts = $BottomSecProducts = $Sec9Products = $Sec10Products = $Sec11Products = $Sec12Products = [];
				if (!empty($BrandDetail[0]->list_sec_sku))
				{
					$ListSecSku = explode("#", $BrandDetail[0]->list_sec_sku);
					$ProductSKUs = array_merge($ProductSKUs,$ListSecSku);
				}
				if (!empty($BrandDetail[0]->middle_sec_sku))
				{
					$MiddleSecSku = explode("#", $BrandDetail[0]->middle_sec_sku);
					$ProductSKUs = array_merge($ProductSKUs,$MiddleSecSku);
				}
				if (!empty($BrandDetail[0]->bottom_sec_sku))
				{
					$BottomSecSku = explode("#", $BrandDetail[0]->bottom_sec_sku);
					$ProductSKUs = array_merge($ProductSKUs,$BottomSecSku);
				}
				if (!empty($BrandDetail[0]->sec9_skus))
				{
					$Sec9Sku = explode("#", $BrandDetail[0]->sec9_skus);
					$ProductSKUs = array_merge($ProductSKUs,$Sec9Sku);
				}
				if (!empty($BrandDetail[0]->sec10_skus))
				{
					$Sec10Sku = explode("#", $BrandDetail[0]->sec10_skus);
					$ProductSKUs = array_merge($ProductSKUs,$Sec10Sku);
				}
				if (!empty($BrandDetail[0]->sec11_skus))
				{
					$Sec11Sku = explode("#", $BrandDetail[0]->sec11_skus);
					$ProductSKUs = array_merge($ProductSKUs,$Sec11Sku);
				}
				if (!empty($BrandDetail[0]->sec12_skus))
				{
					$Sec12Sku = explode("#", $BrandDetail[0]->sec12_skus);
					$ProductSKUs = array_merge($ProductSKUs,$Sec12Sku);
				}

				$DefaultShowListSecProducts = 3;
				if(empty($BrandDetail[0]->list_sec_image))
				{
					$DefaultShowListSecProducts = 4;
				}
				$DefaultShowListSec9Products = 3;
				if(empty($BrandDetail[0]->sec9_image))
				{
					$DefaultShowListSec9Products = 4;
				}
				if(count($ProductSKUs) > 0)
				{
					$SetFilters['ProductSKUs'] = $ProductSKUs;
					$ProductsDetails = $this->GetProductsForMaxaromaTemplate($BrandDetail[0]->designerid,$ProductSKUs);
					$Products = $ProductsDetails['Products'];

					$TotalProducts = count($Products);
					$this->PageData['Products'] = $Products;
					$this->PageData['TotalProducts'] = $TotalProducts;

					foreach($Products as $product)
					{
						if(in_array($product->sku,$ListSecSku) && count($ListSecProducts) < $DefaultShowListSecProducts)
						{
							$ListSecduplicate = 0;
							foreach($ListSecProducts as $ListSecProduct)
							{
								if($ListSecProduct->sku == $product->sku)
								{
									$ListSecduplicate = 1;
									break;
								}
							}
							if($ListSecduplicate == 0)
							{
								$ListSecProducts[] = $product;
							}
						}
						if(in_array($product->sku,$MiddleSecSku) && count($MiddleSecProducts) < 12)
						{
							$MiddleSecduplicate = 0;
							foreach($MiddleSecProducts as $MiddleSecProduct)
							{
								if($MiddleSecProduct->sku == $product->sku)
								{
									$MiddleSecduplicate = 1;
									break;
								}
							}
							if($MiddleSecduplicate == 0)
							{
								$MiddleSecProducts[] = $product;
							}
						}

						if(in_array($product->sku,$BottomSecSku) && count($BottomSecProducts) < 1)
						{
							$BottomSecduplicate = 0;
							foreach($BottomSecProducts as $BottomSecProduct)
							{
								if($BottomSecProduct->sku == $product->sku)
								{
									$BottomSecduplicate = 1;
									break;
								}
							}
							if($BottomSecduplicate == 0)
							{
								$BottomSecProducts[] = $product;
							}
						}
						if(in_array($product->sku,$Sec9Sku) && count($Sec9Products) < $DefaultShowListSec9Products)
						{
							$Sec9duplicate = 0;
							foreach($Sec9Products as $Sec9Product)
							{
								if($Sec9Product->sku == $product->sku)
								{
									$Sec9duplicate = 1;
									break;
								}
							}
							if($Sec9duplicate == 0)
							{
								$Sec9Products[] = $product;
							}
						}
						if(in_array($product->sku,$Sec10Sku) && count($Sec10Products) < 12)
						{
							$Sec10duplicate = 0;
							foreach($Sec10Products as $Sec10Product)
							{
								if($Sec10Product->sku == $product->sku)
								{
									$Sec10duplicate = 1;
									break;
								}
							}
							if($Sec10duplicate == 0)
							{
								$Sec10Products[] = $product;
							}
						}
						if(in_array($product->sku,$Sec11Sku) && count($Sec11Products) < 2)
						{
							$Sec11duplicate = 0;
							foreach($Sec11Products as $Sec11Product)
							{
								if($Sec11Product->sku == $product->sku)
								{
									$Sec11duplicate = 1;
									break;
								}
							}
							if($Sec11duplicate == 0)
							{
								$Sec11Products[] = $product;
							}
						}
						if(in_array($product->sku,$Sec12Sku) && count($Sec12Products) < 2)
						{
							$Sec12duplicate = 0;
							foreach($Sec12Products as $Sec12Product)
							{
								if($Sec12Product->sku == $product->sku)
								{
									$Sec12duplicate = 1;
									break;
								}
							}
							if($Sec12duplicate == 0)
							{
								$Sec12Products[] = $product;
							}
						}
					}
				}

				// $Sec9Products = array_values(array_reduce($Sec9Products, function ($carry, $item) {
				// 	$carry[$item->products_id] = $item;
				// 	return $carry;
				// }, []));

				// $Sec10Products = array_values(array_reduce($Sec10Products, function ($carry, $item) {
				// 	$carry[$item->products_id] = $item;
				// 	return $carry;
				// }, []));

				// $Sec11Products = array_values(array_reduce($Sec11Products, function ($carry, $item) {
				// 	$carry[$item->products_id] = $item;
				// 	return $carry;
				// }, []));

				// $Sec12Products = array_values(array_reduce($Sec12Products, function ($carry, $item) {
				// 	$carry[$item->products_id] = $item;
				// 	return $carry;
				// }, []));

				if ($category_id > 0) {
					$BrandDetail[0]->vlink = config('global.SITE_URL') . stripcslashes(remove_special_chars($vmanufacture)) . "/p4u/cid-" . $category_id . "/mid-" . $BrandDetail[0]->imanufactureid . "/view";
				} else {
					$BrandDetail[0]->vlink = config('global.SITE_URL') . stripcslashes(remove_special_chars($vmanufacture)) . "/p4u/mid-" . $BrandDetail[0]->imanufactureid . "/view";
				}

				$this->PageData['ListSecProducts'] = $ListSecProducts;
				$this->PageData['MiddleSecProducts'] = $MiddleSecProducts;
				$this->PageData['BottomSecProducts'] = $BottomSecProducts;
				$this->PageData['Sec9Products'] = $Sec9Products;
				$this->PageData['Sec10Products'] = $Sec10Products;
				$this->PageData['Sec11Products'] = $Sec11Products;
				$this->PageData['Sec12Products'] = $Sec12Products;

				//Maxaroma-Edit Fields

				$AllSectionStatus = ['sec-1' => '1','sec-2' => '1','sec-3' => '1','sec-4' => '1','sec-5' => '1','sec-6' => '1','sec-7' => '1','sec-8' => '1','sec-9' => '1','sec-10' => '1','sec-11' => '1','sec-12' => '1'];
				if(!empty($BrandDetail[0]->section_status))
				{
					$ExpStatus = explode("|",$BrandDetail[0]->section_status);
					foreach($ExpStatus as $skey => $StatusSec)
					{
						$secStatus = 'sec-'.($skey + 1);
						$AllSectionStatus[$secStatus] = $StatusSec;
					}
				}
				$BrandDetail[0]->PageSectionStatus = $AllSectionStatus;

				//Maxaroma-Edit Fields
				$this->PageData['CSSFILES'] = ['brand-history.css','maxaroma-edit.css'];
				return view('brand.maxaroma-edit-brand')->with($this->PageData);
			} else {

				if ($category_id > 0) {
					$BrandDetail[0]->vlink = config('global.SITE_URL') . stripcslashes(remove_special_chars($vmanufacture)) . "/p4u/cid-" . $category_id . "/mid-" . $BrandDetail[0]->imanufactureid . "/view";
					if ($BrandDetail[0]->showhide == "No" || $BrandDetail[0]->header_image == "")
						return redirect(remove_special_chars($BrandDetail[0]->vlink));
				} else {
					$BrandDetail[0]->vlink = config('global.SITE_URL') . stripcslashes(remove_special_chars($vmanufacture)) . "/p4u/mid-" . $BrandDetail[0]->imanufactureid . "/view";
					if ($BrandDetail[0]->showhide == "No" || $BrandDetail[0]->header_image == "")
						return redirect($BrandDetail[0]->vlink);
				}

				$Submanufacture = Submanufacture::where('imanufactureid', '=', $imanufactureid)->where('status', '=', '1')->limit(6)->get();
				$this->PageData['Submanufacture'] = $Submanufacture;
				$BrandDetail[0]->header_image = $this->setImageName($BrandDetail[0]->header_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr.jpg", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				$BrandDetail[0]->mobile_header_image = $this->setImageName($BrandDetail[0]->mobile_header_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr.jpg", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));

				$BrandDetail[0]->overlap_brand_logo = $this->setImageName($BrandDetail[0]->overlap_brand_logo, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));

				$BrandDetail[0]->authorized_dealer_logo = $this->setImageName($BrandDetail[0]->authorized_dealer_logo, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "auth-logo.png", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
				if ($BrandDetail[0]->is_new_design == 'Yes' && $BrandDetail[0]->status == '1' && $BrandDetail[0]->showhide == 'Yes')
				{
					if ($BrandDetail[0]->history_images != '') {
						$historyImgs = explode("#", $BrandDetail[0]->history_images);
						for ($k = 0; $k < count($historyImgs); $k++) {
							$imgindx = $k + 1;
							$historyImgs[$k] = $this->setImageName($historyImgs[$k], config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-h" . $imgindx . ".jpg", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
						}
					} else {
						$historyImgs = array();
					}
					$this->PageData['historyImgs'] = $historyImgs;

					$SetFilters = array();
					if ($BrandDetail[0]->designerid > 0) {
						if ($BrandDetail[0]->exclude_sku != '') {
							$SetFilters['NotProductSKUs'] = explode("#", $BrandDetail[0]->exclude_sku);
						}
						$SetFilters['brands'] = array($BrandDetail[0]->designerid);
					} else {
						if ($BrandDetail[0]->sku != '') {
							$SetFilters['ProductSKUs'] = explode("#", $BrandDetail[0]->sku);
						}
					}
					$ProductsDetails = $this->GetProducts('BrandPage', '', 10, $SetFilters);
					$Products = $ProductsDetails['Products'];
					$TotalProducts = $ProductsDetails['TotalProducts'];
					$this->PageData['Products'] = $Products;
					$this->PageData['TotalProducts'] = $TotalProducts;

					$this->PageData['CSSFILES'] = ['brand-history.css'];
					$this->PageData['JSFILES'] = ['brand_history.js'];

					/** Schema - Start */
					$SchemaDetails = ['page_title' => $vmanufacture, 'url' => url()->current(),'BrandDetail' => $BrandDetail,'Products' => $Products];
					$this->PageData['SitePageSchema'] = PageSchema('brand',$SchemaDetails);
					/** Schema - End */

					return view('brand.brand_land')->with($this->PageData);
				} else {
					$this->PageData['CSSFILES'] = ['brand-lending.css'];
					return view('brand.brand')->with($this->PageData);
				}
			}
		}
	}

	public function GetBrandProducts(Request $request)
	{
		$imanufactureid = $request->brand_id;
		$BrandDetail = Manufacture::where('imanufactureid', '=', $imanufactureid)
			->where('status', '=', '1')->get();
		if ($BrandDetail && $BrandDetail->count() > 0) {
			if ($BrandDetail[0]->designerid > 0) {
				if ($BrandDetail[0]->exclude_sku != '') {
					$SetFilters['NotProductSKUs'] = explode("#", $BrandDetail[0]->exclude_sku);
				}
				$SetFilters['brands'] = array($BrandDetail[0]->designerid);
			} else {
				if ($BrandDetail[0]->sku != '') {
					$SetFilters['ProductSKUs'] = explode("#", $BrandDetail[0]->sku);
				}
			}
			$SetFilters['page'] = $request->page;
			$ProductsDetails = $this->GetProducts('BrandPage', '', 10, $SetFilters);
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$this->PageData['Products'] = $Products;
			$this->PageData['TotalProducts'] = $TotalProducts;

			$ProductHTML = view('brand.brandproductlist')->with($this->PageData)->render();
			return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML));
		}
	}

	public function Promotional(Request $request)
	{
		$this->PageData['CSSFILES'] = ['jquery-ui-slider.css', 'deal-list.css', 'custom.css'];
		$this->PageData['JSFILES'] = ['moment.min.js', 'jquery-ui-slider.min.js', 'promotional.js'];

		$PromotionalData = Promotional::all();
		//$PromotionalBannerData = PromotionalBanner::all();
		$PromotionalBannerData = PromotionalBanner::where('start_date','<=',date('Y-m-d'))->where('end_date','>=',date('Y-m-d'))->get();
		//dd($PromotionalBannerData);

		$SetFilters = $this->SetFilters($request);
		if ($PromotionalData && $PromotionalData->count() > 0) {
			$SetFilters['ProductSKUs'] = explode(",", $PromotionalData[0]->sku);
		}
		$this->PageData['SelBrand'] = '';
		if (isset($SetFilters['brands']) && count($SetFilters['brands']) > 0)
			$this->PageData['SelBrand'] = implode(",", $SetFilters['brands']);

		$this->PageData['SelSize'] = '';
		if (isset($SetFilters['size']) && count($SetFilters['size']) > 0)
			$this->PageData['SelSize'] = implode(",", $SetFilters['size']);

		$this->PageData['SelKey'] = '';
		if (isset($SetFilters['key']) && $SetFilters['key'] != '')
			$this->PageData['SelKey'] = $SetFilters['key'];

		$this->PageData['SelStock'] = '';
		if (isset($SetFilters['stock']) && in_array('In', $SetFilters['stock']))
			$this->PageData['SelStock'] = 'checked';

		$ProductsDetails = $this->GetProducts('Promotional', '', 12, $SetFilters);
		$Products = $ProductsDetails['Products'];
		$TotalProducts = $ProductsDetails['TotalProducts'];
		$this->PageData['PromotionalProds'] = $Products;
		$this->PageData['TotalProducts'] = $TotalProducts;
		$BrandList = [];
		$SizeList = [];
		if (count($ProductsDetails['LeftFilters']) > 0) {
			foreach ($ProductsDetails['LeftFilters'] as $DealFilter) {
				if (array_key_exists('Brands', $DealFilter)) {
					$BrandList = $DealFilter['Brands']['Data'];
				}
				if (array_key_exists('Size', $DealFilter)) {
					$SizeList = $DealFilter['Size']['Data'];
				}
			}
		}
		$this->PageData['PromotionalData'] = $PromotionalData;
		$this->PageData['PromotionalBannerData'] = $PromotionalBannerData;
		//dd($PromotionalData);

		$this->PageData['BrandList'] = $BrandList;
		$this->PageData['SizeList'] = $SizeList;
		$this->PageData['MinPrice'] = count($Products) > 0 ? min(array_column($Products, 'product_price')) : 0;
		$this->PageData['MaxPrice'] = count($Products) > 0 ? max(array_column($Products, 'product_price')) : 0;
		return view('product.promotional')->with($this->PageData);
	}
	public function GetPromotional(Request $request)
	{
		$Filters = json_decode($request->filters, true);

		$PromotionalData = Promotional::all();
		$PromotionalBannerData = PromotionalBanner::where('start_date','<=',date('Y-m-d'))->where('end_date','>=',date('Y-m-d'))->get();

		if ($PromotionalData && $PromotionalData->count() > 0) {
			$Filters['ProductSKUs'] = explode(",", $PromotionalData[0]->sku);
		}
		$Filters['page'] = $request->page;
		$ProductsDetails = $this->GetProducts('Promotional', '', 12, $Filters);
		$Products = $ProductsDetails['Products'];
		$TotalProducts = $ProductsDetails['TotalProducts'];
		$this->PageData['Products'] = $Products;
		$this->PageData['TotalProducts'] = $TotalProducts;

		$this->PageData['PromotionalData'] = $PromotionalData;
		$this->PageData['PromotionalBannerData'] = $PromotionalBannerData;

		$BrandList = [];
		$SizeList = [];
		if (count($ProductsDetails['LeftFilters']) > 0) {
			foreach ($ProductsDetails['LeftFilters'] as $PromotionalFilter) {
				if (array_key_exists('Brands', $PromotionalFilter)) {
					$BrandList = $PromotionalFilter['Brands']['Data'];
				}
				if (array_key_exists('Size', $PromotionalFilter)) {
					$SizeList = $PromotionalFilter['Size']['Data'];
				}
			}
		}
		$ProductHTML = view('product.otherlist')->with($this->PageData)->render();
		return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML, 'BrandList' => $BrandList, 'SizeList' => $SizeList));
	}

	public function setImageName($image, $imgpath, $imgURL, $defaultImg="", $defaultPath="", $defaultURL="")
	{
		$imageName="";
		if (!empty($image) && file_exists($imgpath . stripslashes($image))) {
			$ver = filemtime($imgpath . stripslashes($image));
			$imageName = $imgURL . $image . '?ver=' . $ver;
		} /*else {
			$ver = @filemtime($defaultPath . $defaultImg);
			$imageName = $defaultURL . $defaultImg . '?ver=' . $ver;
		}*/
		return $imageName;
	}
	public function BrandHistory(Request $request)
	{
		$id	= $request['brand_id'];
		$res = BrandLandling::select('*')
			->where('id', '=', (int) $id)
			->where('status', '=', '1')
			->first();
		if ($res && $res->count() > 0) {
			$res->banner_image = $this->setImageName($res->banner_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr.jpg", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));
			$res->mob_image = $this->setImageName($res->mobile_banner_image, config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'), "brand-hbnr.jpg", config('global.MANUFACTUR_IMAGE_PATH'), config('global.MANUFACTUR_IMAGE_URL'));

			$relatedItems = $res->sku;
			if ($relatedItems && $relatedItems != '') {
				if (strstr($relatedItems, ',')) {
					$relatedItems = str_replace(",", "#", $relatedItems);
				}

				$relatedItems = rtrim($relatedItems,"#");

				$relatedItemValues = explode("#", $relatedItems);
				$SetFilters['ProductSKUs'] = $relatedItemValues;
				$ProductsDetails = $this->GetProducts('', '', 15, $SetFilters);
				$Products = $ProductsDetails['Products'];
				$TotalProducts = $ProductsDetails['TotalProducts'];
				$this->PageData['Products'] = $Products;
				$this->PageData['TotalProducts'] = $TotalProducts;
				// $ProductsDetails = $this->GetSliderProducts($res->sku, '', '');
				// dd($ProductsDetails);
			} else {
				$ProductsDetails = [];
			}
			$this->PageData['brandpro_arr'] = $ProductsDetails;
			$this->PageData['meta_title'] = getMetaTitleDescription(config('Settings.SITE_TITLE') . " :: " . $res->title);
			// } else {
			// }

			$res->is_brand_show = 'Yes';

			if ($res->youtube_link != '') {
				$youtubeLinks = explode("#", $res->youtube_link);
			} else {
				$youtubeLinks = array();
			}
			$this->PageData['cntYoutubeLink'] = count($youtubeLinks);
			$this->PageData['youtubeLinks'] = $youtubeLinks;
			$this->PageData['res'] = $res;
			// dd($res);

			$this->PageData['CSSFILES'] = ['brand-history.css'];
			$this->PageData['JSFILES'] = ['brandhistorybundle.js'];
			$this->PageData['CanonicalURL'] = url()->current();
			return view('brand.brandhistory')->with($this->PageData);
		} else {
			return view('errors.404');
		}
	}
	public function GetBrandHistoryBundleProducts(Request $request)
	{
		$brand_id = $request->brand_id;
		$page = $request->page;
		$res = BrandLandling::select('*')
			->where('id', '=', (int) $brand_id)
			->where('status', '=', '1')
			->first();
		if ($res && $res->count() > 0) {
			if ($res->sku != '') {
				$res->sku = rtrim($res->sku,"#");
				$SetFilters['ProductSKUs'] = explode("#", $res->sku);
			}
			$relatedItems = $res->sku;
			if ($relatedItems && $relatedItems != '') {
				if (strstr($relatedItems, ',')) {
					$relatedItems = str_replace(",", "#", $relatedItems);
				}

				$relatedItems = rtrim($relatedItems,"#");

				$relatedItemValues = explode("#", $relatedItems);
				$SetFilters['ProductSKUs'] = $relatedItemValues;
				$SetFilters['page'] = $request->page;
				$ProductsDetails = $this->GetProducts('BrandHistory', '', 15, $SetFilters);
				// dd($ProductsDetails);
				$Products = $ProductsDetails['Products'];
				//$TotalProducts = count($ProductsDetails['Products']);
				$TotalProducts = $ProductsDetails['TotalProducts'];
				$this->PageData['Products'] = $Products;
				$this->PageData['TotalProducts'] = $TotalProducts;
			}
			$ProductHTML = view('brand.brandhistorygrid')->with($this->PageData)->render();
			return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML));
		}
	}
	public function MaxaromaBundles(Request $request)
	{
		$mainBrandLanding = MainbrandLanding::get();
		$this->PageData['mainBrandLanding']  = "";
		if($mainBrandLanding){

			if (file_exists(config('global.CAT_IMAGE_PATH') . $mainBrandLanding[0]['mega_menu_image']) && $mainBrandLanding[0]['mega_menu_image'] != '') {
				$newimageVal = config('global.CAT_IMAGE_PATH')  . stripslashes($mainBrandLanding[0]['mega_menu_image']);
				$verP = filemtime($newimageVal);
				$mainBrandLanding[0]['mega_menu_image'] = config('global.CAT_IMAGE_URL') . $mainBrandLanding[0]['mega_menu_image'] . "?ver=" . $verP;
			}else{
				//$mainBrandLanding[0]['mega_menu_image'] = config('global.CAT_IMAGE_URL').$mainBrandLanding[0]['mega_menu_image'];
				$mainBrandLanding[0]['mega_menu_image'] = '';
			}

			$mainBrandLanding[0]['title'] = strtolower($mainBrandLanding[0]['title']);
			$this->PageData['mainBrandLanding'] = $mainBrandLanding;

		}
		$result = BrandLandling::select('id', 'bundle_banner_image','banner_link','title')
			->where('status', '=', '1')
			->orderBy('position')
			->get();
		$brandlanding = array();
		if ($result && $result->count() > 0) {
			$i = 0;
			foreach ($result as $value) {
				$brandlanding[$i]['id'] = $value['id'];
				$brandlanding[$i]['banner_link'] = $value['banner_link'];
				$brandlanding[$i]['title'] = $value['title'];

				if (file_exists(config('global.MANUFACTUR_IMAGE_PATH') . $value['bundle_banner_image']) && $value['bundle_banner_image'] != '') {
					$newimageVal = config('global.MANUFACTUR_IMAGE_PATH')  . stripslashes($value['bundle_banner_image']);
					$verP = filemtime($newimageVal);
					$brandlanding[$i]['banner_image'] = config('global.MANUFACTUR_IMAGE_URL') . $value['bundle_banner_image'] . "?ver=" . $verP;
				}else{
					$brandlanding[$i]['banner_image'] = config('global.MANUFACTUR_IMAGE_URL') . $value['bundle_banner_image'];
				}

				$i++;
			}
			$this->PageData['brandlanding'] = $brandlanding;
		}

		$this->PageData['CSSFILES'] = ['influencer-bundel.css'];
		$this->PageData['meta_title'] = getMetaTitleDescription(config('global.META_TITLE').' :: Discovery Bundles');
		return view('brand.maxaroma_bundles')->with($this->PageData);
	}

	public function Offers(Request $request)
	{
		$this->PageData['CSSFILES'] = ['jquery-ui-slider.css','custom.css'];
		if($request->has('ver') && $request->ver == '2026')
			$this->PageData['CSSFILES'][]='offers26.css';
		$this->PageData['JSFILES'] = ['moment.min.js', 'jquery-ui-slider.min.js'];

		$this->PageData['IsExclusiveCustomers'] = 'No';

		if((Session::get('sess_icustomerid') == '' || Session::get('sess_icustomerid') <= 0) && $request->path() == 'exclusive-offers.html'){
			return redirect('/login.html');
		}

		$OrdResultQuery = Order::select('orders_id', 'orders_no', 'sub_total', 'ship_status','order_total', 'refund_amount', 'pay_status', 'status', 'payment_type', 'use_credit_limit', 'payment_method', 'phoneorder_payby', 'route_shipping_insurance_charge','bill_first_name', 'bill_last_name','bill_email', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime,DATEDIFF(now(), order_datetime) as days"))
							->where('customer_id', '=', Session::get('sess_icustomerid'))
							->where('pay_status', '=', 'Paid')
							->where('status', '!=', 'Declined')
							->where('order_total', '>', 200)
							->having('days', '<=', 60)
							->get();

		if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0 && count($OrdResultQuery) > 0){
			$this->PageData['IsExclusiveCustomers'] = 'Yes';
		}else{
			$this->PageData['IsExclusiveCustomers'] = 'No';
		}

		/*if(!Session::has('sess_icustomerid') || Session::has('sess_icustomerid') == '' || count($OrdResultQuery) <= 0){
			return redirect('/offers.html', 301);
			exit;
		}*/

		if (!Cache::has('SiteOfferCache')) {

			$siteOffer = SiteOffers::where('status', '=', '1')->where('expiry_date', '>=', date('Y-m-d'))->get();
			Cache::put('SiteOfferCache', $siteOffer);
		}
		else
		{
			$siteOffer = Cache::get('SiteOfferCache');
		}
		//dd($siteOffer);

		$StrOfferStr  = "";
		$k =0 ;
		$GAoffer4 = '';
		foreach($siteOffer as $key => $offerbanner){
			if(!empty($offerbanner->offer_banner) && file_exists(config('global.SITE_OFFER_PATH').$offerbanner->offer_banner)){
				$newimageVal = config('global.SITE_OFFER_PATH')  . stripslashes($offerbanner->offer_banner);
				$verP = filemtime($newimageVal);
				$siteOffer[$key]->offer_banner = config('global.SITE_OFFER_URL') . $offerbanner->offer_banner . "?ver=" . $verP;
			} else {
				$siteOffer[$key]->offer_banner = 'https://via.placeholder.com/400x600/eee';
			}

			  $StrOfferStr .="{
								item_id:'".$siteOffer[$key]->site_offers_id."',
								item_name:'".$siteOffer[$key]->offer_title."',
								affiliation: 'Maxaroma',
								index: ".$k."
							},";
			$k++;

		}
		if(isset($StrOfferStr) && $StrOfferStr!='')
		{
			$StrOfferStr = substr($StrOfferStr,0,-1);
			$GAoffer4 .="dataLayer.push({ ecommerce: null });
					dataLayer.push({event: 'view_promotion',
					ecommerce: {";
			$GAoffer4 .="items: [
									".$StrOfferStr."
								   ]}
								   });";

		}
		//echo $GAoffer4; exit;
		$this->PageData['siteOffer'] = $siteOffer;
		$this->PageData['GA4'] = $GAoffer4;

		if($this->PageData['IsExclusiveCustomers'] == 'Yes' && $request->path() == 'exclusive-offers.html'){
			return view('product.exclusive-offers')->with($this->PageData);
		}
		else if($this->PageData['IsExclusiveCustomers'] == 'Yes' && $request->path() == 'offers.html'){
			if($request->has('ver') && $request->ver == '2026')
				return view('product.offers26')->with($this->PageData);
			else
				return view('product.offers')->with($this->PageData);
		}else{
			if($request->has('ver') && $request->ver == '2026')
				return view('product.offers26')->with($this->PageData);
			else
				return view('product.offers')->with($this->PageData);
		}
	}
}
