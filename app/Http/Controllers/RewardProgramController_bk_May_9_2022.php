<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MetaInfo;
use App\Models\RewardProgram;
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
			}
			//list($RewardProgram, $subArray) = $RewardProgram->chunk(5);
			
			$RewardProgram = $RewardProgram11->where('is_main_section','=','Yes');
			$subArray = $RewardProgram11->where('is_main_section','=','No');
			
			$this->PageData['RewardProgram'] = $RewardProgram;
			
			$this->PageData['RewardProgram11'] = $RewardProgram11;
			
			$this->PageData['subArray'] = $subArray;
		}
			
		return view('staticpages.reward-program')->with($this->PageData);
	}
}
