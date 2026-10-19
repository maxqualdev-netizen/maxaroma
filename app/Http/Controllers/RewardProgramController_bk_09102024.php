<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MetaInfo;
use App\Models\RewardProgram;
use App\Models\LandingPages;
use App\Models\LandingPagesData;
use App\Models\FAQS;
use DB;
use Session;
use Cache;

class RewardProgramController extends Controller
{
		
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
	
	public function RewardProgram(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css','static.css'];
		$RewardProgram = RewardProgram::where('status','=','1')->orderBy('section_rank')->get();
		
		$RewardProgram11 =  $RewardProgram;
		$this->PageData['RewardProgram'] = [];
		
		
		if($RewardProgram->count() > 0)
		{
			foreach($RewardProgram as $RPage)
			{
				$RPage->section_title = html_entity_decode(trim($RPage->section_title));
				if($RPage->section_id == 2 || $RPage->section_id == 4)
				{
					$RPage->value1 = stripcslashes(trim($RPage->value1));
				}
				if($RPage->section_id != 1 && $RPage->section_id != 2 && $RPage->section_id != 3 && $RPage->section_id != 4 && $RPage->section_id != 5)
				{
					$pointsArray = explode(' ',trim($RPage->section_header));
					$RPage->points = $pointsArray[0];
				}
				
				if (file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($RPage->image_name)) and !empty($RPage->image_name)) {
					$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($RPage->image_name);
					$verP = filemtime($newimageVal);
					$RPage->image_name = config('global.SITE_URL').'/images/'.$RPage->image_name."?ver=" . $verP;
				}else{
					$RPage->image_name = '';
				}
				
				if (file_exists(config('global.PHYSICAL_PATH').'/images/'.stripslashes($RPage->mobile_image_name)) and !empty($RPage->mobile_image_name)) {
					$newimageVal = config('global.PHYSICAL_PATH').'/images/'.stripslashes($RPage->mobile_image_name);
					$verP = filemtime($newimageVal);
					$RPage->mobile_image_name = config('global.SITE_URL').'/images/'.$RPage->mobile_image_name."?ver=" . $verP;
				}else{
					if($RPage->image_name != ''){
						$RPage->mobile_image_name = $RPage->image_name;
					}else{
						$RPage->mobile_image_name = '';
					}
				}
			}
			//list($RewardProgram, $subArray) = $RewardProgram->chunk(5);
			
			$RewardProgram = $RewardProgram11->where('is_main_section','=','Yes');
			$subArray = $RewardProgram11->where('is_main_section','=','No');
			
			$this->PageData['RewardProgram'] = $RewardProgram;
			
			$this->PageData['RewardProgram11'] = $RewardProgram11;
			
			$this->PageData['subArray'] = $subArray;
		}
		if(isset($_GET["isnew"]) && $_GET["isnew"]=="Yes")
		{
			return view('staticpages.reward-program-new')->with($this->PageData);
		}
		else
		{	
			return view('staticpages.reward-program')->with($this->PageData);
		}
	}

	public function ReferralProgram(Request $request)
	{
		
		if(Cache::has('DefaultMetaInfo'))
        {
            $MetaInfo = Cache::get('DefaultMetaInfo');
            $this->PageData['meta_title'] = $MetaInfo->meta_title;
            $this->PageData['meta_description'] = $MetaInfo->meta_description;
            $this->PageData['meta_keywords'] = $MetaInfo->meta_keywords;
        }
		
		$this->PageData['CSSFILES'] = ['myaccount.css','static.css'];
		
		$pageData = LandingPagesData::whereHas('landingPage', function($q){$q->where(['status' => '1', 'page_type' => 'yotpo-referral']);})->orderBy('rank')->get();
		
		$this->PageData['MainBanner'] = $pageData->where('section_id', 14)->where('data_id', 44)->first();
		
		if(isset($this->PageData['MainBanner']) && $this->PageData['MainBanner']->count() > 0){
			if (file_exists(config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($this->PageData['MainBanner']->banner)) and !empty($this->PageData['MainBanner']->banner)) {
				$newimageVal = config('global.PHYSICAL_PATH').'/images/landing/'.stripslashes($this->PageData['MainBanner']->banner);
				$verP = filemtime($newimageVal);
				$this->PageData['MainBanner']->banner = config('global.SITE_URL').'/images/landing/'.$this->PageData['MainBanner']->banner."?ver=" . $verP;
			}else{
				$this->PageData['MainBanner']->banner = '';
			}
		}
		
		$this->PageData['RightSection'] = $pageData->where('section_id', 18)->where('data_id', 45)->first();
		
		$PData = FAQS::where('status','=','1')->where('type','=','Yotpo-Referral')->orderBy('rank')->get();
		
		if($PData->count() > 0)
		{
			foreach($PData as $key => $Data)
			{
				//dd($Data->question);
				$Data->question = ltrim($Data->question,"<p>");
				$Data->question = rtrim($Data->question,"</p>");
				$Data->answer = $Data->answer;
			}
		}
		$this->PageData['PData'] = $PData;
		
		$this->PageData['PageTitle'] = 'Referral Program';
		
		$Meta = $pageData->where('section_id', 21)->where('data_id', 58)->first();
		
		if($Meta->count() > 0)
		{
			$this->PageData['meta_title'] = $Meta->banner_title;
			$this->PageData['meta_keywords'] = $Meta->banner_link;
			$this->PageData['meta_description'] = $Meta->banner_desc;
		}
		return view('staticpages.referral-program-new')->with($this->PageData);
		
		/*if(isset($_GET["is_new"]) && $_GET["is_new"]=="Y")
		{
			return view('staticpages.referral-program-new')->with($this->PageData);
		} else {
			return view('staticpages.referral-program')->with($this->PageData);
		}*/	
	}
}
