<?php
$messageee = $_SERVER['HTTP_REFERER'].''.serialize($_POST).''.serialize($_GET);
mail("ravi.qualdev@gmail.com","Alert Stripe Webhook Second",$messageee);
exit;

	include_once('../lib/configuration.php');
	
	$data_response = file_get_contents('php://input');
	
	$data_response_array = json_decode($data_response,true);
	$paymentintentid = $data_response_array['data']['object']['payment_intent'];

	$client_reference_id = $data_response_array['data']['object']['client_reference_id'];


	if(preg_match('/FUND-PU-/i',$client_reference_id))
	{
		$updAray = array (
						'webhook_response' 	   		=> $data_response,
						'webhook_event' 	   		=> $data_response_array['type'],
						'payment_gateway_response' 	=> serialize($data_response)
					 );

		if($data_response_array['type'] == "checkout.session.completed" || $data_response_array['type'] == "payment_intent.succeeded"){
			$stripesessionid = $data_response_array['data']['object']['id'];
			
			$sql = "SELECT customer_id,fund_amount,status FROM ".TABLE_PREFIX."authorize_fund_logs WHERE stripesessionid='".$stripesessionid."' AND paymentintentid='".$paymentintentid."' and status='Sent To Stripe'";
			$AuthRes = $obj->select($sql);

			if(!empty($AuthRes) && count($AuthRes)>0){
				$updAray['transaction_info'] = "This transaction has been approved.";
				$updAray['status']      = 'Completed';
				$updAray['pay_status']  = 'Paid';
				$updOrder = $obj->update(TABLE_PREFIX.'authorize_fund_logs', $updAray, " stripesessionid='".$stripesessionid."' and paymentintentid='".$paymentintentid."' ");
		
				$fundsres = $obj->select("select available_funds from ".TABLE_PREFIX."customer where customer_id = '".$AuthRes[0]["customer_id"]."'");
				$total_available_funds = $fundsres[0]['available_funds'] + $AuthRes[0]["fund_amount"];

				$where = "customer_id= '".$AuthRes[0]["customer_id"]."'";
				$custdata = array(
					"available_funds"=>$total_available_funds
				);
				$custId = $obj->update(TABLE_PREFIX.'customer', $custdata, $where);
			}
		}else{
			// $paymentintentid = $data_response_array['data']['object']['id'];
			// $updAray['transaction_info'] = "This transaction has been sent to stripe.";
			// $updAray['status']  = 'Sent To Stripe';
			// $updOrder = $obj->update(TABLE_PREFIX.'authorize_fund_logs', $updAray, " paymentintentid='".$paymentintentid."' ");
		}
	}
	else
	{
		if($data_response_array['type'] == "charge.succeeded"){
			$Is3DSecure = "No";
			
			$data_threedsecure = $data_response_array['data']['object']['payment_method_details']['card']['three_d_secure'];
			if(!is_null($data_threedsecure) && $data_threedsecure != ""){
				if($data_threedsecure['authenticated'] == 1 && $data_threedsecure['succeeded'] == 1){
					$Is3DSecure = "Yes";
				}
			}
			$updAray = array (
							'TransResponse3D'	   => $data_response,
							'Is3DSecure'	   => $Is3DSecure,
						 );
			$updOrder = $obj->update(TABLE_PREFIX.'orders', $updAray, " paymentintentid='".$paymentintentid."' ");
			
		}else{
			$updAray = array (
							'transaction_info'	   => serialize($data_response),
							'webhook_response' 	   => $data_response,
							'webhook_event' 	   => $data_response_array['type']
						 );
			
			if($data_response_array['type'] == "checkout.session.completed" || $data_response_array['type'] == "payment_intent.succeeded"){
				$updAray['status']      = 'Pending';
				$updAray['pay_status']  = 'Paid';
				$stripesessionid = $data_response_array['data']['object']['id'];
				
				$sql = "SELECT orders_id FROM ".TABLE_PREFIX."orders WHERE stripesessionid='".$stripesessionid."' and paymentintentid='".$paymentintentid."'";
				$order_data = $obj->select($sql);
				
				if(!empty($order_data) && $order_data[0]['orders_id'] > 0){
					$updOrder = $obj->update(TABLE_PREFIX.'orders', $updAray, " stripesessionid='".$stripesessionid."' and paymentintentid='".$paymentintentid."' ");
				}else{
					$sql = "SELECT orders_id FROM ".TABLE_PREFIX."orders WHERE orders_no='".$client_reference_id."'";
					$order_data = $obj->select($sql);
					
					if(!empty($order_data) && $order_data[0]['orders_id'] > 0){
						$updAray['stripesessionid']  = $stripesessionid;
						$updAray['paymentintentid']  = $paymentintentid;
						
						$updOrder = $obj->update(TABLE_PREFIX.'orders', $updAray, " orders_no='".$client_reference_id."'");
					}
				}
			}else if($data_response_array['type'] == "checkout.session.expired"){
				// echo "sasa";exit;
				/* $sql = "SELECT orders_id,bill_email FROM ".TABLE_PREFIX."orders WHERE paymentintentid='".$paymentintentid."' and status='Sent To Stripe'";
				$order_data = $obj->select($sql);
				
				if((!empty($order_data) && $order_data[0]['orders_id'] > 0) || 1 == 1){
					$data_obj = $data_response_array['data']['object'];
					
					$res_mail = $generalobj->Get_Mail_Template("STRIPE_SESSION_RECOVERY");	
					
					$res_mail[0]['subject'] = str_replace("{$SITE_NAME}",SITE_TITLE,$res_mail[0]['subject']);
					$res_mail[0]['mail_body'] = str_replace("{$SITE_NAME}",SITE_TITLE,$res_mail[0]['mail_body']);
					$res_mail[0]['mail_body'] = str_replace("{$freeshippinginfo}",$freeshippinginfo,$res_mail[0]['mail_body']);
					$res_mail[0]['mail_body'] = str_replace("{$stripe_url}",$data_obj['after_expiration']['recovery']['url'],$res_mail[0]['mail_body']);
					$res_mail[0]['mail_body'] = str_replace("{$SITE_NAME}",SITE_TITLE,$res_mail[0]['mail_body']);
					$res_mail[0]['mail_body'] = str_replace("{$Site_URL}",$Site_URL,$res_mail[0]['mail_body']);
					$res_mail[0]['mail_body'] = str_replace("{$CONTACT_MAIL}",CONTACT_MAIL,$res_mail[0]['mail_body']);
					
					$to_email = $order_data[0]['bill_email'];
					
					$to_email = "dip.qualdev@gmail.com";
					$rtn = Email_Send($to_email, $res_mail[0]['subject'], $res_mail[0]['mail_body'], ADMIN_MAIL,'text/html');
				} */
			}else{
				$updAray['status']  = 'Declined';
				$paymentintentid = $data_response_array['data']['object']['id'];
				
				$sql = "SELECT orders_id,bill_first_name,bill_last_name,bill_email,orders_no FROM ".TABLE_PREFIX."orders WHERE paymentintentid='".$paymentintentid."' and status='Sent To Stripe'";
				$order_data = $obj->select($sql);
				
				
				
				
				
				
				if(!empty($order_data) && $order_data[0]['orders_id'] > 0){
					$updOrder = $obj->update(TABLE_PREFIX.'orders', $updAray, " paymentintentid='".$paymentintentid."' ");
				}else{
					$sql = "SELECT orders_id,bill_first_name,bill_last_name,bill_email,orders_no FROM ".TABLE_PREFIX."orders WHERE orders_no='".$client_reference_id."' and status='Sent To Stripe'";
					$order_data = $obj->select($sql);
					
					if(!empty($order_data) && $order_data[0]['orders_id'] > 0){
						$updAray['paymentintentid']  = $paymentintentid;
						
						$updOrder = $obj->update(TABLE_PREFIX.'orders', $updAray, " orders_no='".$client_reference_id."'");
					}
				}
								
				
				if(!empty($order_data) && $order_data[0]['orders_id'] > 0){
					
					$customerEmail = '';
					if(isset($order_data[0]['bill_email']) && $order_data[0]['bill_email']!='')
					{
						$customerEmail	= $order_data[0]['bill_email'];
					}
					
					$orderNo		= $order_data[0]['orders_no'];
					$customerName 	= '';
					if((isset($order_data[0]['bill_first_name']) && $order_data[0]['bill_first_name']!=''))
					{
						$customerName	= $order_data[0]['bill_first_name']." ";
					}
					
					if(isset($order_data[0]['bill_last_name']) && $order_data[0]['bill_last_name']!='')
					{
						$customerName = $customerName.$order_data[0]['last_name'];
					}
					
					if($paymentintentid!='')
					{
						$Data = ['toMail' => $customerEmail, 'orderno' => $orderNo, 'customer_name' => $customerName];
						manisendRequest('63c113f03d897d0020e8b31e',$Data);
						
						$Data = ['toMail' => 'naresh.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
						OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
					
						$Data = ['toMail' => 'ravi.qualdev@gmail.com', 'orderno' => $orderNo, 'customer_name' => $customerName];
						OmanisendRequest('63c113f03d897d0020e8b31e',$Data);
					}
				}
			}
		}
	}
	function OmanisendRequest($RequestType='',$Data=[],$OtherData=[])
	{
		$ApiURL = "https://api.omnisend.com/v3/";
		$ApiType = "POST";
		$RequestData = [];
		$NoAction = 0;
		$omnisend_accountid = "";	
		$ApiURL.="events/".$RequestType;
		$RequestData = [];
		$RequestData = ['email' => $Data['toMail'], //'qualdev.devs@gmail.com'
			'fields' => [
				'orders_no' => $Data['orderno'],
				'customer_name' => $Data['customer_name'],
				'SITE_NAME' => "MaxAroma",
				'CONTACT_MAIL' => "CustomerCare@maxaroma.com"
			]
		];	
		$CurlSetup = array(
							CURLOPT_URL => $ApiURL, 
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 30,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => $ApiType,
							CURLOPT_HTTPHEADER => array(
							"X-API-KEY: 61a57424f7860b001f9ed49f-7g0VYzQJLyDNsljKTSUIRTtDu5e44XZToAeB4WMGNmN3c4cv5q",
							"cache-control: no-cache",
							"Content-Type: application/json",
							"Accept: application/json"),
							);
		$CurlSetup[CURLOPT_POSTFIELDS] = json_encode($RequestData);
		$ch = curl_init();
		curl_setopt_array($ch,$CurlSetup);
		$result = curl_exec($ch);
		$OmaniRes = json_decode($result,true);
		return $OmaniRes;	
		
	}
?>
