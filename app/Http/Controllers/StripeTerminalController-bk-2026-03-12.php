<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Terminal\ConnectionToken;
use Stripe\PaymentIntent;
use App\Models\StorePayment;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Traits\EncryptTrait;
use App\Models\Customer;

use Hash;
use Session;
use DB;
use Mail;
use Stripe\StripeClient;

use Carbon\Carbon;

class StripeTerminalController extends Controller
{
	protected $stripe;       // Stripe client
    protected $stripeKey;    // Stripe API key

	use EncryptTrait;

	public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $sess_useremail = Session::get('sess_useremail') ?? '';

            $db_res = PaymentMethod::select('pm_details')
                            ->where('pm_group_name','PAYMENT_STRIPE')
                            ->where('pm_status','Active')
                            ->first();

            if ($db_res) {
                $arrPEVar = unserialize($db_res->pm_details);
                $STRIPE_KEY = $this->decrypt($arrPEVar['Secret_Key']);
            }
            $devEmails = [
                'wgequaldev@gmail.com',
                'gequaldev@gmail.com',
                'qqualdev@gmail.com',
                'testing12345678@gmail.com',
                'tetn@gmail.com',
                'maxqual11@gmail.com',
                'storeguest2@gmail.com',
                'tempchecknew1223@gmail.com'
            ];

            if (in_array(strtolower($sess_useremail), $devEmails)) {
                $STRIPE_KEY = config('services.stripe.secret');
            }

            if ($STRIPE_KEY) {
                Stripe::setApiKey($STRIPE_KEY);
                $this->stripe = new StripeClient($STRIPE_KEY);
                $this->stripeKey = $STRIPE_KEY;
            }

            return $next($request);
        });
    }

    public function createPaymentIntent(Request $request)
    {
		$CustEmail = Session::get('ShoppingCart.BillingAddress.email') ?? '';
		
       
		$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;
		}
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;
		}	
		
         $paymentIntent = PaymentIntent::create([
            'amount' => intval(round($request->input('amount') * 100)),
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'capture_method' => 'automatic',
			'metadata' => [
				'order_id' => 'OR' . $StoreOrderId,
				'customer_email' => $CustEmail,
			],

        ]);


       $myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
   
		if (fopen($myFile, 'a+')) 
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			
			$stringData .= date("m/d/Y H:i:s") . " Regarding Stripe Client Secret : " .json_encode($paymentIntent). " :\n";
			
			fwrite($fh, $stringData);
			fclose($fh);
		}
   
       
        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }
    public function createPaymentIntentCheckout(Request $request)
    {

		if($request->input('amount')<=0)
		{
			return response()->json([
					'status' => 'error',
					'message' => 'enter proper amount.'
			]);
		}

		$amount = $request->input('amount');
		
		$CustEmail = Session::get('ShoppingCart.BillingAddress.email') ?? '';
       
		$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;
		}
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;
		}	
		
        $paymentIntent = PaymentIntent::create([
            'amount' => intval(round($request->input('amount') * 100)),
            'currency' => 'usd',
            'payment_method_types' => ['card_present'],
            'capture_method' => 'automatic',
            'metadata' => [
								'order_id' => 'OR' . $StoreOrderId,
								'customer_email' => $CustEmail,
						  ],

        ]);
        
        
        $myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
   
		if (fopen($myFile, 'a+')) 
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			
			$stringData .= date("m/d/Y H:i:s") . " Regarding Stripe Client Secret For Checkout : " .json_encode($paymentIntent). " :\n";
			
			fwrite($fh, $stringData);
			fclose($fh);
		}

		if(null !== $request->input('PaymentType') && $request->input('PaymentType') == "CreditCard" && isset($paymentIntent->id) && $paymentIntent->id!='')
		{

			$CartItem = Session::get('ShoppingCart.Cart');
			$grouped = collect($CartItem)->groupBy('OrderType');
			$storeCount = $grouped->get('Store', collect())->count();
			$websiteCount = $grouped->get('Website', collect())->count();

			$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;

			$WebsiteOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;

			$NormalOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;

			$StoreCashSplitPaymentId = Session::has('ShoppingCart.StoreCashSplitPaymentId') ? Session::get('ShoppingCart.StoreCashSplitPaymentId') : null;

			$WebsiteCashSplitPaymentId = Session::has('ShoppingCart.WebsiteCashSplitPaymentId') ? Session::get('ShoppingCart.WebsiteCashSplitPaymentId') : null;

			$StoreCashPaymentId = Session::has('ShoppingCart.StoreCashPaymentId') ? Session::get('ShoppingCart.StoreCashPaymentId') : null;

			if($storeCount > 0 && $websiteCount > 0 && $StoreOrderId > 0 && $WebsiteOrderId > 0 && $request->input('amount') > 0)
			{
				$StoreInsert = array(
									'amount' => NumberFormat($request->input('amount')),
									'Payment_type' => $request->input('PaymentType'),
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'orders_id'	 => Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null,
									'status'	=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);

				$StoreInsert = array(
									'amount' => NumberFormat($request->input('amount')),
									'Payment_type' => $request->input('PaymentType'),
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'orders_id'	 => Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null,
									'status'	=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);
				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' received.',
					'id'	  => $paymentIntent->id
				]);

			}
			else if($NormalOrderId > 0 && $amount > 0)
			{

				$StoreInsert = array(
									'amount' 			=> NumberFormat($amount),
									'Payment_type' 		=> $request->input('PaymentType'),
									'orders_id'	 		=> $NormalOrderId,
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'status'			=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);

				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' created.',
					'id'	  => $paymentIntent->id
				]);

			}
			else
			{
				 return response()->json([
					'status' => 'error',
					'message' => 'Order not found,please try again.'
				]);
			}

		}
		else
		{
			 return response()->json([
				'status' => 'error',
				'message' => 'Please process again.'
			]);

		}

    }

