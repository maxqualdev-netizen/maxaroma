<?php

namespace App\Services\Payment;

use App\Http\Controllers\Traits\EncryptTrait;
use App\Models\PaymentMethod;
use AmazonPay\Client as AmazonClient;
use RuntimeException;

class AmazonPayService
{
    use EncryptTrait;

    public $MERCHANT_ID;
    public $MARKETPLACE_ID;
    public $AWS_ACCESS_KEY;
    public $AWS_SECRET_KEY;
    public $CLIENT_ID;
    public $TRANSACTION_MODE;
    public $CURRENCY_CODE;
    public $JS_SERVER_URL;
    public $API_URL;
    public $API_PROFILE_URL;
    public $SANDBOX_VAL;
    public $client;

    public function __construct()
    {
        $paymentMethod = PaymentMethod::query()
            ->select('pm_details')
            ->where('pm_group_name', 'PAYMENT_PAYWITHAMAZON')
            ->where('pm_status', 'Active')
            ->first();

        if (!$paymentMethod) {
            throw new RuntimeException('Amazon Pay is not configured or is inactive.');
        }

        $details = @unserialize($paymentMethod->pm_details);
        if (!is_array($details)) {
            throw new RuntimeException('Amazon Pay configuration is invalid.');
        }

        $this->MERCHANT_ID = $this->decrypt($details['paywithamazon_Merchant_Id'] ?? '');
        $this->MARKETPLACE_ID = $this->decrypt($details['paywithamazon_Marketplace_Id'] ?? '');
        $this->AWS_ACCESS_KEY = $this->decrypt($details['paywithamazon_Access_Key_Id'] ?? '');
        $this->AWS_SECRET_KEY = $this->decrypt($details['paywithamazon_Secret_Key_ID'] ?? '');
        $this->CLIENT_ID = $this->decrypt($details['paywithamazon_Client_ID'] ?? '');
        $this->TRANSACTION_MODE = strtoupper(trim($details['paywithamazon_Transaction_Mode'] ?? 'SANDBOX'));
        $this->CURRENCY_CODE = $details['paywithamazon_Currency_Code'] ?? 'USD';

        $this->SANDBOX_VAL = $this->TRANSACTION_MODE === 'SANDBOX';

        if ($this->SANDBOX_VAL) {
            $this->JS_SERVER_URL = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js?sellerId=' . $this->MERCHANT_ID;
            $this->API_URL = 'https://api.sandbox.amazon.com/auth/o2/tokeninfo?access_token=';
            $this->API_PROFILE_URL = 'https://api.sandbox.amazon.com/user/profile';
        } else {
            $this->JS_SERVER_URL = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js?sellerId=' . $this->MERCHANT_ID;
            $this->API_URL = 'https://api.amazon.com/auth/o2/tokeninfo?access_token=';
            $this->API_PROFILE_URL = 'https://api.amazon.com/user/profile';
        }

        $this->client = new AmazonClient([
            'merchant_id' => $this->MERCHANT_ID,
            'access_key' => $this->AWS_ACCESS_KEY,
            'secret_key' => $this->AWS_SECRET_KEY,
            'client_id' => $this->CLIENT_ID,
            'currency_code' => $this->CURRENCY_CODE,
            'region' => 'us',
            'sandbox' => $this->SANDBOX_VAL,
        ]);
    }

    public function getUserInfo(string $accessToken)
    {
        return $this->client->getUserInfo($accessToken);
    }

    public function getOrderReferenceDetails(array $parameters)
    {
        return $this->client->getOrderReferenceDetails($parameters);
    }

    public function charge(array $parameters)
    {
        return $this->client->charge($parameters);
    }

    public function closeOrderReference(array $parameters)
    {
        return $this->client->closeOrderReference($parameters);
    }

    public function transliterate(string $value): string
    {
        if (!function_exists('transliterator_transliterate')) {
            return $value;
        }

        $latin = transliterator_transliterate('Any-Latin; Latin-ASCII', $value);

        return strtr($latin, [
            "ʿ" => 'a',
            '`' => '',
        ]);
    }
}
