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
		
	public function __construct()
	{
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
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
		return view('staticpages.frangrance-landing-page')->with($this->PageData);
	}
}
