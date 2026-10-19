<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Traits\POSTrait;
use App\Http\Controllers\Traits\StripeTrait;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hash;
use Session;
use App\Models\MetaInfo;
use App\Models\StoreUser;
use App\Models\StorePayment;
use App\Models\Editprofile;
use App\Models\StoreLoginLog;
use App\Models\StoreSalesPerson;
use App\Models\StoreCardReader;

use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

use DB;
use Mail;

use Carbon\Carbon;
// use File;
use Illuminate\Support\Facades\File;

use PDF;
use Cookie;

class POSConnecterController extends Controller
{
	use POSTrait;
	use StripeTrait;

    public function __construct()
    {
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
	}

    public function devicesStatus(Request $request)
	{
		// $stripe = new \Stripe\StripeClient($this->stripeKey);
		// $locations = $stripe->terminal->locations->all();

		if(!auth('store')->user())
		{
			return redirect('store/login');
		}

		// if(!auth('store')->user() || auth('store')->user()->enable_pos != '1')
		// {
		// 	return redirect('store/dashboard');
		// }
		$canonical_url = config('global.SITE_URL')."store/devices";
        $this->PageData['canonical_url'] = $canonical_url;
		## Breadcrumb
		$page_bread_crumb	= '';
		$page_bread_crumb 	.= '<a href="'.config('global.SITE_URL').'">Home</a>';
		$page_bread_crumb 	.= '<span class="active">Devices</span>';

		$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id','=',auth('store')->user()->store_id)->first();
		$this->PageData['reader_store_name'] = $get_store_name->store_name;

		$this->PageData['location_label'] = "";
		$CardReaders = StoreCardReader::where('store_id',auth('store')->user()->store_id)->where('status','1')->get();
		if($CardReaders->count()>0)
		{
			$this->PageData['location_label'] = $CardReaders[0]->location_label;
		}
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		//$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js','StarWebPrintBuilder.js','StarBarcodeEncoder.js','StarWebPrintExtManager.js','StarWebPrintDisplayBuilder.js','StarWebPrintTrader.js','store_devices.js','pos_cashdrawer.js'];
		$this->PageData['JSFILES'] = ['jquery.mobile-1.0rc2.min.js','StarWebPrintBuilder.js','StarBarcodeEncoder.js','StarWebPrintExtManager.js','StarWebPrintDisplayBuilder.js','StarWebPrintTrader.js','store_devices.js','store_device_status.js'];

		$CardReaderStatus = $this->CardReaderStatus();
		$this->PageData['cardReaderStatus'] = $CardReaderStatus;
		return view('pos.devicesStatus')->with($this->PageData);
	}
	public function ConnectNewReader(Request $request)
	{
		$UserCode = $request->user_code;
		$ReaderName = $request->reader_name;
		$LocationID = "";
		if(!Session::has('ConnectedCardReaderLocationID'))
		{
			$CardReaders = StoreCardReader::where('store_id',auth('store')->user()->store_id)->where('status','1')->get();
			if($CardReaders->count()>0)
			{
				$LocationID = $CardReaders[0]->location;
				Session::put('ConnectedCardReaderLocationID',$LocationID);
			}
		} else{
			$LocationID = Session::get('ConnectedCardReaderLocationID');
		}
		$StripeKeyDetail = $this->GetStripeKey();
		if(Auth::guard('web')->check())
        {
            $CustomerEmail = Auth::guard('web')->user()->email;
        }
		try {
			$stripe = new \Stripe\StripeClient($StripeKeyDetail['STRIPE_KEY']);
			$reader = $stripe->terminal->readers->create([
				'location' => $LocationID,
				'registration_code' => $UserCode
			]);

			if(isset($reader->id))
			{
				$get_store_name = DB::table('pu_stores')->select('store_name')->where('store_id','=',auth('store')->user()->store_id)->first();

				$GetFirstReaderEntry = StoreCardReader::where('store_id','=',auth('store')->user()->store_id)
					->where(function ($query) {
    					$query->whereNull('reader_id')
          				->orWhere('reader_id', '');
					})->first();
				if(isset($GetFirstReaderEntry))
				{
					$addedBy = $GetFirstReaderEntry->added_by."#".Auth::guard('store')->user()->store_user_email;
					$UpdateReader = $GetFirstReaderEntry->update(['reader_id' => $reader->id,'reader_name' => $ReaderName, 'added_by' => $addedBy]);
					if($UpdateReader)
					{
						Session::put('ConnectedCardReaderId',$reader->id);
						Session::put('ConnectedCardReaderName',$ReaderName);
						Session::put('ConnectedCardReaderLocationID',$LocationID);
						Session::flash('deviceSuccess', 'New Card Reader has been added successfully.');
					} else {
						Session::flash('deviceError','Something went wrong. Please try again.');
					}
				} else {
					$readerData = [
						'reader_id' => $reader->id,
						'reader_name' => $ReaderName,
						'store_id' => Auth::guard('store')->user()->store_id,
						'location' => $LocationID,
						'location_label' => $get_store_name->store_name,
						'added_by' => Auth::guard('store')->user()->store_user_email,
					];
					$ReaderCreate = StoreCardReader::create($readerData);
					if($ReaderCreate)
					{
						Session::put('ConnectedCardReaderId',$reader->id);
						Session::put('ConnectedCardReaderName',$ReaderName);
						Session::put('ConnectedCardReaderLocationID',$LocationID);
						Session::flash('deviceSuccess', 'New Card Reader has been added successfully.');
					} else {
						Session::flash('deviceError','Something went wrong. Please try again.');
					}
				}
			}
			$message = $reader->toArray();
			$this->ReaderLog('CREATE_READER',json_encode($message));
		}catch (ApiErrorException $e) {
			$message = $e->getHttpStatus()."#".$e->getStripeCode()."#".$e->getMessage();
			$this->ReaderLog('CREATE_READER',$message);
			Session::flash('deviceError',$e->getMessage());
			return response()->json(['error' =>$e->getMessage()]);
		} catch (\Exception $e) {
			$this->ReaderLog('CREATE_READER',$e->getMessage());
			Session::flash('deviceError','Something went wrong: ' . $e->getMessage());
			return back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}
	public function CardReaderStatus()
	{
		$deviceStatus = "";
		$ReaderStatus = $this->GetCardReaderStatus();

		$changeCardReaderLink = "";
		$CardReaders = StoreCardReader::where('store_id',auth('store')->user()->store_id)->where('status','1')->get();
		if($CardReaders->count() > 1)
		{
			$cardreader_selectbox = '<div id="change_cardreader_div" style="display:none;">';
			$cardreader_selectbox.= '<input type="hidden" name="selcardreader" id="selcardreader" value="' . Session::get('ConnectedCardReaderId') .'">';
			$cardreader_selectbox .= '<select class="form-control mt-2" name="sel_cardreader_id" id="sel_cardreader_id">';
			foreach($CardReaders as $card_reader)
			{
				$cardreader_selectbox .= '<option value="'.$card_reader->reader_id.'" '.($card_reader->reader_id == Session::get('ConnectedCardReaderId') ? 'selected' : '').' >'.$card_reader->reader_name.'</option>';
			}
			$cardreader_selectbox .= '</select>';
			$cardreader_selectbox .= '<a href="javascript:void(0);" id="btnChangeCardReader" class="btn-sm btn-primary mt-3 mb-2 p-2 pl-4 pr-4 d-inline-block">Save</a>&nbsp;&nbsp;
			<a href="javascript:void(0);" id="btnCancelChangeCardReader" class="btn-sm btn-secondary mt-3 mb-2 p-2 pl-3 pr-3 d-inline-block">Cancel</a>';
			$cardreader_selectbox .= '</div>';

			$changeCardReaderLink = '<a href="javascript:void(0);" class="posstatus-link" id="change_cardreader_link">Change</a>'.$cardreader_selectbox;
		}

		if($ReaderStatus['status'] == 'ONLINE')
		{
			$deviceStatus = '
			<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$ReaderStatus['reader_name'].'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>
			<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:green" id="cardReaderStatus">ONLINE</span></span></p>';
		} else {
			$deviceStatus="";
			if(isset($ReaderStatus['reader_name']) && !empty($ReaderStatus['reader_name']))
			{
				$deviceStatus.= '
				<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$ReaderStatus['reader_name'].'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>';
				$deviceStatus.='
				<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">NOT CONNECTED</span></span></p>';
			} elseif(Session::has('ConnectedCardReaderName') && !empty(Session::get('ConnectedCardReaderName')))
			{
				$deviceStatus.= '
				<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.Session::get('ConnectedCardReaderName').'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>';
				$deviceStatus.='
				<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">NOT CONNECTED</span></span></p>';
			} else {
				$deviceStatus.='
				<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">NOT CONNECTED</span></span></p>';
			}
		}

		$deviceStatus .= '<p class="mb-0 mt-2" id="newCardReader"><a href="javascript:void(0);" data-toggle="modal" data-target="#connect-card-reader" class="posstatus-link">Connect New Card Reader</a></p>';
		return $deviceStatus;
	}
	/*
	public function CardReaderStatus()
	{
		try{
			if(Session::has('ConnectedCardReaderId') && !empty(Session::get('ConnectedCardReaderId')))
			{
				$stripe = new \Stripe\StripeClient($this->stripeKey);
				$selReader = Session::get('ConnectedCardReaderId');
				$selReaderName = Session::get('ConnectedCardReaderName');
				$reader = $stripe->terminal->readers->retrieve($selReader, []);
				$changeCardReaderLink = "";
				if($reader->status == 'online')
				{
					$deviceStatus = '
					<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$selReaderName.'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>
					<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:green" id="cardReaderStatus">ONLINE</span></span></p>';
				} else {
					$deviceStatus = '
					<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$selReaderName.'</span></span>&nbsp;&nbsp;&nbsp;</p>
					<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
				}

			} else {

				$CardReaders = StoreCardReader::where('store_id',auth('store')->user()->store_id)->where('status','1')->get();
				if($CardReaders->count() > 0)
				{
					$stripe = new \Stripe\StripeClient($this->stripeKey);
					if(Session::has('ConnectedCardReaderId') && !empty(Session::get('ConnectedCardReaderId')))
					{
						$selReader = Session::get('ConnectedCardReaderId');
						$selReaderName = Session::get('ConnectedCardReaderName');
						$reader = $stripe->terminal->readers->retrieve($selReader, []);
					} else {
						$reader = $stripe->terminal->readers->retrieve($CardReaders[0]->reader_id, []);
						$selReader = $CardReaders[0]->reader_id;
						$selReaderName = $CardReaders[0]->reader_name;
						Session::put('ConnectedCardReaderId',$selReader);
						Session::put('ConnectedCardReaderName',$selReaderName);
					}

					if(!empty($selReader))
					{
						$InactiveOtherDevices = StoreCardReader::where('store_id',auth('store')->user()->store_id)
							->where('reader_id','!=',$selReader)
							->update(['status'=>'0']);
					}
					$changeCardReaderLink = "";

					if($CardReaders->count() > 1)
					{
						$cardreader_selectbox = '<div id="change_cardreader_div" style="display:none;">';
						$cardreader_selectbox .= '<select class="form-control mt-2" name="sel_cardreader_id" id="sel_cardreader_id">';
						foreach($CardReaders as $card_reader)
						{
							$cardreader_selectbox .= '<option value="'.$card_reader->reader_id.'" '.($card_reader->reader_id == $selReader ? 'selected' : '').' >'.$card_reader->reader_name.'</option>';
						}
						$cardreader_selectbox .= '</select>';
						$cardreader_selectbox .= '<a href="javascript:void(0);" id="btnChangeCardReader" class="btn-sm btn-primary mt-3 mb-2 p-2 pl-4 pr-4 d-inline-block">Save</a>&nbsp;&nbsp;
						<a href="javascript:void(0);" id="btnCancelChangeCardReader" class="btn-sm btn-secondary mt-3 mb-2 p-2 pl-3 pr-3 d-inline-block">Cancel</a>';
						$cardreader_selectbox .= '</div>';

						$changeCardReaderLink = '<a href="javascript:void(0);" class="posstatus-link" id="change_cardreader_link">Change</a>'.$cardreader_selectbox;
					}

					if($reader->status == 'online')
					{
						$deviceStatus = '
						<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$selReaderName.'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>
						<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:green" id="cardReaderStatus">ONLINE</span></span></p>';
					} else {
						$deviceStatus = '
						<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$selReaderName.'</span></span>&nbsp;&nbsp;&nbsp;</p>
						<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
					}
				} else {
					$deviceStatus = '<p class="mb-0">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">NOT CONNECTED</span></span></p>';
				}
			}
		} catch (ApiErrorException $e) {
			Session::flash('deviceError',$e->getMessage());
			$deviceStatus = '<p class="mb-0">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
		} catch (\Exception $e) {
			Session::flash('deviceError','Something went wrong: ' . $e->getMessage());
			$deviceStatus = '<p class="mb-0">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
		}

		$deviceStatus .= '<p class="mb-0 mt-2" id="newCardReader"><a href="javascript:void(0);" data-toggle="modal" data-target="#connect-card-reader" class="posstatus-link">Connect New Card Reader</a></p>';
		return $deviceStatus;
	}
	*/
	public function ChangeCardReader(Request $request)
	{
		$selReader = $request->sel_cardreader_id;
		$selReaderName = $request->sel_cardreader_name;
		Session::put('ConnectedCardReaderId',$selReader);
		Session::put('ConnectedCardReaderName',$selReaderName);

		$CardReaderStatus = $this->GetCardReaderStatus(1);
		return response()->json($CardReaderStatus);
	}
    public function POSCardReaderStatus(){

		try{

			$all_bt_res = DB::table($this->prefix.'store_card_reader')
									->select('*')
									->where('store_id','=',auth('store')->user()->store_id)
									->get();
			$store_cardreader_count = $all_bt_res->count();

			if(session()->has('store_ipad_device_id') && !empty(session()->get('store_ipad_device_id')))
			{
				$bt_res = DB::table($this->prefix.'store_card_reader AS cr')
									->select('cr.*','icrm.store_ipad_device_id')
									->join($this->prefix.'store_ipad_card_reader_map as icrm', 'icrm.store_card_reader_id', '=', 'cr.store_card_reader_id')
									->where('cr.store_id','=',auth('store')->user()->store_id)
									->where('icrm.store_ipad_device_id','=',session()->get('store_ipad_device_id'))
									->orderBy('cr.store_card_reader_id')
									->first();
			}
			if(!isset($bt_res) || empty($bt_res))
			{
				$bt_res = DB::table($this->prefix.'store_card_reader')
									->select('*')
									->where('store_id','=',auth('store')->user()->store_id)
									->orderBy('store_card_reader_id')
									->first();
			}

			if(isset($bt_res) && !empty($bt_res)) {

				$store_card_reader_id = $bt_res->store_card_reader_id;
				$clientMutationId=$bt_res->client_mutation_id;
				$cardReaderId=$bt_res->reader_id;
				$cardReaderName=$bt_res->reader_name;
				// Prepare the variables for the GraphQL query as a PHP array
				$variables = [
					'readerId' => $cardReaderId
				];

				// Prepare the GraphQL query as a string
				$requestSearchInputQuery = <<<PINGINSTOREREADER
				query PingInStoreReader(\$readerId: ID!) {
					pingInStoreReader(readerId: \$readerId) {
						id
						name
						status
						pairedAt
						lastSeenAt
						offlineSince
						softwareVersion
						location {
							id
							name
							internalName
							address {
								streetAddress
								extendedAddress
								locality
								region
								postalCode
								countryCode
							}
						}
					}
				}
				PINGINSTOREREADER;

				\Log::channel('POSpayment')->info('ConnectReader RequestInput : '.json_encode($variables));
				// Make the request with headers and payload
				$response = Http::withHeaders($this->headers)->post($this->endpoint, [
					'query' => $requestSearchInputQuery,  // The GraphQL query string
					'variables' => $variables,           // The variables array
				]);

				\Log::channel('POSpayment')->info('ConnectReader RequestInput : '.$response);

				$changeCardReaderLink = '';
				if($store_cardreader_count > 1)
				{
					$cardreader_selectbox = '<div id="change_cardreader_div" class="d-none">';
					$cardreader_selectbox .= '<select class="form-control mt-2" name="sel_cardreader_id" id="sel_cardreader_id">';
					foreach($all_bt_res as $card_reader)
					{
						 $cardreader_selectbox .= '<option value="'.$card_reader->store_card_reader_id.'" '.($card_reader->store_card_reader_id == $store_card_reader_id ? 'selected' : '').' >'.$card_reader->reader_name.'</option>';
					}
					$cardreader_selectbox .= '</select>';
					$cardreader_selectbox .= '<a href="javascript:void(0);" onclick="javascript:saveChangeCardReader();" class="btn-sm btn-primary mt-3 mb-2 p-2 pl-4 pr-4 d-inline-block">Save</a>&nbsp;&nbsp;
					<a href="javascript:void(0);" onclick="javascript:cancelChangeCardReader();" class="btn-sm btn-secondary mt-3 mb-2 p-2 pl-3 pr-3 d-inline-block">Cancel</a>';
					$cardreader_selectbox .= '</div>';

					$changeCardReaderLink = '<a href="javascript:void(0);" onclick="javascript:showChangeCardReaderDiv();" class="posstatus-link" id="change_cardreader_link">Change</a>'.$cardreader_selectbox;

				}

				// Decode the response
				$responseData = json_decode($response, true);
				if(isset($responseData['data']['pingInStoreReader'])) {
					$status = '
					<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$cardReaderName.'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>
					<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:green" id="cardReaderStatus">'.$responseData['data']['pingInStoreReader']['status'].'</span></span></p>';
				} else {
					$status = '
					<p class="mb-0">Device Name: <span class="posstatus-span"><span style="color:black">'.$cardReaderName.'</span></span>&nbsp;&nbsp;&nbsp;'.$changeCardReaderLink.'</p>
					<p class="mb-0" id="card_reader_status_div">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
				}

			} else {
				$status = '<p class="mb-0">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">NOT CONNECTED</span></span></p>';
			}
			$status .= '<p class="mb-0 mt-2" id="newCardReader"><a href="javascript:void(0);" onclick="javascript:connectCardReaderPopup();" class="posstatus-link">Connect New Card Reader</a></p>';
		}catch(\Exception $e){
			$status = '<p class="mb-0">Status: <span class="posstatus-span"><span style="color:red" id="cardReaderStatus">OFFLINE</span></span></p>';
		}
		return $status;
	}
}
