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
	
	public function BlogDetailPage(Request $request)
	{
		
		$this->PageData['JSFILES'] = ['blog-landing-pages.js'];
		
		$Filters = [];
		
		if(Cache::has('DefaultMetaInfo'))
        {
            $MetaInfo = Cache::get('DefaultMetaInfo');
            $this->PageData['meta_title'] = $MetaInfo->meta_title;
            $this->PageData['meta_description'] = $MetaInfo->meta_description;
            $this->PageData['meta_keywords'] = $MetaInfo->meta_keywords;
        }
          $main_blogid=$request->id;
          $title=$request->title;
          $this->PageData['title'] = $title;
          $this->PageData['main_blogid'] = $main_blogid;
		  $this->PageData['CSSFILES'] = ['blog.css'];
		//$this->PageData['SliderProducts'] = ['UP3508441001114','UP3760035450184'];
		//$BlogSection = BlogSection::with('PageData')->where('status1','=','1')->where('page_type','=','blog')->orderBy('section_rank')->get();

		//		SELECT `pu_blog_sublanding`.*,`pu_blog_sections`.`section_name`,`pu_blog_sections`.`section_rank`,`pu_blog_sections`.`page_type`,`pu_blog_sections`.`status` as section_status,`pu_blog_landing`.* from `pu_blog_sublanding` left join pu_blog_sections on pu_blog_sections.blog_section_id=pu_blog_sublanding.blog_section_id left join `pu_blog_landing` on pu_blog_landing.id=pu_blog_sublanding.main_blogid where pu_blog_sections.status='1' AND pu_blog_sublanding.main_blogid='1' ORDER BY pu_blog_sections.section_rank


		//$BlogSection = DB::table('pu_blog_sublanding')->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();
		
		$mainBlogData = DB::table('pu_blog_sublanding')->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sections.section_name', 'pu_blog_sections.blog_section_id')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->groupBy('pu_blog_sections.blog_section_id')->get();
		
		//echo "<pre>";print_r($mainBlogData);echo "</pre>";
		
		/*$BlogSection = DB::table('pu_blog_sublanding')->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_landing.status', '=', '1')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();*/
		
		//echo "<pre>";print_r($BlogSection);echo "</pre>";

		$this->PageData['BlogSection'] = [];
		
		if($mainBlogData->count() == 0){
			return redirect('/blog');
		}
		
		if($mainBlogData->count() > 0)
		{
			foreach($mainBlogData as $LPage)
			{
				if(isset($LPage->section_name))
				{
					$LPage->section_name = stripslashes($LPage->section_name);
				}
				
					if($LPage->blog_section_id == 1)
					{
						$Wide_Banner =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Wide_Banner) && $Wide_Banner->count() > 0){
							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes( $Wide_Banner[0]->main_banner_img)) and !empty( $Wide_Banner[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes( $Wide_Banner[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								 $Wide_Banner[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'. $Wide_Banner[0]->main_banner_img."?ver=" . $verP;
							}else{
								 $Wide_Banner[0]->main_banner_img = '';
							}

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes( $Wide_Banner[0]->mobile_banner_img)) and !empty( $Wide_Banner[0]->mobile_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes( $Wide_Banner[0]->mobile_banner_img);
								$verP = filemtime($newimageVal);
								 $Wide_Banner[0]->mobile_banner_img = config('global.SITE_URL').'/images/blog/'. $Wide_Banner[0]->mobile_banner_img."?ver=" . $verP;
							}else{
								 $Wide_Banner[0]->mobile_banner_img = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 10)
					{
						$Banner_Desc =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Banner_Desc) && $Banner_Desc->count() > 0){
							$Banner_Desc[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Banner_Desc[0]->banner_description));
						}

					}
					
					if($LPage->blog_section_id == 11)
					{
						$Coll_Desc =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Coll_Desc) && $Coll_Desc->count() > 0){
							
							$Coll_Desc[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Coll_Desc[0]->banner_description));
							
							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Coll_Desc[0]->main_banner_img)) and !empty($Coll_Desc[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Coll_Desc[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								$Coll_Desc[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'.$Coll_Desc[0]->main_banner_img."?ver=" . $verP;
							}else{
								$Coll_Desc[0]->main_banner_img = '';
							}

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Coll_Desc[0]->banner_img1)) and !empty($Coll_Desc[0]->banner_img1)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Coll_Desc[0]->banner_img1);
								$verP = filemtime($newimageVal);
								$Coll_Desc[0]->banner_img1 = config('global.SITE_URL').'/images/blog/'.$Coll_Desc[0]->banner_img1."?ver=" . $verP;
							}else{
								$Coll_Desc[0]->banner_img1 = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 12)
					{
						$Blocks =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Blocks) && $Blocks->count() > 0){
							foreach($Blocks as $LPageData){
								$LPageData->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $LPageData->banner_description));
								$LPageData->description_title = stripslashes(str_replace(array("\\r","\\n")," ", $LPageData->description_title));
								$LPageData->banner_title = stripslashes($LPageData->banner_title);
								$LPageData->banner_link = stripslashes($LPageData->banner_link);
								$LPageData->banner_link_title = stripslashes($LPageData->banner_link_title);
								
								if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPageData->main_banner_img)) and !empty($LPageData->main_banner_img)) {
									$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPageData->main_banner_img);
									$verP = filemtime($newimageVal);
									$LPageData->main_banner_img = config('global.SITE_URL').'/images/blog/'.$LPageData->main_banner_img."?ver=" . $verP;
								}else{
									$LPageData->main_banner_img = '';
								}
							}
						}
					}
					
					if($LPage->blog_section_id == 2)
					{
						$Right_Banners =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Right_Banners) && $Right_Banners->count() > 0){
						
							$Right_Banners[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Right_Banners[0]->banner_description));
							$Right_Banners[0]->banner_title = stripslashes($Right_Banners[0]->banner_title);
							$Right_Banners[0]->banner_link = stripslashes($Right_Banners[0]->banner_link);

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Right_Banners[0]->main_banner_img)) and !empty($Right_Banners[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Right_Banners[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								$Right_Banners[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'.$Right_Banners[0]->main_banner_img."?ver=" . $verP;
							}else{
								$Right_Banners[0]->main_banner_img = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 3)
					{
						$Left_Banners =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Left_Banners) && $Left_Banners->count() > 0){
						
							$Left_Banners[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Left_Banners[0]->banner_description));
							$Left_Banners[0]->banner_title = stripslashes($Left_Banners[0]->banner_title);
							$Left_Banners[0]->banner_link = stripslashes($Left_Banners[0]->banner_link);

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Left_Banners[0]->main_banner_img)) and !empty($Left_Banners[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Left_Banners[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								$Left_Banners[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'.$Left_Banners[0]->main_banner_img."?ver=" . $verP;
							}else{
								$Left_Banners[0]->main_banner_img = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 6)
					{
						$Right_Small_Banners =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Right_Small_Banners) && $Right_Small_Banners->count() > 0){
						
							$Right_Small_Banners[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Right_Small_Banners[0]->banner_description));
							$Right_Small_Banners[0]->banner_title = stripslashes($Right_Small_Banners[0]->banner_title);
							$Right_Small_Banners[0]->banner_link = stripslashes($Right_Small_Banners[0]->banner_link);

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Right_Small_Banners[0]->main_banner_img)) and !empty($Right_Small_Banners[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Right_Small_Banners[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								$Right_Small_Banners[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'.$Right_Small_Banners[0]->main_banner_img."?ver=" . $verP;
							}else{
								$Right_Small_Banners[0]->main_banner_img = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 7)
					{
						$Left_Small_Banners =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Left_Small_Banners) && $Left_Small_Banners->count() > 0){
						
							$Left_Small_Banners[0]->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $Left_Small_Banners[0]->banner_description));
							$Left_Small_Banners[0]->banner_title = stripslashes($Left_Small_Banners[0]->banner_title);
							$Left_Small_Banners[0]->banner_link = stripslashes($Left_Small_Banners[0]->banner_link);

							if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Left_Small_Banners[0]->main_banner_img)) and !empty($Left_Small_Banners[0]->main_banner_img)) {
								$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($Left_Small_Banners[0]->main_banner_img);
								$verP = filemtime($newimageVal);
								$Left_Small_Banners[0]->main_banner_img = config('global.SITE_URL').'/images/blog/'.$Left_Small_Banners[0]->main_banner_img."?ver=" . $verP;
							}else{
								$Left_Small_Banners[0]->main_banner_img = '';
							}
						}
					}
					
					if($LPage->blog_section_id == 8)
					{
						$Set_Products =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Set_Products) && $Set_Products->count() > 0){
						
							$SliderProducts= array();
							
							if($Set_Products[0]->sku != ''){
								$SliderProducts = $this->GetProductsWithParms($Set_Products[0]->sku,'','','','','',$Filters);
							}
							else if($Set_Products[0]->designerid != '' && $Set_Products[0]->designerid > 0){
								
								if($Set_Products[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('',$Set_Products[0]->designerid,'',$Set_Products[0]->exclude_sku,'','',$Filters);
								}else{
									$SliderProducts = $this->GetProductsWithParms('',$Set_Products[0]->designerid,'','','','',$Filters);
								}

								if(count($SliderProducts) <= 0){
									if($Set_Products[0]->category_id != '' && $Set_Products[0]->category_id > 0){
										if($Set_Products[0]->exclude_sku != ''){
											$SliderProducts = $this->GetProductsWithParms('','',$Set_Products[0]->category_id,$Set_Products[0]->exclude_sku,'','',$Filters);
										}else{
											$SliderProducts = $this->GetProductsWithParms('','',$Set_Products[0]->category_id,'','','',$Filters);
										}
									}
								}
							}
							else if($Set_Products[0]->category_id != '' && $Set_Products[0]->category_id > 0){
								
								if($Set_Products[0]->exclude_sku != ''){
									$SliderProducts = $this->GetProductsWithParms('','',$Set_Products[0]->category_id,$Set_Products[0]->exclude_sku,'','',$Filters);
								}else{
									$SliderProducts = $this->GetProductsWithParms('','',$Set_Products[0]->category_id,'','','',$Filters);
								}

								if(count($SliderProducts) <= 0){
									if($Set_Products[0]->sku != ''){
										$SliderProducts = $this->GetProductsWithParms($Set_Products[0]->sku,'','','','','',$Filters);
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
					
					if($LPage->blog_section_id == 5)
					{
						$Banner_Grid =  DB::table('pu_blog_sublanding')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.blog_section_id', '=', $LPage->blog_section_id)->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sublanding.rank')->get();
						
						if(isset($Banner_Grid) && $Banner_Grid->count() > 0){
							foreach($Banner_Grid as $LPageData){
							
								$LPageData->banner_description = stripslashes(str_replace(array("\\r","\\n")," ", $LPageData->banner_description));
								$LPageData->banner_title = stripslashes($LPageData->banner_title);
								$LPageData->banner_link = stripslashes($LPageData->banner_link);
								
								if (file_exists(config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPageData->main_banner_img)) and !empty($LPageData->main_banner_img)) {
									$newimageVal = config('global.PHYSICAL_PATH').'/images/blog/'.stripslashes($LPageData->main_banner_img);
									$verP = filemtime($newimageVal);
									$LPageData->main_banner_img = config('global.SITE_URL').'/images/blog/'.$LPageData->main_banner_img."?ver=" . $verP;
								}else{
									$LPageData->main_banner_img = '';
								}
							}
						}
					}
				
			}
			
			$Meta = BlogSubLanding::where('main_blogid', '=', $main_blogid)->where('blog_section_id', '=', 4)->get();
             if(count($Meta)>0){
				$this->PageData['meta_title'] = $Meta[0]->meta_title;
				$this->PageData['meta_keywords'] = $Meta[0]->meta_keywords;
				$this->PageData['meta_description'] = $Meta[0]->meta_description;
			}
		}
		$this->PageData['mainBlogData'] = $mainBlogData;
		$this->PageData['Wide_Banner'] = $Wide_Banner;
		$this->PageData['Banner_Desc'] = $Banner_Desc;
		$this->PageData['Coll_Desc'] = $Coll_Desc;
		$this->PageData['Blocks'] = $Blocks;
		$this->PageData['Right_Banners'] = $Right_Banners;
		$this->PageData['Left_Banners'] = $Left_Banners;
		$this->PageData['Right_Small_Banners'] = $Right_Small_Banners;
		$this->PageData['Left_Small_Banners'] = $Left_Small_Banners;
		$this->PageData['Set_Products'] = $Set_Products;
		$this->PageData['Banner_Grid'] = $Banner_Grid;
		
		//dd($this->PageData);
		
		return view('staticpages.blog-landing-page')->with($this->PageData);
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
		
			$BlogSection = DB::table('pu_blog_sublanding')->leftjoin('pu_blog_sections', 'pu_blog_sublanding.blog_section_id', '=', 'pu_blog_sections.blog_section_id')->leftjoin('pu_blog_landing', 'pu_blog_sublanding.main_blogid', '=', 'pu_blog_landing.id')->select('pu_blog_sublanding.*', 'pu_blog_landing.*','pu_blog_sections.section_name', 'pu_blog_sections.section_rank','pu_blog_sections.page_type','pu_blog_sections.status AS section_status')->where('pu_blog_landing.status', '=', '1')->where('pu_blog_sections.status', '=', '1')->where('pu_blog_sublanding.status', '=', '1')->where('pu_blog_sublanding.main_blogid', '=', $main_blogid)->orderBy('pu_blog_sections.section_rank')->orderBy('pu_blog_sublanding.rank')->get();
			
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

	public function BlogTemplate(Request $request)
	{
		
		$this->PageData['CSSFILES'] = ['blog.css'];

		return view('staticpages.blogtemplate')->with($this->PageData);
	}
	

}