public function PaymentProcessCheckout(Request $request)
{
    try {
		 sleep(3);
        $readerId =  Session::has('ConnectedCardReaderId') ? Session::get('ConnectedCardReaderId') : null;
        $status = '';
        $paymentIntentId = $request->input('payment_intent_id');
        $lastResponse = null;
		$attempts = 0;
		$maxAttempts = 60;
        $sess_useremail = Session::get('sess_useremail') ?? '';
        
        $reader = $this->stripe->terminal->readers->processPaymentIntent($readerId,
			['payment_intent' => $request->payment_intent_id]); // This live mode only
        
        
        $myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
   
		if (fopen($myFile, 'a+')) 
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			
			$stringData .= date("m/d/Y H:i:s") . " Regarding Reader For Checkout : " .json_encode($reader). " :\n";
			
			fwrite($fh, $stringData);
			fclose($fh);
		}
        
         
        $status = '';
		$PaymentRes = [];	
        while ($attempts < $maxAttempts)
		{
					
            if($sess_useremail == "qualdev.devs@gmail.com" || strtolower($sess_useremail) == "adam@maxaroma.com" || strtolower($sess_useremail)== "hamed@maxaroma.com" || strtolower($sess_useremail)== "maxstore@gmail.com")
            { 
				
					$PaymentRes = $this->stripe->paymentIntents->retrieve($request->payment_intent_id);
					$status = $PaymentRes->status ?? '';
					
					$paymentIntentId = $PaymentRes->id ?? null;
					
					$myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
			   
					if (fopen($myFile, 'a+')) 
					{
						$stringData = '';
						$fh = fopen($myFile, 'a+');
						
						$stringData .= date("m/d/Y H:i:s") . " Regarding Reader Payment Response For Checkout : " .json_encode($PaymentRes). " :\n";
						
						fwrite($fh, $stringData);
						fclose($fh);
					}
					
				
					
			}
			else
			{
				
					$PaymentRes = $this->stripe->testHelpers->terminal->readers->presentPaymentMethod($readerId,[]); // this is for test mode
					$status = $PaymentRes->action->status ?? '';
					$paymentIntentId = $PaymentRes->action->process_payment_intent->payment_intent ?? null;
				

			}
            

            if (in_array($status, ['succeeded', 'failed', 'cancelled'])) {
                break;
            }
			 $attempts++;
            sleep(2);
        }
		
		$lastResponse = $reader."---".$PaymentRes;
			
		$myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
   
		if (fopen($myFile, 'a+')) 
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			
			$stringData .= date("m/d/Y H:i:s") . " Regarding Reader Payment Last Response For Checkout : " .json_encode($lastResponse). " :\n";
			
			fwrite($fh, $stringData);
			fclose($fh);
		}
       
       
        $cartItems = collect(Session::get('ShoppingCart.Cart', []));
        $grouped = $cartItems->groupBy('OrderType');
        $storeCount = $grouped->get('Store', collect())->count();
        $websiteCount = $grouped->get('Website', collect())->count();

        $storeOrderId = Session::get('ShoppingCart.StoreOrderID') ?? 0;

        $websiteOrderId = Session::get('ShoppingCart.WebsiteOrderID') ?? 0;

        $normalOrderId = Session::get('ShoppingCart.OrderID') ?? 0;

		$StoreInsert = array(
								'amount' 			=> NumberFormat($request->amount),
								'Payment_type' 		=> $request->input('PaymentType'),
								'transaction_info'	=> json_encode($lastResponse),
								'paymentintentid'	=> $paymentIntentId,
							);

		 if ($status === 'succeeded') {
			 $StoreInsert['status'] = 'Completed';
		 }elseif ($status === 'failed') {
			 $StoreInsert['status'] = 'Declined';
			 $this->stripe->terminal->readers->cancelAction($readerId, []);
        } elseif ($status === 'cancelled') {
			 $StoreInsert['status'] = 'Canceled';
			 $this->stripe->terminal->readers->cancelAction($readerId, []);
        } else {
			 $StoreInsert['status'] = 'Declined';
			 $this->stripe->terminal->readers->cancelAction($readerId, []);
        }

        if ($storeCount > 0 && $websiteCount > 0 && $storeOrderId && $websiteOrderId) {
			$StoreInsert['orders_id']	= $storeOrderId;
            StorePayment::create($StoreInsert);
            $StoreInsert['orders_id']	= $websiteOrderId;
            StorePayment::create($StoreInsert);

        }
        elseif ($normalOrderId) {
			$StoreInsert['orders_id']	= $normalOrderId;
            StorePayment::create($StoreInsert);
        }

        if ($status === 'succeeded') {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment of $' . NumberFormat($request->input('amount')) . ' received.',
            ]);
        } elseif ($status === 'failed') {
			
			Session::put('PaymentMessagePOS',"Payment failed or declined");
		
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment failed or declined.'
            ]);
        } elseif ($status === 'cancelled') {
			Session::put('PaymentMessagePOS',"Payment was cancelled");
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Payment was cancelled by user.'
            ]);
        } else {
            // Just in case
            Session::put('PaymentMessagePOS',"Payment failed or declined");
            return response()->json([
                'status' => 'failed',
                'message' => 'Unknown payment status.'
            ]);
        }

    } catch (\Exception $e) {
		 Session::put('PaymentMessagePOS',"Payment failed or declined");
        return response()->json([
            'status' => 'failed',
            'message' => 'Something went wrong. Please try again later.',
            'error_detail' => $e->getMessage(),
        ]);
    }
}

