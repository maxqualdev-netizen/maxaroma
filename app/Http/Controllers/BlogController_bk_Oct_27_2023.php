<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MetaInfo;
use App\Models\BlogSection;
use App\Models\BlogSubLanding;
use App\Models\Blog;
use App\Models\LandingPagesData;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\VendorTrait;
use App\Http\Controllers\Traits\CartTrait;
use DB;
use Session;
use Cache;

class BlogController extends Controller
{
	use CommonTrait;
	use VendorTrait;
	use CartTrait;
		
	public function __construct()
	{
		// $PageType = 'NR';
		// $MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		// if($MetaInfo->count() > 0 )
		// {
		// 	$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
		// 	$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
		// 	$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		// }
	}

	
	public function Blog(Request $request)
	{
		if(Cache::has('DefaultMetaInfo'))
        {
            $MetaInfo = Cache::get('DefaultMetaInfo');
            $this->PageData['meta_title'] = $MetaInfo->meta_title;
            $this->PageData['meta_description'] = $MetaInfo->meta_description;
            $this->PageData['meta_keywords'] = $MetaInfo->meta_keywords;
        }
		$this->PageData['CSSFILES'] = ['blog.css'];
		$this->PageData['JSFILES'] = ['slick.js','landing-pages.js'];

		$blog_detail_video='';
		$mobile_detail_video='';

		$Blog_video_query = Blog::where('flag','=','Blog Video')->where('video_status','=','1')->get();
			if(count($Blog_video_query)>0){
				if($Blog_video_query[0]->blog_detail_video != ''){
					$blog_detail_video = $Blog_video_query[0]->blog_detail_video;
					$blog_detail_video = htmlspecialchars('<iframe src="https://'.$blog_detail_video);
					$blog_detail_video = htmlspecialchars_decode($blog_detail_video);
				}
				if($Blog_video_query[0]->mobile_detail_video != ''){
					$mobile_detail_video = $Blog_video_query[0]->mobile_detail_video;
					$mobile_detail_video = htmlspecialchars('<iframe src="https://'.$mobile_detail_video);
					$mobile_detail_video = htmlspecialchars_decode($mobile_detail_video);
				}else{
					$mobile_detail_video = $blog_detail_video;
				}
			
		}
     
		$this->PageData['blog_page_title']=[];

		$Blog_page_query = Blog::where('blog_page_title','!=','')->get();
			if(count($Blog_page_query)>0){
			$this->PageData['blog_page_title'] = $Blog_page_query[0]->blog_page_title;
		}else{
			$this->PageData['blog_page_title']='Blog';
		}
           
		     $Meta = MetaInfo::where('type', '=', 'BL')->get();

			$this->PageData['meta_title'] = $Meta[0]->meta_title;
			$this->PageData['meta_keywords'] = $Meta[0]->meta_keywords;
			$this->PageData['meta_description'] = $Meta[0]->meta_description;
           
           // get blog font size color

			$Blog_font_query = Blog::where('type','=','SetFont')->get();
			if(count($Blog_font_query)>0){
			$this->PageData['title_font_size'] = $Blog_font_query[0]->title_font_size;
			$this->PageData['title_font_color'] = $Blog_font_query[0]->title_font_color;
			$this->PageData['author_font_size'] = $Blog_font_query[0]->author_font_size;
			$this->PageData['author_font_color'] = $Blog_font_query[0]->author_font_color;
			$this->PageData['date_font_size'] = $Blog_font_query[0]->date_font_size;
			$this->PageData['date_font_color'] = $Blog_font_query[0]->date_font_color;
			$this->PageData['link_font_size'] = $Blog_font_query[0]->link_font_size;
			$this->PageData['link_font_color'] = $Blog_font_query[0]->link_font_color;
		    }else{
		    	$this->PageData['title_font_size'] = '';
			$this->PageData['title_font_color'] = '';
			$this->PageData['author_font_size'] = '';
			$this->PageData['author_font_color'] = '';
			$this->PageData['date_font_size'] = '';
			$this->PageData['date_font_color'] = '';
			$this->PageData['link_font_size'] = '';
			$this->PageData['link_font_color'] = '';
		    }

		$this->PageData['blog_detail_video'] = $blog_detail_video;
		$this->PageData['mobile_detail_video'] = $mobile_detail_video;

		// main blog page start
		
		$Blog = Blog::where('status','=','1')->orderBy('id', 'DESC')->get();
		$this->PageData['Blog'] = [];
		
		if($Blog->count() > 0)
		{			
			 foreach($Blog as $key => $PData)
			 {
				
						if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($PData->blog_image)) and !empty($PData->blog_image)) {
							$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($PData->blog_image);
							$verP = filemtime($newimageVal);
							$PData->blog_image = config('global.SITE_URL').'/images/blog/'.$PData->blog_image."?ver=" . $verP;
						}else{
							$PData->blog_image = '';
						}

               }
			$this->PageData['Blog'] = $Blog;
			
            }
			
		return view('staticpages.blog')->with($this->PageData);
	}
	
	public function BlogDetailPageProducts(Request $request)
	{
		$SliderProducts = [];
		$Filters = [];
		$this->PageData['ProductData'] = [];
		$Filters['page'] = $request->page;
		$Filters['limit'] = 10;
		$main_blogid = $request->main_blogid;
		
		if($request->ajax()){
		
			$BlogSection = DB::table('pu_blog_sublanding')
				->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')
				->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')
				->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();
			
			if($BlogSection->count() > 0){
				foreach($BlogSection as $LPage){
					if($LPage->blog_section_id == 8){
						
						if($LPage->sku != ''){
							$SliderProducts = $this->GetProductsWithParms($LPage->sku,'','','','','',$Filters);
						}
						else if($LPage->designerid != '' && $LPage->designerid > 0){
							if($LPage->exclude_sku != ''){
								$SliderProducts = $this->GetProductsWithParms('',$LPage->designerid,'',$LPage->exclude_sku,'','',$Filters);
							}else{
								$SliderProducts = $this->GetProductsWithParms('',$LPage->designerid,'','','','',$Filters);
							}

							if(count($SliderProducts) <= 0){
								if($LPage->category_id != '' && $LPage->category_id > 0){
									if($LPage->exclude_sku != ''){
										$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,$LPage->exclude_sku,'','',$Filters);
									}else{
										$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,'','','',$Filters);
									}
								}
							}
						}
						else if($LPage->category_id != '' && $LPage->category_id > 0){
							if($LPage->exclude_sku != ''){
								$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,$LPage->exclude_sku,'','',$Filters);
							}else{
								$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,'','','',$Filters);
							}

							if(count($SliderProducts) <= 0){
								if($LPage->sku != ''){
									$SliderProducts = $this->GetProductsWithParms($LPage->sku,'','','','','',$Filters);
								}
							}
						}				
					}
				}

				$Products = $SliderProducts['Products'];
				$TotalProducts = $SliderProducts['TotalProducts'];
				$this->PageData['ProductData'] = $Products;
				$this->PageData['TotalProducts'] = $TotalProducts;

				$ProductHTML = view('staticpages.blogpageproductlist')->with($this->PageData)->render();
				return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML' => $ProductHTML));
			}
		}
	}

	public function BlogDetailPage(Request $request){
		if(Cache::has('DefaultMetaInfo'))
        {
            $MetaInfo = Cache::get('DefaultMetaInfo');
            $this->PageData['meta_title'] = $MetaInfo->meta_title;
            $this->PageData['meta_description'] = $MetaInfo->meta_description;
            $this->PageData['meta_keywords'] = $MetaInfo->meta_keywords;
        }
          $main_blogid=$request->id;
          $title=$request->title;
		  $this->PageData['CSSFILES'] = ['blog.css'];
		//$this->PageData['SliderProducts'] = ['UP3508441001114','UP3760035450184'];
		//$BlogSection = BlogSection::with('PageData')->where('status1','=','1')->where('page_type','=','blog')->orderBy('section_rank')->get();

		//		SELECT `pu_blog_sublanding`.*,`pu_blog_sections`.`section_name`,`pu_blog_sections`.`section_rank`,`pu_blog_sections`.`page_type`,`pu_blog_sections`.`status` as section_status,`pu_blog_landing`.* from `pu_blog_sublanding` left join pu_blog_sections on pu_blog_sections.blog_section_id=pu_blog_sublanding.blog_section_id left join `pu_blog_landing` on pu_blog_landing.id=pu_blog_sublanding.main_blogid where pu_blog_sections.status='1' AND pu_blog_sublanding.main_blogid='1' ORDER BY pu_blog_sections.section_rank


		/*$BlogSection = DB::table('pu_blog_sublanding')
            ->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')
            ->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')
            ->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();*/

		$BlogSection = DB::table('pu_blog_sublanding')
            ->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')
            ->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')
            ->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_landing.status', '=', '1')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();
		$this->PageData['BlogSection'] = [];
		
		if($BlogSection->count() == 0){
			return redirect('/blog');
		}
		if($BlogSection->count() > 0)
		{	
			// if($_SERVER['HTTP_CF_CONNECTING_IP'] == '2406:b400:d5:8015:19c4:8f1e:c820:ffcd'){
			// 	echo '<pre>';
			// 	print_r($BlogSection);
			// }
			$main_blog_title = $title;
			if(isset($BlogSection[0]->title) && trim($BlogSection[0]->title)!=''){
				$main_blog_title = str_replace("\'","'",trim($BlogSection[0]->title));
			}
			foreach($BlogSection as $LPage)
			{
				if(isset($LPage->section_name))
				{
					$LPage->section_name = stripslashes($LPage->section_name);
				}

				
				
				if($LPage->blog_section_id == 1)
				{
					
                 if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img)) and !empty($LPage->main_banner_img)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img);
						$verP = filemtime($newimageVal);
						$LPage->main_banner_img = config('global.SITE_URL').'/images/blog/'.$LPage->main_banner_img."?ver=" . $verP;
					}else{
						$LPage->main_banner_img = '';
					}

					if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->mobile_banner_img)) and !empty($LPage->mobile_banner_img)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->mobile_banner_img);
						$verP = filemtime($newimageVal);
						$LPage->mobile_banner_img = config('global.SITE_URL').'/images/blog/'.$LPage->mobile_banner_img."?ver=" . $verP;
					}else{
						$LPage->mobile_banner_img = '';
					}

				}
				
				if($LPage->blog_section_id == 2 || $LPage->blog_section_id==3 || $LPage->blog_section_id==6 || $LPage->blog_section_id==7)
				{
                  $LPage->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $LPage->banner_description));
                  $LPage->banner_title = stripslashes($LPage->banner_title);
                  $LPage->banner_link = stripslashes($LPage->banner_link);

                  if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img)) and !empty($LPage->main_banner_img)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img);
						$verP = filemtime($newimageVal);
						$LPage->main_banner_img = config('global.SITE_URL').'/images/blog/'.$LPage->main_banner_img."?ver=" . $verP;
					}else{
						$LPage->main_banner_img = '';
					}

				}

				if($LPage->blog_section_id == 10 || $LPage->blog_section_id == 11)
				{
                   $LPage->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $LPage->banner_description));

				}

				if($LPage->blog_section_id == 5)
				{
			         $LPage->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $LPage->banner_description));
                  $LPage->banner_title = stripslashes($LPage->banner_title);
                  $LPage->banner_link = stripslashes($LPage->banner_link);
                  if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img)) and !empty($LPage->main_banner_img)) {
						$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPage->main_banner_img);
						$verP = filemtime($newimageVal);
						$LPage->main_banner_img = config('global.SITE_URL').'/images/blog/'.$LPage->main_banner_img."?ver=" . $verP;
					}else{
						$LPage->main_banner_img = '';
					}

                }

                if($LPage->blog_section_id == 8)
				{
					$SliderProducts= array();
					if($LPage->sku != ''){
						$SliderProducts = $this->GetProductsWithParms($LPage->sku);
					}
					else if($LPage->designerid != '' && $LPage->designerid > 0){
						if($LPage->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('',$LPage->designerid,'',$LPage->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('',$LPage->designerid);
						}
						
						if(count($SliderProducts) <= 0){
							if($LPage->category_id != '' && $LPage->category_id > 0){
								if($LPage->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,$LPage->exclude_sku);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id);
								}
							}
						}
					}
					else if($LPage->category_id != '' && $LPage->category_id > 0){
						if($LPage->exclude_sku != ''){
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id,$LPage->exclude_sku);
						}else{
							$SliderProducts = $this->GetProductsWithParms('','',$LPage->category_id);
						}
						
						if(count($SliderProducts) <= 0){
							if($LPage->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($LPage->sku);
							}
						}
					}
					$Products =array();
					if(isset($SliderProducts['Products']) && count($SliderProducts['Products']) >0)
					{
						$Products = $SliderProducts['Products'];
					}
					$TotalProducts = 0;
					if(isset($SliderProducts['TotalProducts']) && $SliderProducts['TotalProducts'] >0)
					{
						$TotalProducts = $SliderProducts['TotalProducts'];
					}
					$this->PageData['TotalProducts'] = $TotalProducts;
					
					if($TotalProducts > 0)
					{	
						$this->PageData['ProductData'] = $Products;
						$this->PageData['ProductAttr'] = [
							'Title' => '',
							'Slider' => 'home-new-sl',
							'Page' => 'BlogLanding',
							'SeeMore' => '',
						];
					}						
				}				
				
			}
		$this->PageData['BlogSection'] = $BlogSection;
			$Meta = BlogSubLanding::where('main_blogid', '=', $main_blogid)->where('blog_section_id', '=', 4)->get();
             if(count($Meta)>0){
			$this->PageData['meta_title'] = $Meta[0]->meta_title;
			$this->PageData['meta_keywords'] = $Meta[0]->meta_keywords;
			$this->PageData['meta_description'] = $Meta[0]->meta_description;
		}
		}
		$this->PageData['main_blog_title'] = $main_blog_title;		
		return view('staticpages.blog-landing-page')->with($this->PageData);
	}


	public function BlogTemplate(Request $request)
	{
		
		$this->PageData['CSSFILES'] = ['blog.css'];

		return view('staticpages.blogtemplate')->with($this->PageData);
	}
	

}
