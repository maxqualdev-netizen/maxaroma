<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MetaInfo;
use App\Models\LandingPages;
use App\Models\LandingPagesPreview;
use App\Models\LandingPagesData;
use App\Models\LandingPagesDataPreview;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use DB;
use Session;
use Cache;

class LandingpageController extends Controller
{
	use CommonTrait;
	use VendorTrait;
	use CartTrait;
	protected $PageData = [];

	public function __construct()
	{
		if (!Cache::has('NRMetaInfo')) {
			$PageType = 'NR';
			$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
			Cache::put('NRMetaInfo', $MetaInfo);
		} else {
			$MetaInfo = Cache::get('NRMetaInfo');
		}
		// $PageType = 'NR';
		// $MetaInfo = MetaInfo::where('type','=',$PageType)->get();
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = getMetaTitleDescription($MetaInfo[0]->meta_title); //$MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = getMetaTitleDescription($MetaInfo[0]->meta_description); //$MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = getMetaTitleDescription($MetaInfo[0]->meta_keywords); //$MetaInfo[0]->meta_keywords;
		}
	}

	public function GiftGuide(Request $request)
	{
		$this->PageData['CSSFILES'] = ['slick.css','home.css','landing-page.css','brand-history.css'];
		$this->PageData['JSFILES'] = ['slick.js','landing-pages.js'];
		//$this->PageData['SliderProducts'] = ['UP3508441001114','UP3760035450184'];
		$LandingPages = LandingPages::with('PageData')->where('status','=','1')->where('page_type','=','gift-guide')->orderBy('section_rank')->get();
		$this->PageData['LandingPages'] = [];
		//$BestSellers = $this->ProductSlider('TOP SELLERS','Home','');

		$SliderProducts = [];
		$TotalProducts = 0;
		$this->PageData['BestSellers'] = [];

		if($LandingPages->count() > 0)
		{
			foreach($LandingPages as $LPage)
			{
				if(isset($LPage->section_name))
				{
					$LPage->section_name = stripslashes($LPage->section_name);
				}

				if($LPage->section_id == 1 || $LPage->section_id == 7)
				{
					if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->PageData[0]->banner)) and !empty($LPage->PageData[0]->banner)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->PageData[0]->banner);
						$verP = filemtime($newimageVal);
						$LPage->PageData[0]->banner = config('global.SITE_URL').'/images/landing/'.$LPage->PageData[0]->banner."?ver=" . $verP;
					}else{
						$LPage->PageData[0]->banner = '';
					}
				}
				if($LPage->section_id == 2 || $LPage->section_id == 3 || $LPage->section_id == 5 || $LPage->section_id == 6 || $LPage->section_id == 8)
				{
					foreach($LPage->PageData as $key => $PData){
						if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($PData->banner)) and !empty($PData->banner)) {
							$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($PData->banner);
							$verP = filemtime($newimageVal);
							$PData->banner = config('global.SITE_URL').'/images/landing/'.$PData->banner."?ver=" . $verP;
						}else{
							$PData->banner = '';
						}
					}
				}
				if($LPage->section_id == 4)
				{

					/*if($LPage->PageData[0]->sku != '');
						$SliderProducts = $this->GetSliderProducts($LPage->PageData[0]->sku);*/

					if($LPage->PageData[0]->sku != ''){
						$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku);
					}
					else if($LPage->PageData[0]->designerid != '' && $LPage->PageData[0]->designerid > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'',$LPage->PageData[0]->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
								if($LPage->PageData[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id);
								}
							}
						}
					}
					else if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku);
							}
						}
					}

					$Products = $SliderProducts['Products'];
					$TotalProducts = $SliderProducts['TotalProducts'];
					$this->PageData['TotalProducts'] = $TotalProducts;

					if($TotalProducts > 0)
					{
						/*if($SliderProducts > 15)
						{
							$SliderProducts = array_slice($SliderProducts,0,15);
						}*/
						$this->PageData['BestSellers'] = $Products;
						$this->PageData['BestSellersAttr'] = [
							'Title' => '',
							'Slider' => 'home-new-sl',
							'Page' => 'Landing',
							'SeeMore' => '',
						];
					}
				}
				if($LPage->section_id == 9)
				{
					$LPage->PageData[0]->banner_desc = stripslashes($LPage->PageData[0]->banner_desc);
				}
			}
			$this->PageData['LandingPages'] = $LandingPages;
		}

		return view('staticpages.landing-page')->with($this->PageData);
	}

	public function GetGiftGuideProducts(Request $request)
	{

		$LandingPages = LandingPages::with('PageData')->where('status','=','1')->where('page_type','=','gift-guide')->orderBy('section_rank')->get();

		$SliderProducts = [];
		$Filters = [];
		$this->PageData['BestSellers'] = [];
		$Filters['page'] = $request->page;
		$Filters['limit'] = 10;
		//GetProductsWithParms($ProductString='',$ManufactureID='',$CategoryID='',$ExcludeProductString='',$Flag='',$limit=15,$Filters=[])
		//$Filters['page']

		if($LandingPages->count() > 0)
		{
			foreach($LandingPages as $LPage)
			{
				if($LPage->section_id == 4)
				{
					if($LPage->PageData[0]->sku != ''){
						$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku,'','','','','',$Filters);
					}
					else if($LPage->PageData[0]->designerid != '' && $LPage->PageData[0]->designerid > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'',$LPage->PageData[0]->exclude_sku,'','',$Filters);
						}else{
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'','','','',$Filters);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
								if($LPage->PageData[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku,'','',$Filters);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,'','','',$Filters);
								}
							}
						}
					}
					else if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku,'','',$Filters);
						}else{
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,'','','',$Filters);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku,'','','','','',$Filters);
							}
						}
					}

					//$TotalProducts = count($SliderProducts);
				}
			}

			$Products = $SliderProducts['Products'];
			$TotalProducts = $SliderProducts['TotalProducts'];
			$this->PageData['BestSellers'] = $Products;
			$this->PageData['TotalProducts'] = $TotalProducts;

			$ProductHTML = view('staticpages.landingpageproductlist')->with($this->PageData)->render();
			return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML));
		}
	}

	public function GiftGuidePreview(Request $request)
	{
		$this->PageData['CSSFILES'] = ['slick.css','home.css','landing-page.css','brand-history.css'];
		$this->PageData['JSFILES'] = ['slick.js','landing-pages-preview.js'];
		//$this->PageData['SliderProducts'] = ['UP3508441001114','UP3760035450184'];
		$LandingPages = LandingPagesPreview::with('PageData')->where('status','=','1')->where('page_type','=','gift-guide')->orderBy('section_rank')->get();
		$this->PageData['LandingPages'] = [];
		//$BestSellers = $this->ProductSlider('TOP SELLERS','Home','');

		$SliderProducts = [];
		$TotalProducts = 0;
		$this->PageData['BestSellers'] = [];

		if($LandingPages->count() > 0)
		{
			foreach($LandingPages as $LPage)
			{
				if($LPage->section_id == 1 || $LPage->section_id == 7)
				{
					if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->PageData[0]->banner)) and !empty($LPage->PageData[0]->banner)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->PageData[0]->banner);
						$verP = filemtime($newimageVal);
						$LPage->PageData[0]->banner = config('global.SITE_URL').'/images/landing/'.$LPage->PageData[0]->banner."?ver=" . $verP;
					}else{
						$LPage->PageData[0]->banner = '';
					}
				}
				if($LPage->section_id == 2 || $LPage->section_id == 3 || $LPage->section_id == 5 || $LPage->section_id == 6 || $LPage->section_id == 8)
				{
					foreach($LPage->PageData as $key => $PData){
						if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($PData->banner)) and !empty($PData->banner)) {
							$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($PData->banner);
							$verP = filemtime($newimageVal);
							$PData->banner = config('global.SITE_URL').'/images/landing/'.$PData->banner."?ver=" . $verP;
						}else{
							$PData->banner = '';
						}
					}
				}
				if($LPage->section_id == 4)
				{

					/*if($LPage->PageData[0]->sku != '');
						$SliderProducts = $this->GetSliderProducts($LPage->PageData[0]->sku);*/

					if($LPage->PageData[0]->sku != ''){
						$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku);
					}
					else if($LPage->PageData[0]->designerid != '' && $LPage->PageData[0]->designerid > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'',$LPage->PageData[0]->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
								if($LPage->PageData[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id);
								}
							}
						}
					}
					else if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku);
							}
						}
					}

					$Products = $SliderProducts['Products'];
					$TotalProducts = $SliderProducts['TotalProducts'];
					$this->PageData['TotalProducts'] = $TotalProducts;

					if($TotalProducts > 0)
					{
						/*if($SliderProducts > 15)
						{
							$SliderProducts = array_slice($SliderProducts,0,15);
						}*/
						$this->PageData['BestSellers'] = $Products;
						$this->PageData['BestSellersAttr'] = [
							'Title' => '',
							'Slider' => 'home-new-sl',
							'Page' => 'Landing',
							'SeeMore' => '',
						];
					}
				}
				if($LPage->section_id == 9)
				{
					$LPage->PageData[0]->banner_desc = stripslashes($LPage->PageData[0]->banner_desc);
				}
			}
			$this->PageData['LandingPages'] = $LandingPages;
		}

		return view('staticpages.landing-page-preview')->with($this->PageData);
	}

	public function GetGiftGuideProductsPreview(Request $request)
	{

		$LandingPages = LandingPagesPreview::with('PageData')->where('status','=','1')->where('page_type','=','gift-guide')->orderBy('section_rank')->get();

		$SliderProducts = [];
		$Filters = [];
		$this->PageData['BestSellers'] = [];
		$Filters['page'] = $request->page;
		$Filters['limit'] = 10;
		//GetProductsWithParms($ProductString='',$ManufactureID='',$CategoryID='',$ExcludeProductString='',$Flag='',$limit=15,$Filters=[])
		//$Filters['page']

		if($LandingPages->count() > 0)
		{
			foreach($LandingPages as $LPage)
			{
				if($LPage->section_id == 4)
				{
					if($LPage->PageData[0]->sku != ''){
						$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku,'','','','','',$Filters);
					}
					else if($LPage->PageData[0]->designerid != '' && $LPage->PageData[0]->designerid > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'',$LPage->PageData[0]->exclude_sku,'','',$Filters);
						}else{
							$SliderProducts = $this->GetProductsWithParms('',$LPage->PageData[0]->designerid,'','','','',$Filters);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
								if($LPage->PageData[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku,'','',$Filters);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,'','','',$Filters);
								}
							}
						}
					}
					else if($LPage->PageData[0]->category_id != '' && $LPage->PageData[0]->category_id > 0){
						if($LPage->PageData[0]->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,$LPage->PageData[0]->exclude_sku,'','',$Filters);
						}else{
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->PageData[0]->category_id,'','','',$Filters);
						}

						if(count($SliderProducts) <= 0){
							if($LPage->PageData[0]->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($LPage->PageData[0]->sku,'','','','','',$Filters);
							}
						}
					}

					//$TotalProducts = count($SliderProducts);
				}
			}

			$Products = $SliderProducts['Products'];
			$TotalProducts = $SliderProducts['TotalProducts'];
			$this->PageData['BestSellers'] = $Products;
			$this->PageData['TotalProducts'] = $TotalProducts;

			$ProductHTML = view('staticpages.landingpageproductlist')->with($this->PageData)->render();
			return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML));
		}
	}

	public function FragranceLandingPage(){

		$AllCatInfo = config('CATEGORY_INFO');

		$Bredcrum = $AllCatInfo['CatForProd'][1]['subcatbredcrum'];

		$this->PageData['Bredcrum'] = $Bredcrum;
		$this->PageData['PageTitle'] = $Bredcrum;

		$this->PageData['CSSFILES'] = ['slick.css','listing.css','listpage.css','custom.css'];
		$this->PageData['JSFILES'] = ['slick.js','maincategorylanding.js','jquery.mCustomScrollbar.concat.min.js'];

		/*$pageData = LandingPagesData::whereHas('landingPage', function($q){
			$q->where(['status' => '1', 'page_type' => 'fragrance']);
		})->orderBy('rank')->get();*/

		if (!Cache::has('FragranceLandingPage')) {
			$pageData = LandingPagesData::whereHas('landingPage', function($q){
				$q->where(['status' => '1', 'page_type' => 'fragrance']);
			})->orderBy('rank')->get();
			Cache::put('FragranceLandingPage', $pageData);
		} else {
			$pageData = Cache::get('FragranceLandingPage');
		}

		if (!Cache::has('FragranceLandingPageMainBanner')) {
			$MainBanner = $pageData->where('section_id', 10)->where('data_id', 27)->first();
			Cache::put('FragranceLandingPageMainBanner', $MainBanner);
		} else {
			$MainBanner = Cache::get('FragranceLandingPageMainBanner');
		}

		$this->PageData['MainBanner'] = $MainBanner;	//$pageData->where('section_id', 10)->where('data_id', 27)->first();
		//if(isset($this->PageData['MainBanner']) && $this->PageData['MainBanner']->count() > 0){
		if(isset($this->PageData['MainBanner']) && !empty($this->PageData['MainBanner']) > 0){
			if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($this->PageData['MainBanner']->banner)) and !empty($this->PageData['MainBanner']->banner)) {
				$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($this->PageData['MainBanner']->banner);
				$verP = filemtime($newimageVal);
				$this->PageData['MainBanner']->banner = config('global.SITE_URL').'/images/landing/'.$this->PageData['MainBanner']->banner."?ver=" . $verP;
			}else{
				$this->PageData['MainBanner']->banner = '';
			}
		}

		$this->PageData['MainBannerLinks'] =	$pageData->where('section_id', 10)->where('data_id','<>', 27)->take(4);
		//$this->PageData['MainBannerLinks'] =	$pageData->where('section_id', 10)->where('data_id','<>', 27)->orderBy('rank')->get();

		$this->PageData['RightBanners'] = $pageData->whereIn('section_id', [11,12])->take(2);
		if(isset($this->PageData['RightBanners']) && $this->PageData['RightBanners']->count() > 0){
			foreach($this->PageData['RightBanners'] as $LPage){
				if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->banner)) and !empty($LPage->banner)) {
					$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage->banner);
					$verP = filemtime($newimageVal);
					$LPage->banner = config('global.SITE_URL').'/images/landing/'.$LPage->banner."?ver=" . $verP;
				}else{
					$LPage->banner = '';
				}
			}
		}

		$this->PageData['BottomSliders'] = $pageData->where('section_id', 13)->where('status','1');
		//$this->PageData['BottomSliders'] = $pageData->where('section_id', 13)->where('status','1')->orderBy('rank')->get();
		if(isset($this->PageData['BottomSliders']) && $this->PageData['BottomSliders']->count() > 0){
			foreach($this->PageData['BottomSliders'] as $LPage11){
				if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage11->banner)) and !empty($LPage11->banner)) {
					$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($LPage11->banner);
					$verP = filemtime($newimageVal);
					$LPage11->banner = config('global.SITE_URL').'/images/landing/'.$LPage11->banner."?ver=" . $verP;
				}else{
					$LPage11->banner = '';
				}
			}
		}
		$this->PageData['CanonicalURL'] = config('app.url') . request()->path();

		/** Schema - Start */
		$pageBanners = [
			'MainBanner' => $this->PageData['MainBanner'],
			'MainBannerLinks' => $this->PageData['MainBannerLinks'],
			'RightBanners' => $this->PageData['RightBanners'],
			'BottomSliders' => $this->PageData['BottomSliders']
		];
		$SchemaDetails = ['page_title' => $Bredcrum, 'url' => url()->current(),'pageBanners' => $pageBanners];
		$this->PageData['SitePageSchema'] = PageSchema('category',$SchemaDetails);
		/** Schema - End */

		return view('staticpages.frangrance-landing-page')->with($this->PageData);
	}

	public function MaxaromaEdit(Request $request)
	{
		$this->PageData['CSSFILES'] = ['slick.css','home.css','landing-page.css','brand-history.css','maxaroma-edit.css'];
		$this->PageData['JSFILES'] = ['slick.js','landing-pages.js'];
		//$Sections = DB::table("pu_maxaroma_edit")->where('status',1)->orderBy('rank')->get();

		if(!Cache::has('MaxaromaEditLandingPage'))
		{
			$SectionDetails = DB::table("pu_maxaroma_edit_data as d")
			->join("pu_maxaroma_edit as s", "s.id", "=","d.section_id")
			->where('s.status','1')
			->orderBy('d.section_id')->get();
			Cache::put('MaxaromaEditLandingPage', $SectionDetails);
		} else {
			$SectionDetails = Cache::get('MaxaromaEditLandingPage');
		}

		$AllSections = [];
		if($SectionDetails->count() > 0)
		{
			foreach($SectionDetails as $LPage)
			{
				if($LPage->section_id == 1)
				{
					if(!empty($LPage->logo) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->logo)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->logo);
						$verP = filemtime($newimageVal);
						$LPage->logo = config('global.SITE_URL').'/images/'.$LPage->logo."?ver=" . $verP;
					}else{
						$LPage->logo = '';
					}
					if(!empty($LPage->header_image) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image);
						$verP = filemtime($newimageVal);
						$LPage->header_image = config('global.SITE_URL').'/images/'.$LPage->header_image."?ver=" . $verP;
					}else{
						$LPage->header_image = '';
					}
					$AllSections[$LPage->section_id]['logo'] = $LPage->logo;
					$AllSections[$LPage->section_id]['header_image'] = $LPage->header_image;
					$AllSections[$LPage->section_id]['short_description'] = $LPage->short_description;
				}
				if($LPage->section_id == 3)
				{
					$AllSections[$LPage->section_id]['sec_label'] = $LPage->sec_label;
					$AllSections[$LPage->section_id]['introduction'] = $LPage->introduction;
					$AllSections[$LPage->section_id]['title'] = $LPage->title;
					$AllSections[$LPage->section_id]['link'] = $LPage->link;
					if(!empty($LPage->header_image) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image);
						$verP = filemtime($newimageVal);
						$LPage->header_image = config('global.SITE_URL').'/images/'.$LPage->header_image."?ver=" . $verP;
					}else{
						$LPage->header_image = '';
					}
					$AllSections[$LPage->section_id]['header_image'] = $LPage->header_image;

					if(!empty($LPage->header_image_mobile) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image_mobile)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image_mobile);
						$verP = filemtime($newimageVal);
						$LPage->header_image_mobile = config('global.SITE_URL').'/images/'.$LPage->header_image_mobile."?ver=" . $verP;
					}else{
						$LPage->header_image_mobile = '';
					}
					$AllSections[$LPage->section_id]['header_image_mobile'] = $LPage->header_image_mobile;
				}
				if($LPage->section_id == 5)
				{
					$AllSections[$LPage->section_id]['title'] = $LPage->title;
					$AllSections[$LPage->section_id]['link'] = $LPage->link;
					if(!empty($LPage->header_image) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image);
						$verP = filemtime($newimageVal);
						$LPage->header_image = config('global.SITE_URL').'/images/'.$LPage->header_image."?ver=" . $verP;
					}else{
						$LPage->header_image = '';
					}
					$AllSections[$LPage->section_id]['header_image'] = $LPage->header_image;

					if(!empty($LPage->header_image_mobile) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image_mobile)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->header_image_mobile);
						$verP = filemtime($newimageVal);
						$LPage->header_image_mobile = config('global.SITE_URL').'/images/'.$LPage->header_image_mobile."?ver=" . $verP;
					}else{
						$LPage->header_image_mobile = '';
					}
					$AllSections[$LPage->section_id]['header_image_mobile'] = $LPage->header_image_mobile;
				}
				if($LPage->section_id == 2 || $LPage->section_id == 4 || $LPage->section_id == 6)
				{
					if(!empty($LPage->brand_image) && file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->brand_image)))
					{
						$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($LPage->brand_image);
						$verP = filemtime($newimageVal);
						$LPage->brand_image = config('global.SITE_URL').'/images/'.$LPage->brand_image."?ver=" . $verP;
					}else{
						$LPage->brand_image = '';
					}
					$AllSections[$LPage->section_id][]=[
						'brand_title' => $LPage->brand_title,
						'brand_image' => $LPage->brand_image,
						'link' => $LPage->link,
					];
				}
			}
		}

		$this->PageData['AllSections'] = $AllSections;
		return view('staticpages.maxaroma-edit')->with($this->PageData);
		/*
		if($request->has('1'))
		{
			return view('staticpages.maxaroma-edit-bk.maxaroma-edit')->with($this->PageData);
		} else {
			return view('staticpages.maxaroma-edit')->with($this->PageData);
		}
		*/
	}

	public function MaxaromaEditPreview(Request $request)
	{
		if(!Cache::has('MaxaromaEditLandingPagePreview'))
		{
			return redirect('/');
		}

		$this->PageData['CSSFILES'] = ['slick.css','home.css','landing-page.css','brand-history.css','maxaroma-edit.css'];
		$this->PageData['JSFILES'] = ['slick.js','landing-pages.js'];

		$SectionDetails = Cache::get('MaxaromaEditLandingPagePreview');

		$AllSections = [];
		if(isset($SectionDetails) && count($SectionDetails))
		{
			foreach($SectionDetails as $key => $LPage)
			{
				$section_id = $key;
				if($section_id == '1')
				{
					$AllSections[$section_id]['logo'] = $LPage['logo'];
					$AllSections[$section_id]['header_image'] = $LPage['header_image'];
					$AllSections[$section_id]['short_description'] = $LPage['short_description'];
				}
				if($section_id == '3')
				{
					$AllSections[$section_id]['introduction'] = $LPage['introduction'];
					$AllSections[$section_id]['title'] = $LPage['title'];
					$AllSections[$section_id]['link'] = $LPage['link'];
					$AllSections[$section_id]['header_image'] = $LPage['header_image'];
					$AllSections[$section_id]['header_image_mobile'] = $LPage['header_image_mobile'];
				}
				if($section_id == '2' || $section_id == '4' || $section_id == '6')
				{
					foreach($LPage as $brandDetails)
					{
						$BrandTitle = $BrandImage = $BrandLink = '';
						foreach($brandDetails as $bkey => $brandVal)
						{
							$ExpKey = explode("_", $bkey);
							$ID = $ExpKey[count($ExpKey)-1];
							if($bkey == 'brand_title_'.$ID)
							{
								$BrandTitle = $brandVal;
							} elseif($bkey == 'brand_image_'.$ID)
							{
								$BrandImage = $brandVal;
							} elseif($bkey == 'link_'.$ID)
							{
								$BrandLink = $brandVal;
							}
						}
						$AllSections[$section_id][]=[
							'brand_title' => $BrandTitle,
							'brand_image' => $BrandImage,
							'link' => $BrandLink
						];
					}
				}

				if($section_id == '5')
				{
					$AllSections[$section_id]['title'] = $LPage['title'];
					$AllSections[$section_id]['link'] = $LPage['link'];
					$AllSections[$section_id]['header_image'] = $LPage['header_image'];
					$AllSections[$section_id]['header_image_mobile'] = $LPage['header_image_mobile'];
				}
			}
		}
		//dd($AllSections);
		$this->PageData['AllSections'] = $AllSections;
		return view('staticpages.maxaroma-edit')->with($this->PageData);
	}
	public function getCSRFToken()
	{
		return response()->json([
			'token' => csrf_token()
		]);
	}
	public function SeasonalLandingPages()
	{
		return view('staticpages.seasonal-landing-pages');
	}
	public function LuxuryEdit()
	{
		return view('staticpages.luxury-edit');
	}
	
	public function AdvancedLandingPage(Request $request){
		
		$pageSlug = $request->pageslug;
		
		if($pageSlug != ''){
			
			$Page = DB::table("pu_advanced_landing_pages")
			->where('landing_page_url',$pageSlug)
			->where('status','1')
			->get();
			
			if($Page->count() <= 0){
				return redirect('/');
			}
			
			$this->PageData['pageName'] = $Page[0]->landing_page_name;
			$this->PageData['meta_title'] = $Page[0]->meta_title;
			$this->PageData['meta_description'] = $Page[0]->meta_description;
			
			if(!Cache::has($pageSlug.'-advanced-landing-page')){
				
				$Details = DB::table("pu_landing_page_sections_details as d")
				->select('d.*','s.*','r.rank AS section_rank')
				->join("pu_landing_page_sections as s", "s.landing_section_id", "=","d.landing_section_id")
				->join("pu_landing_page_section_rank as r", function ($join) {
						$join->on("r.landing_section_id", "=", "d.landing_section_id")
							 ->on("r.landing_page_id", "=", "d.landing_page_id");
					})
				->where('d.landing_page_id',$Page[0]->landing_page_id)
				//->where('s.section_name','Hero Section')
				->where('s.status','1')
				->where('d.status','1')
				->orderBy('r.rank','ASC')
				->orderBy('d.rank','ASC')
				->get();
				$sectionWise = $Details->groupBy('section_name');
				//echo "<pre>";print_r($sectionWise);echo "</pre>";
				
				Cache::put($pageSlug.'-advanced-landing-page', $sectionWise);
			}
			else{
				$sectionWise = Cache::get($pageSlug.'-advanced-landing-page');
			}
			//echo "<pre>";print_r($sectionWise);echo "</pre>";
			
			$this->PageData['LandingPageDetails'] = $sectionWise;
			return view('staticpages.advanced-landing-page')->with($this->PageData);
		}
		else{
			return redirect('/');
		}
	}
	
}
