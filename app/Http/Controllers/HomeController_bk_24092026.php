<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\Models\SiteSettings;
	use App\Models\MetaInfo;
	use App\Models\HomeImage;
	use App\Models\HomepageHtmlProducts;
	use App\Models\Manufacture;
	use App\Models\Dealofweek;
	use App\Http\Controllers\Traits\CommonTrait;
	use App\Http\Controllers\Traits\VendorTrait;
	use App\Http\Controllers\Traits\CartTrait;
	use App\Http\Services\CacheService;
	use DB;
	use Session;
	use Cache;
	class HomeController extends Controller
	{
		use CommonTrait;
		use VendorTrait;
		use CartTrait;
		public $PageData;

		public function __construct(Request $request)
		{
            /** NEW DESIGN **/
            $NewHomeDesign = 0;

            $NewHomeDesign = 1;
            $this->PageData['CSSFILES'] = ['slick.css','home-new.css'];
            /*
            if(isset($request->ver) && $request->ver == 'new')
            {
                $NewHomeDesign = 1;
                $this->PageData['CSSFILES'] = ['slick.css','home-new.css'];
            } else {
                $this->PageData['CSSFILES'] = ['slick.css','home.css'];
            }*/
            $this->PageData['NewHomeDesign'] = $NewHomeDesign;
            /** NEW DESIGN **/

			$this->PageData['JSFILES'] = ['slick.js','moment.min.js','home.js'];
			$GTMDATA = ['page' => 'home', 'pagetype' => 'home'];
			$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);
			$this->PageData['TEST'] = '123';
		}
		public function index(Request $request)
		{
			if(isset($request->ap) && $request->ap!='')
			{
				Session::put('sess_apmcust',$request->ap);
            }
            if(isset($request->test))
            {
                config(['app.debug' => true]);
                \Debugbar::enable();
            }
			$CategoryID = '';

			$Homepage_Video_Text = $Homepage_Video_Text2 = '';
			$Homepage_Mobile_Video_Text = $Homepage_Mobile_Video_Text2 = '';
			//if (!Cache::has('HomeBanners')) {
				/*$HomeBanners = HomeImage::where('status','=','1')
							->where('flag','!=','Home Banner Video')
							->whereIn('section',['HOME MAIN','HOME BOTTOM','MIDDLE BOTTOM BANNER','HOMEPAGE DEAL PRODUCT','HOME TOP THREE','HOME FEATURED BRANDS'])
							->orderBy('section')
							->orderBy('position')->get();*/
				// $HomeBanners = HomeImage::where('status','=','1')
				// 			->where('flag','!=','Home Banner Video')
				// 			->whereIn('section',['HOME MAIN','HOME BOTTOM','MIDDLE BOTTOM BANNER','HOMEPAGE DEAL PRODUCT','HOME TOP THREE','HOME FEATURED BRANDS','HOME FEATURED BRANDS 2026','HOME MAIN 2026'])
				// 			->where('start_date','<=',date("Y-m-d"))
				// 			->where('end_date','>=',date("Y-m-d"))
				// 			->orderBy('section')
				// 			->orderBy('position')->get();
				// Cache::put('HomeBanners', $HomeBanners);
			// }else{
			// 	$HomeBanners = Cache::get('HomeBanners');
			// }
			$HomeBanners = CacheService::HomeBanners();

			if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
				// $getHomeBannerVideo = HomeImage::where('flag','=','Home Banner Video')
				// 				->where('detail_html','!=','')
				// 				->orWhere('mobile_detail_html','!=','')
				// 				->whereIn('section',['HOME MAIN'])
				// 				->orderBy('position')->get();
				$getHomeBannerVideo = HomeImage::where('flag','=','Home Banner Video')
								->whereIn('section',['HOME MAIN'])
								->orderBy('position')->get();
			}
			else{
				// if (!Cache::has('HomeBannersVideo')) {
				// 	$getHomeBannerVideo = HomeImage::where('status','=','1')
				// 					->where('flag','=','Home Banner Video')
				// 					->whereIn('section',['HOME MAIN'])
				// 					->orderBy('position')->get();

				// 	Cache::put('HomeBannersVideo', $getHomeBannerVideo);
				// }
				// else{
				// 	$getHomeBannerVideo = Cache::get('HomeBannersVideo');
				// }
				$getHomeBannerVideo = CacheService::HomeBannerVideos();
			}

			$HomeMainBannerVideos = [];

			if($getHomeBannerVideo->count() > 0){
				foreach($getHomeBannerVideo as $HBannerVideo){

					if($HBannerVideo->detail_html != '' && file_exists(config('global.HOME_IMAGE_PATH').'videos/'.$HBannerVideo->detail_html)){

						$newVideoVal = config('global.HOME_IMAGE_PATH').'videos/'.stripslashes($HBannerVideo->detail_html);
						$verPVideo = filemtime($newVideoVal);

						$Homepage_Video_Text = $HBannerVideo->detail_html."?ver=".$verPVideo;

						if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
							$Homepage_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Video_Text.'" style="width:100%;height:auto"></video>';
						}
						else{
							$Homepage_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.SPEED_SIZE_URL').config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Video_Text.'" style="width:100%;height:auto"></video>';
						}

						$HBannerVideo->detail_html = $Homepage_Video_Text;
					}

					if($HBannerVideo->mobile_detail_html != '' && file_exists(config('global.HOME_IMAGE_PATH').'videos/'.$HBannerVideo->mobile_detail_html)){

						$newMobVideoVal = config('global.HOME_IMAGE_PATH').'videos/'.stripslashes($HBannerVideo->mobile_detail_html);
						$verPMobVideo = filemtime($newMobVideoVal);

						$Homepage_Mobile_Video_Text = $HBannerVideo->mobile_detail_html."?ver=".$verPMobVideo;

						if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
							$Homepage_Mobile_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Mobile_Video_Text.'" style="width:100%;height:auto"></video>';
						}
						else{
							$Homepage_Mobile_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.SPEED_SIZE_URL').config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Mobile_Video_Text.'" style="width:100%;height:auto"></video>';
						}

						$HBannerVideo->mobile_detail_html = $Homepage_Mobile_Video_Text;
					}else{
						$Homepage_Mobile_Video_Text = $Homepage_Video_Text;
						$HBannerVideo->mobile_detail_html = $Homepage_Mobile_Video_Text;
					}

					$HomeMainBannerVideos[] = $HBannerVideo;
				}
			}
			//dd($HomeMainBannerVideos);

			/*if($getHomeBannerVideo->count() > 0){
				if($getHomeBannerVideo[0]->detail_html != ''){
					$Homepage_Video_Text = $getHomeBannerVideo[0]->detail_html;
					//$Homepage_Video_Text = htmlspecialchars('<iframe src="https://'.$Homepage_Video_Text);
					$Homepage_Video_Text = htmlspecialchars($Homepage_Video_Text);
					$Homepage_Video_Text = htmlspecialchars_decode($Homepage_Video_Text);
				}
				if($getHomeBannerVideo[0]->mobile_detail_html != ''){
					$Homepage_Mobile_Video_Text = $getHomeBannerVideo[0]->mobile_detail_html;
					//$Homepage_Mobile_Video_Text = htmlspecialchars('<iframe src="https://'.$Homepage_Mobile_Video_Text);
					$Homepage_Mobile_Video_Text = htmlspecialchars($Homepage_Mobile_Video_Text);
					$Homepage_Mobile_Video_Text = htmlspecialchars_decode($Homepage_Mobile_Video_Text);
				}else{
					$Homepage_Mobile_Video_Text = $Homepage_Video_Text;
				}
			}*/
			//echo $Homepage_Video_Text;exit;

			$Banners = [];
			$Section = "";
			$DefaultImage = [
				"HOME_MAIN" => "main-banner.jpg",
				"HOMEPAGE_DEAL_PRODUCT" => "weekly_sale_banner.jpg",
				"HOME_BOTTOM" => ['pick-bnr.jpg','earn-bnr.jpg',"affil-bnr.jpg"],
				"MIDDLE_BOTTOM_BANNER" => ['wholesalers-bnr.jpg','blog-bnr.jpg'],
                "HOME_TOP_THREE" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS_2026" => "weekly_sale_banner.jpg",
				"HOME_MAIN_2026" => "main-banner.jpg",
			];
			$DefaultImageMobile = [
				"HOME_MAIN" => "main-banner-mobile.jpg",
				"HOMEPAGE_DEAL_PRODUCT" => "weekly_sale_banner_mob.jpg",
				"HOME_BOTTOM" => ['pick-bnr.jpg','earn-bnr.jpg',"affil-bnr.jpg"],
				"MIDDLE_BOTTOM_BANNER" => ['wholesalers-bnr.jpg','blog-bnr.jpg'],
                "HOME_TOP_THREE" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS_2026" => "weekly_sale_banner.jpg",
				"HOME_MAIN_2026" => "main-banner-mobile.jpg",
			];
			$MiddleSection = 0;
			$HomeBottomCnt = 0;
			foreach($HomeBanners as $HBanner)
			{
				$Section = str_replace(" ","_",$HBanner->section);
				$BannerImg = $HBanner->home_image;
				$DefaultImg = $DefaultImage[$Section];
				$DefaultImgMob = $DefaultImageMobile[$Section];
				if($Section == 'MIDDLE_BOTTOM_BANNER')
				{
					$DefaultImg = $DefaultImage[$Section][$MiddleSection];
					$DefaultImgMob = $DefaultImageMobile[$Section][$MiddleSection];
					$MiddleSection++;
				}
				if($Section == 'HOME_BOTTOM')
				{
					$DefaultImg = $DefaultImage[$Section][$HomeBottomCnt];
					$DefaultImgMob = $DefaultImageMobile[$Section][$HomeBottomCnt];
					$HomeBottomCnt++;
				}
				if($Section == 'HOMEPAGE_DEAL_PRODUCT')
				{
					$DefaultImg = $DefaultImage[$Section];
					$DefaultImgMob = $DefaultImageMobile[$Section];
				}
				if(!empty($BannerImg) && file_exists(config('global.HOME_IMAGE_PATH').$BannerImg)){
					$newimageVal = config('global.HOME_IMAGE_PATH')  . stripslashes($BannerImg);
					$verP = filemtime($newimageVal);
					$BannerImg = config('global.HOME_IMAGE_URL') . $BannerImg . "?ver=" . $verP;
				} else {
					$BannerImg = config('global.SITE_IMAGES').$DefaultImg;
				}
				$HBanner->home_image = $BannerImg;

				$BannerImgMobile = $HBanner->mobile_image;
				if(!empty($BannerImgMobile) && file_exists(config('global.HOME_IMAGE_PATH').$BannerImgMobile)){
					$BannerImgMobile = config('global.HOME_IMAGE_URL').$BannerImgMobile;
				} else {
					$BannerImgMobile = config('global.SITE_IMAGES').$DefaultImgMob;
				}
				$HBanner->mobile_image = $BannerImgMobile;

				$BannerLink = $HBanner->link;
				$HBanner->link = str_replace('{$Site_URL}',config('global.SITE_URL'),$BannerLink);
				$Banners[$Section][] = $HBanner;
			}
			//PrintObj($Banners['HOME_BOTTOM']);
            $this->PageData['HomeMainBanners'] = isset($Banners['HOME_MAIN'])?$Banners['HOME_MAIN']:[];
			$this->PageData['HomeWeeklyBanners'] = isset($Banners['HOMEPAGE_DEAL_PRODUCT'])?$Banners['HOMEPAGE_DEAL_PRODUCT']:[];
			$this->PageData['HomeBottomBanners'] = isset($Banners['HOME_BOTTOM'])?$Banners['HOME_BOTTOM']:[];
			$this->PageData['HomeMiddleBottomBanners'] = isset($Banners['MIDDLE_BOTTOM_BANNER'])?$Banners['MIDDLE_BOTTOM_BANNER']:[];
            $this->PageData['HomeTopThree'] = isset($Banners['HOME_TOP_THREE'])?$Banners['HOME_TOP_THREE']:[];
            $this->PageData['HomeFeatureBrands'] = isset($Banners['HOME_FEATURED_BRANDS'])?$Banners['HOME_FEATURED_BRANDS']:[];
			$this->PageData['HomeFeatureBrands2026'] = isset($Banners['HOME_FEATURED_BRANDS_2026'])?$Banners['HOME_FEATURED_BRANDS_2026']:[];
			$this->PageData['HomeMainBanners2026'] = isset($Banners['HOME_MAIN_2026']) ? $Banners['HOME_MAIN_2026'] : [];
            /*
			$this->PageData['HomeMainBanners'] = $Banners['HOME_MAIN'];
			$this->PageData['HomeWeeklyBanners'] = $Banners['HOMEPAGE_DEAL_PRODUCT'];
			$this->PageData['HomeBottomBanners'] = $Banners['HOME_BOTTOM'];
			$this->PageData['HomeMiddleBottomBanners'] = $Banners['MIDDLE_BOTTOM_BANNER'];
            $this->PageData['HomeTopThree'] = $Banners['HOME_TOP_THREE'];
            $this->PageData['HomeFeatureBrands'] = $Banners['HOME_FEATURED_BRANDS'];
            */
			// if (!Cache::has('HomeCategoryBanners')) {
			// 	$HomeCategoryBanners = HomepageHtmlProducts::where('title','!=','')
			// 	->where('banner_image','!=','')
			// 	->where('status','=','1')
			// 	->orderBy('position')->get();
			// 	Cache::put('HomeCategoryBanners', $HomeCategoryBanners);
			// }else{
			// 	$HomeCategoryBanners = Cache::get('HomeCategoryBanners');
			// }

			$HomeCategoryBanners = CacheService::HomeCategoryBanners();

			$CatBannersList = [];
			if($HomeCategoryBanners && $HomeCategoryBanners->count() > 0)
			{
				foreach($HomeCategoryBanners as $CatBanners)
				{
					$CatBanners->main_link = str_replace('{$Site_URL}',config('global.SITE_URL'),$CatBanners->main_link);
					if(file_exists(config('global.HOME_IMAGE_PATH').$CatBanners->banner_image)){
						$newimageVal = config('global.HOME_IMAGE_PATH')  . stripslashes($CatBanners->banner_image);
						$verP = filemtime($newimageVal);
						$CatBanners->banner_image = config('global.HOME_IMAGE_URL') . $CatBanners->banner_image . "?ver=" . $verP;
					}
					$CatBannersList[]=$CatBanners;
				}
			}
			$this->PageData['HomeCategoryBanners'] = $CatBannersList;

			$NewArrivals = $this->ProductSlider('NEW ARRIVALS','Home',$CategoryID);

			//$ProductsDetails = $this->GetProducts('ProductListPage','',12,['special' => ['na']]);
            $TotalProducts = config('GeneralSettings.NEW_ARRIVAL_COUNT');
            if(isset($NewArrivals[0]['products']))
            {
            $this->PageData['NewArrivals'] = $NewArrivals[0]['products'];
			}
			else
			{
				$this->PageData['NewArrivals'] = array();
			}
			$this->PageData['NewArrivalsAttr'] = [
				'Title' => 'New Arrivals',
				'Slider' => 'home-new-sl',
				'SeeMore' => config('global.SITE_URL').'new-arrivals/p4u/special-na/view',
                'TotalProd' => $TotalProducts
            ];

			$BestSellers = $this->ProductSlider('TOP SELLERS','Home',$CategoryID);
			$this->PageData['BestSellers'] = $BestSellers[0]['products'];
			$this->PageData['BestSellersAttr'] = [
				'Title' => 'Best Sellers',
				'Slider' => 'home-new-sl',
				'SeeMore' => config('global.SITE_URL').'top-sellers/p4u/special-ts/view',
			];

			$PageType = 'HO';
			$MetaInfo = CacheService::HomeMetaInfo();
			// if (!Cache::has('HOMEMETAINFO')) {
			// 	$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
			// 	Cache::put('HOMEMETAINFO', $MetaInfo);
			// }else{
			// 	$MetaInfo = Cache::get('HOMEMETAINFO');
			// }

			if($MetaInfo->count() > 0 )
			{
				$this->PageData['meta_title'] = getMetaTitleDescription($MetaInfo[0]->meta_title);
				$this->PageData['meta_description'] = getMetaTitleDescription($MetaInfo[0]->meta_description);
				$this->PageData['meta_keywords'] = getMetaTitleDescription($MetaInfo[0]->meta_keywords);
			}

			$this->PageData['Homepage_Video_Text'] = $Homepage_Video_Text;
			$this->PageData['Homepage_Mobile_Video_Text'] = $Homepage_Mobile_Video_Text;

			$this->PageData['Homepage_Video_Text2'] = $Homepage_Video_Text2;
			$this->PageData['Homepage_Mobile_Video_Text2'] = $Homepage_Mobile_Video_Text2;

            $this->PageData['HomeMainBannerVideos'] = isset($HomeMainBannerVideos)?$HomeMainBannerVideos:[];

			/** Schema - Start */
			$SchemaDetails = ['NewArrivals' => $this->PageData['NewArrivals'], 'BestSellers' => $this->PageData['BestSellers']];
			$this->PageData['SitePageSchema'] = PageSchema('home',$SchemaDetails);
			/** Schema - End */

			// if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP'] == '2406:b400:d11:3091:6c49:a4d7:44d:9532'){
			// 	$this->PageData['Deal'] = $this->HomeDealOfWeek();
			// }
            /** NEW DESIGN **/

            if(isset($request->ss) && $request->ss == 1)
            {
				$this->PageData['ss'] = 1;
				return view('home-ss')->with($this->PageData);
			}
			elseif(isset($request->ver) && $request->ver == 2026)
			{
				$this->PageData['ver']='2026';
				$this->PageData['NewHomeDesign'] = 2026;
				$this->PageData['JSFILES'] = ['slick.js','moment.min.js','home26.js'];
				$this->PageData['CSSFILES'] = ['slick.css','home26.css'];
                return view('home-new-2026')->with($this->PageData);
            }
            elseif($this->PageData['NewHomeDesign'] == 1)
                return view('home-new')->with($this->PageData);

            else
                return view('home')->with($this->PageData);
            /** NEW DESIGN **/
		}

		public function Home26(Request $request)
		{
			if(isset($request->ap) && $request->ap!='')
			{
				Session::put('sess_apmcust',$request->ap);
            }
            if(isset($request->test))
            {
                config(['app.debug' => true]);
                \Debugbar::enable();
            }
			$CategoryID = '';

			$Homepage_Video_Text = $Homepage_Video_Text2 = '';
			$Homepage_Mobile_Video_Text = $Homepage_Mobile_Video_Text2 = '';
			//if (!Cache::has('HomeBanners')) {
				/*$HomeBanners = HomeImage::where('status','=','1')
							->where('flag','!=','Home Banner Video')
							->whereIn('section',['HOME MAIN','HOME BOTTOM','MIDDLE BOTTOM BANNER','HOMEPAGE DEAL PRODUCT','HOME TOP THREE','HOME FEATURED BRANDS'])
							->orderBy('section')
							->orderBy('position')->get();*/
				$HomeBanners = HomeImage::where('status','=','1')
							->where('flag','!=','Home Banner Video')
							->whereIn('section',['HOME MAIN','HOME BOTTOM','MIDDLE BOTTOM BANNER','HOMEPAGE DEAL PRODUCT','HOME TOP THREE','HOME FEATURED BRANDS'])
							->where('start_date','<=',date("Y-m-d"))
							->where('end_date','>=',date("Y-m-d"))
							->orderBy('section')
							->orderBy('position')->get();
				Cache::put('HomeBanners', $HomeBanners);
			// }else{
			// 	$HomeBanners = Cache::get('HomeBanners');
			// }

			if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
				$getHomeBannerVideo = HomeImage::where('flag','=','Home Banner Video')
								->where('detail_html','!=','')
								->orWhere('mobile_detail_html','!=','')
								->whereIn('section',['HOME MAIN'])
								->orderBy('position')->get();
			}
			else{
				if (!Cache::has('HomeBannersVideo')) {
					$getHomeBannerVideo = HomeImage::where('status','=','1')
									->where('flag','=','Home Banner Video')
									->whereIn('section',['HOME MAIN'])
									->orderBy('position')->get();

					Cache::put('HomeBannersVideo', $getHomeBannerVideo);
				}
				else{
					$getHomeBannerVideo = Cache::get('HomeBannersVideo');
				}
			}

			$HomeMainBannerVideos = [];

			if($getHomeBannerVideo->count() > 0){
				foreach($getHomeBannerVideo as $HBannerVideo){

					if($HBannerVideo->detail_html != '' && file_exists(config('global.HOME_IMAGE_PATH').'videos/'.$HBannerVideo->detail_html)){

						$newVideoVal = config('global.HOME_IMAGE_PATH').'videos/'.stripslashes($HBannerVideo->detail_html);
						$verPVideo = filemtime($newVideoVal);

						$Homepage_Video_Text = $HBannerVideo->detail_html."?ver=".$verPVideo;

						if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
							$Homepage_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Video_Text.'" style="width:100%;height:auto"></video>';
						}
						else{
							$Homepage_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.SPEED_SIZE_URL').config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Video_Text.'" style="width:100%;height:auto"></video>';
						}

						$HBannerVideo->detail_html = $Homepage_Video_Text;
					}

					if($HBannerVideo->mobile_detail_html != '' && file_exists(config('global.HOME_IMAGE_PATH').'videos/'.$HBannerVideo->mobile_detail_html)){

						$newMobVideoVal = config('global.HOME_IMAGE_PATH').'videos/'.stripslashes($HBannerVideo->mobile_detail_html);
						$verPMobVideo = filemtime($newMobVideoVal);

						$Homepage_Mobile_Video_Text = $HBannerVideo->mobile_detail_html."?ver=".$verPMobVideo;

						if(isset($request['preview']) && $request['preview'] != '' && $request['preview'] != null && $request['preview'] == 1){
							$Homepage_Mobile_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Mobile_Video_Text.'" style="width:100%;height:auto"></video>';
						}
						else{
							$Homepage_Mobile_Video_Text = '<video playsinline autoplay muted loop preload="auto" src="'.config('global.SPEED_SIZE_URL').config('global.HOME_IMAGE_URL').'videos/'.$Homepage_Mobile_Video_Text.'" style="width:100%;height:auto"></video>';
						}

						$HBannerVideo->mobile_detail_html = $Homepage_Mobile_Video_Text;
					}else{
						$Homepage_Mobile_Video_Text = $Homepage_Video_Text;
						$HBannerVideo->mobile_detail_html = $Homepage_Mobile_Video_Text;
					}

					$HomeMainBannerVideos[] = $HBannerVideo;
				}
			}
			//dd($HomeMainBannerVideos);

			/*if($getHomeBannerVideo->count() > 0){
				if($getHomeBannerVideo[0]->detail_html != ''){
					$Homepage_Video_Text = $getHomeBannerVideo[0]->detail_html;
					//$Homepage_Video_Text = htmlspecialchars('<iframe src="https://'.$Homepage_Video_Text);
					$Homepage_Video_Text = htmlspecialchars($Homepage_Video_Text);
					$Homepage_Video_Text = htmlspecialchars_decode($Homepage_Video_Text);
				}
				if($getHomeBannerVideo[0]->mobile_detail_html != ''){
					$Homepage_Mobile_Video_Text = $getHomeBannerVideo[0]->mobile_detail_html;
					//$Homepage_Mobile_Video_Text = htmlspecialchars('<iframe src="https://'.$Homepage_Mobile_Video_Text);
					$Homepage_Mobile_Video_Text = htmlspecialchars($Homepage_Mobile_Video_Text);
					$Homepage_Mobile_Video_Text = htmlspecialchars_decode($Homepage_Mobile_Video_Text);
				}else{
					$Homepage_Mobile_Video_Text = $Homepage_Video_Text;
				}
			}*/
			//echo $Homepage_Video_Text;exit;

			$Banners = [];
			$Section = "";
			$DefaultImage = [
				"HOME_MAIN" => "main-banner.jpg",
				"HOMEPAGE_DEAL_PRODUCT" => "weekly_sale_banner.jpg",
				"HOME_BOTTOM" => ['pick-bnr.jpg','earn-bnr.jpg',"affil-bnr.jpg"],
				"MIDDLE_BOTTOM_BANNER" => ['wholesalers-bnr.jpg','blog-bnr.jpg'],
                "HOME_TOP_THREE" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS" => "weekly_sale_banner.jpg"
			];
			$DefaultImageMobile = [
				"HOME_MAIN" => "main-banner-mobile.jpg",
				"HOMEPAGE_DEAL_PRODUCT" => "weekly_sale_banner_mob.jpg",
				"HOME_BOTTOM" => ['pick-bnr.jpg','earn-bnr.jpg',"affil-bnr.jpg"],
				"MIDDLE_BOTTOM_BANNER" => ['wholesalers-bnr.jpg','blog-bnr.jpg'],
                "HOME_TOP_THREE" => "weekly_sale_banner.jpg",
                "HOME_FEATURED_BRANDS" => "weekly_sale_banner.jpg"
			];
			$MiddleSection = 0;
			$HomeBottomCnt = 0;
			foreach($HomeBanners as $HBanner)
			{
				$Section = str_replace(" ","_",$HBanner->section);
				$BannerImg = $HBanner->home_image;
				$DefaultImg = $DefaultImage[$Section];
				$DefaultImgMob = $DefaultImageMobile[$Section];
				if($Section == 'MIDDLE_BOTTOM_BANNER')
				{
					$DefaultImg = $DefaultImage[$Section][$MiddleSection];
					$DefaultImgMob = $DefaultImageMobile[$Section][$MiddleSection];
					$MiddleSection++;
				}
				if($Section == 'HOME_BOTTOM')
				{
					$DefaultImg = $DefaultImage[$Section][$HomeBottomCnt];
					$DefaultImgMob = $DefaultImageMobile[$Section][$HomeBottomCnt];
					$HomeBottomCnt++;
				}
				if($Section == 'HOMEPAGE_DEAL_PRODUCT')
				{
					$DefaultImg = $DefaultImage[$Section];
					$DefaultImgMob = $DefaultImageMobile[$Section];
				}
				if(!empty($BannerImg) && file_exists(config('global.HOME_IMAGE_PATH').$BannerImg)){
					$newimageVal = config('global.HOME_IMAGE_PATH')  . stripslashes($BannerImg);
					$verP = filemtime($newimageVal);
					$BannerImg = config('global.HOME_IMAGE_URL') . $BannerImg . "?ver=" . $verP;
				} else {
					$BannerImg = config('global.SITE_IMAGES').$DefaultImg;
				}
				$HBanner->home_image = $BannerImg;

				$BannerImgMobile = $HBanner->mobile_image;
				if(!empty($BannerImgMobile) && file_exists(config('global.HOME_IMAGE_PATH').$BannerImgMobile)){
					$BannerImgMobile = config('global.HOME_IMAGE_URL').$BannerImgMobile;
				} else {
					$BannerImgMobile = config('global.SITE_IMAGES').$DefaultImgMob;
				}
				$HBanner->mobile_image = $BannerImgMobile;

				$BannerLink = $HBanner->link;
				$HBanner->link = str_replace('{$Site_URL}',config('global.SITE_URL'),$BannerLink);
				$Banners[$Section][] = $HBanner;
			}
			//PrintObj($Banners['HOME_BOTTOM']);
            $this->PageData['HomeMainBanners'] = isset($Banners['HOME_MAIN'])?$Banners['HOME_MAIN']:[];
			$this->PageData['HomeWeeklyBanners'] = isset($Banners['HOMEPAGE_DEAL_PRODUCT'])?$Banners['HOMEPAGE_DEAL_PRODUCT']:[];
			$this->PageData['HomeBottomBanners'] = isset($Banners['HOME_BOTTOM'])?$Banners['HOME_BOTTOM']:[];
			$this->PageData['HomeMiddleBottomBanners'] = isset($Banners['MIDDLE_BOTTOM_BANNER'])?$Banners['MIDDLE_BOTTOM_BANNER']:[];
            $this->PageData['HomeTopThree'] = isset($Banners['HOME_TOP_THREE'])?$Banners['HOME_TOP_THREE']:[];
            $this->PageData['HomeFeatureBrands'] = isset($Banners['HOME_FEATURED_BRANDS'])?$Banners['HOME_FEATURED_BRANDS']:[];
            /*
			$this->PageData['HomeMainBanners'] = $Banners['HOME_MAIN'];
			$this->PageData['HomeWeeklyBanners'] = $Banners['HOMEPAGE_DEAL_PRODUCT'];
			$this->PageData['HomeBottomBanners'] = $Banners['HOME_BOTTOM'];
			$this->PageData['HomeMiddleBottomBanners'] = $Banners['MIDDLE_BOTTOM_BANNER'];
            $this->PageData['HomeTopThree'] = $Banners['HOME_TOP_THREE'];
            $this->PageData['HomeFeatureBrands'] = $Banners['HOME_FEATURED_BRANDS'];
            */
			if (!Cache::has('HomeCategoryBanners')) {
				$HomeCategoryBanners = HomepageHtmlProducts::where('title','!=','')
				->where('banner_image','!=','')
				->where('status','=','1')
				->orderBy('position')->get();
				Cache::put('HomeCategoryBanners', $HomeCategoryBanners);
			}else{
				$HomeCategoryBanners = Cache::get('HomeCategoryBanners');
			}

			$CatBannersList = [];
			if($HomeCategoryBanners && $HomeCategoryBanners->count() > 0)
			{
				foreach($HomeCategoryBanners as $CatBanners)
				{
					$CatBanners->main_link = str_replace('{$Site_URL}',config('global.SITE_URL'),$CatBanners->main_link);
					if(file_exists(config('global.HOME_IMAGE_PATH').$CatBanners->banner_image)){
						$newimageVal = config('global.HOME_IMAGE_PATH')  . stripslashes($CatBanners->banner_image);
						$verP = filemtime($newimageVal);
						$CatBanners->banner_image = config('global.HOME_IMAGE_URL') . $CatBanners->banner_image . "?ver=" . $verP;
					}
					$CatBannersList[]=$CatBanners;
				}
			}
			$this->PageData['HomeCategoryBanners'] = $CatBannersList;

			$NewArrivals = $this->ProductSlider('NEW ARRIVALS','Home',$CategoryID);

			//$ProductsDetails = $this->GetProducts('ProductListPage','',12,['special' => ['na']]);
            $TotalProducts = config('GeneralSettings.NEW_ARRIVAL_COUNT');
            if(isset($NewArrivals[0]['products']))
            {
            $this->PageData['NewArrivals'] = $NewArrivals[0]['products'];
			}
			else
			{
				$this->PageData['NewArrivals'] = array();
			}
			$this->PageData['NewArrivalsAttr'] = [
				'Title' => 'New Arrivals',
				'Slider' => 'home-new-sl',
				'SeeMore' => config('global.SITE_URL').'new-arrivals/p4u/special-na/view',
                'TotalProd' => $TotalProducts
            ];

			$BestSellers = $this->ProductSlider('TOP SELLERS','Home',$CategoryID);
			$this->PageData['BestSellers'] = $BestSellers[0]['products'];
			$this->PageData['BestSellersAttr'] = [
				'Title' => 'Best Sellers',
				'Slider' => 'home-new-sl',
				'SeeMore' => config('global.SITE_URL').'top-sellers/p4u/special-ts/view',
			];

			$PageType = 'HO';
			if (!Cache::has('HOMEMETAINFO')) {
				$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
				Cache::put('HOMEMETAINFO', $MetaInfo);
			}else{
				$MetaInfo = Cache::get('HOMEMETAINFO');
			}

			if($MetaInfo->count() > 0 )
			{
				$this->PageData['meta_title'] = getMetaTitleDescription($MetaInfo[0]->meta_title);
				$this->PageData['meta_description'] = getMetaTitleDescription($MetaInfo[0]->meta_description);
				$this->PageData['meta_keywords'] = getMetaTitleDescription($MetaInfo[0]->meta_keywords);
			}

			$this->PageData['Homepage_Video_Text'] = $Homepage_Video_Text;
			$this->PageData['Homepage_Mobile_Video_Text'] = $Homepage_Mobile_Video_Text;

			$this->PageData['Homepage_Video_Text2'] = $Homepage_Video_Text2;
			$this->PageData['Homepage_Mobile_Video_Text2'] = $Homepage_Mobile_Video_Text2;

            $this->PageData['HomeMainBannerVideos'] = isset($HomeMainBannerVideos)?$HomeMainBannerVideos:[];

			/** Schema - Start */
			$SchemaDetails = ['NewArrivals' => $this->PageData['NewArrivals'], 'BestSellers' => $this->PageData['BestSellers']];
			$this->PageData['SitePageSchema'] = PageSchema('home',$SchemaDetails);
			/** Schema - End */

			// if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP'] == '2406:b400:d11:3091:6c49:a4d7:44d:9532'){
			// 	$this->PageData['Deal'] = $this->HomeDealOfWeek();
			// }
            /** NEW DESIGN **/

			$this->PageData['ver']='2026';
			$this->PageData['NewHomeDesign'] = 2026;
			$this->PageData['JSFILES'] = ['slick.js','moment.min.js','home26.js'];
			$this->PageData['CSSFILES'] = ['slick.css','home26.css'];
			return view('home-new-2026')->with($this->PageData);
			//return view('home-new-2026-bk')->with($this->PageData);

            if(isset($request->ss) && $request->ss == 1)
            {
				$this->PageData['ss'] = 1;
				return view('home-ss')->with($this->PageData);
			}
			elseif(isset($request->ver) && $request->ver == 2026)
			{
				$this->PageData['ver']='2026';
				$this->PageData['NewHomeDesign'] = 2026;
				$this->PageData['JSFILES'] = ['slick.js','moment.min.js','home26.js'];
				$this->PageData['CSSFILES'] = ['slick.css','home26.css'];
                return view('home-new-2026')->with($this->PageData);
            }
            elseif($this->PageData['NewHomeDesign'] == 1)
                return view('home-new')->with($this->PageData);

            else
                return view('home')->with($this->PageData);
		}
		public function HomeDealOfWeek()
		{
			if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP'] == '2406:b400:d11:3091:6c49:a4d7:44d:9532'){
				$DealOfWeekQuery = Dealofweek::select('pu_dealofweek.*', 'dwt.*', 'p.*', 'pc.*', 'm.*')
					->join('pu_dealofweektitle as dwt', 'pu_dealofweek.did', '=', 'dwt.did')
					->join('pu_products as p', 'pu_dealofweek.product_sku', '=', 'p.sku')
					->join('pu_products_category as pc', 'p.products_id', '=', 'pc.products_id')
					->join('pu_manufacture as m', 'p.imanufactureid', '=', 'm.imanufactureid')
					->where('p.status', '=', '1')
					->where('pu_dealofweek.status', '=', '1')
					->where('pu_dealofweek.start_date', '<=', date('Y-m-d H:i'))
					->where('pu_dealofweek.end_date', '>=', date('Y-m-d H:i'))
					->where('pu_dealofweek.display_on_home', '=', 'Yes');

				$userType = Session::get('eusertype');
				if(Session::get('eusertype') && strtolower(Session::get('eusertype')) == 'wholesaler'){
					$DealOfWeekQuery->whereIn('p.product_type', ['both', 'retailer', 'wholesaler']);
				} else {
					$DealOfWeekQuery->whereIn('p.product_type', ['both', 'retailer']);
				}
				$DealOfWeekQuery->orderBy('pu_dealofweek.end_date');
				$DealOfWeekQuery->orderBy('pu_dealofweek.display_rank');
				$DealOfWeek = $DealOfWeekQuery->first();
			}

			$DealOfWeekQry = DB::table('pu_dealofweek as dw')
							->join('pu_dealofweektitle as dwt','dw.did','=','dwt.did')
							->join('pu_products as p','dw.product_sku','=','p.sku')
							->join('pu_products_category as pc','p.products_id','=','pc.products_id')
							->join('pu_manufacture as m','p.imanufactureid','=','m.imanufactureid')
							->where('p.status','=','1')->where('dw.status','=','1')
							->where('dw.start_date','<=',date('Y-m-d H:i'))
							->where('dw.end_date','>=',date('Y-m-d H:i'))
							->where('dw.display_on_home','=','Yes');
			if(Session::get('eusertype') && strtolower(Session::get('eusertype')) == 'wholesaler')
				$DealOfWeekQry->whereIn('p.product_type',['both','retailer','wholesaler']);
			else
				$DealOfWeekQry->whereIn('p.product_type',['both','retailer']);
			$DealOfWeekQry->orderBy('dw.end_date');
			$DealOfWeekQry->orderBy('display_rank');
			$DealOfWeek = $DealOfWeekQry->limit(1)->get();

			if($DealOfWeek && $DealOfWeek->count() > 0)
			{
				$Deal = $this->SetProduct($DealOfWeek[0]);
				$product_price = $Deal->product_price;
				$Deal->product_url = SetProductURL($Deal->products_id, $Deal->product_name, $Deal->category_id);
				if($Deal->deal_price < $product_price)
					$Deal->product_price = $Deal->deal_price;

				if($Deal->deal_price !='' && $Deal->deal_price < $product_price)
				{
					$yousave = ($Deal->retail_price - $Deal->deal_price)/$Deal->retail_price;
					$yousave = $yousave*100;
					$yousave = number_format($yousave,0);
					$Deal->yousave = $yousave;
					$yousaveprice = $Deal->retail_price - $Deal->deal_price;
					$Deal->yousaveprice = $yousaveprice;
				}else{
					$retail_price = $Deal->retail_price;
					if($retail_price!='' && $retail_price!='0.00')
						$yousave=($retail_price-$product_price)/$retail_price;
					$yousave = $yousave*100;
					$yousave = number_format($yousave,0);
					$yousaveprice = $retail_price-$product_price;
					$Deal->yousaveprice = $yousaveprice;
					$Deal->yousave = $yousave;
				}
				$Deal->formatted_end_date = date('d',strtotime($Deal->end_date));
				$Deal->formatted_end_month = date('m',strtotime($Deal->end_date));
				$Deal->formatted_end_year = date('Y',strtotime($Deal->end_date));
				$Deal->deal_end_date = date('d-m-Y 23:59:00',strtotime($Deal->end_date));
				return $Deal;
			}
		}
	}