public function PaymentCancel(Request $request)
{
    $intentId = $request->input('payment_intent_id');

    try {
        //$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        try {
			 $readerId =  Session::has('ConnectedCardReaderId') ? Session::get('ConnectedCardReaderId') : null;
			$readerRes = $this->stripe->terminal->readers->cancelAction($readerId, []);
            $intent = $this->stripe->paymentIntents->cancel($intentId, []);
            
            		$myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';
   
		if (fopen($myFile, 'a+')) 
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');
			
			$stringData .= date("m/d/Y H:i:s") . " Regarding Reader Cancel Payment Response For Checkout : " .json_encode($readerRes)."--".json_encode($intent)." :\n";
			
			fwrite($fh, $stringData);
			fclose($fh);
		}
            
            
        } catch (\Stripe\Exception\InvalidRequestException $ex) {
			Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");
            return response()->json([
                'status'  => 'InvalidError',
                'message' => $ex->getMessage(),
            ]);
        }

        if (isset($intent->status) && ($intent->status === 'canceled'  || $intent->status === 'cancelSuccess')) {

            $cartItems = collect(Session::get('ShoppingCart.Cart', []));
            $grouped = $cartItems->groupBy('OrderType');
            $storeCount = $grouped->get('Store', collect())->count();
            $websiteCount = $grouped->get('Website', collect())->count();
            $storeOrderId = Session::get('ShoppingCart.StoreOrderID') ?? 0;
            $websiteOrderId = Session::get('ShoppingCart.WebsiteOrderID') ?? 0;
            $normalOrderId = Session::get('ShoppingCart.OrderID') ?? 0;

            $StoreInsert = [
                'amount'            => NumberFormat($request->amount),
                'Payment_type'      => $request->input('PaymentType'),
                'transaction_info'  => json_encode($intent),
                'paymentintentid'   => $intentId,
                'status'			=> 'Canceled'
            ];

            if ($storeCount > 0 && $websiteCount > 0 && $storeOrderId && $websiteOrderId) {
                $StoreInsert['orders_id'] = $storeOrderId;
                StorePayment::create($StoreInsert);
                $StoreInsert['orders_id'] = $websiteOrderId;
                StorePayment::create($StoreInsert);
            } elseif ($normalOrderId) {
                $StoreInsert['orders_id'] = $normalOrderId;
                StorePayment::create($StoreInsert);
            }
			Session::put('PaymentMessagePOS',"Payment of $". NumberFormat($request->amount). " has been canceled");	
            return response()->json([
                'status' => 'cancelSuccess',
                'intent' => $intent
            ]);
        }
        Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");
		
        return response()->json([
            'status' => 'InvalidError',
            'intent' => $intent
        ]);

    } catch (\Exception $e) {
		Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");
        return response()->json([
            'status'  => 'InvalidError',
            'message' => $e->getMessage(),
        ]);
    }
}

}
