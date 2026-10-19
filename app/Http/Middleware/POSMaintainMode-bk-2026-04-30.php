<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\StoreUser;
use DB;
class POSMaintainMode
{
    public function handle(Request $request, Closure $next)
    {
        if (request()->cookie('pos_maxaroma_token') && $request->route()->getName() != 'store_logout')
		{
			setcookie(
				"pos_maxaroma_token",
				request()->cookie('pos_maxaroma_token'),
				[
					'expires' => time() + (24 * 60 * 60),
					'path' => '/',
					'secure' => true,
					'httponly' => true,
					'samesite' => 'Strict'
				]
			);
            //$this->LoginFromCookiee();
		}
        $maintenanceMode = config('global.POSMode'); // or any other way you define your global variable

        if (!$maintenanceMode) {
            return response('Please contact admin for this', 503);
        }

		$shared_isshow_dailyopen_pending = 2;
		$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', Session::get('sess_store_id'))->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
		//dd($cashd_res);
		if (empty($cashd_res)) {
			$shared_isshow_dailyopen_pending = 1;
		}
		//echo "Test - ".$shared_isshow_dailyopen_pending;

		$request->attributes->set('shared_isshow_dailyopen_pending', $shared_isshow_dailyopen_pending);
		view()->share('shared_isshow_dailyopen_pending', $shared_isshow_dailyopen_pending);

		$response = $next($request);

		$shared_isshow_dailyopen_pending = $request->input('shared_isshow_dailyopen_pending');

        return $response;
    }

    public function LoginFromCookiee()
	{
		if (Cookie::has('pos_maxaroma_token') && !empty(Cookie::get('pos_maxaroma_token')))
		{
			$email = base64_decode(Cookie::get('pos_maxaroma_token'));
			$StoreUserSecurity = StoreUser::where('store_user_email', '=', $email)->where('store_user_status', '=', '1')->first();

			if ($StoreUserSecurity)
			{
				Auth::guard('store')->login($StoreUserSecurity, true);
				//Session::regenerate();

				Session::put('sess_storeuseremail', $StoreUserSecurity->store_user_email);
				Session::put('sess_storeusername', $StoreUserSecurity->first_name);
				Session::put('sess_storeuserid', $StoreUserSecurity->store_user_id);
				Session::put('sess_store_id', $StoreUserSecurity->store_id);

				/*$isshow_dailyopen_pending = 2;
				$cashd_res = DB::table('pu_store_cash_drawer')->select('*')->where('store_id', $StoreUserSecurity->store_id)->where(DB::raw("(DATE_FORMAT(added_datetime,'%m-%d-%Y'))"), date("m-d-Y"))->first();
				if (empty($cashd_res)) {
					$isshow_dailyopen_pending = 1;
				}
				Session::put('isshow_dailyopen_pending', $isshow_dailyopen_pending);*/

				$storeUserFname = '';
				if (isset($StoreUserSecurity->first_name) &&  $StoreUserSecurity->first_name != '') {
					$storeUserFname = $StoreUserSecurity->first_name;
				}
				$storeUserlname = '';
				if (isset($StoreUserSecurity->last_name) && $StoreUserSecurity->last_name != '') {
					$storeUserlname = $StoreUserSecurity->last_name;
				}
				Session::put('sess_storeusername', $storeUserFname . "|" . $storeUserlname);

				//Manage Admin Session Start

				$result = DB::table('pu_store_user as su')
					->join('pu_admin as a', 'su.store_user_email', '=', 'a.email')
					->join('pu_admin_roles as r', 'a.role_id', '=', 'r.role_id')
					->where('su.store_user_id', $StoreUserSecurity->store_user_id)
					->select(
						'su.store_user_id',
						'su.store_id',
						'su.first_name',
						'su.last_name',
						'a.admin_id',
						'a.email',
						'a.admin_type',
						'a.role_id',
						'r.name as role_name',
						'r.user_type'
					)->first();
				ini_set('session.gc_maxlifetime', 7200);

				$name = "pos_maxaroma";
				$value = "store_pos";
				$week = time() + (24 * 60 * 60);
				setcookie($name, $value, $week, "/", ".maxaroma.com", true, true);
				$pos_token = base64_encode($StoreUserSecurity->store_user_email);
				setcookie("pos_maxaroma_token",$pos_token,time() + (24 * 60 * 60),"/",".maxaroma.com",true,true);

				$CheckStoreCardReader = DB::table('pu_store_card_reader')->where('store_id', $StoreUserSecurity->store_id)->where('status', '1')->first();
				if ($CheckStoreCardReader) {
					if (!empty($CheckStoreCardReader->reader_id) && !empty($CheckStoreCardReader->location)) {
						Session::put('ConnectedCardReaderId', $CheckStoreCardReader->reader_id);
						Session::put('ConnectedCardReaderName', $CheckStoreCardReader->reader_name);
						Session::put('ConnectedCardReaderLocationID', $CheckStoreCardReader->location);
					}
				}
				//Manage Admin Session End
			}
		}
	}
}

?>
