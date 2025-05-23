<?php

namespace App\Service\Paypal;

use App\Http\Traits\EmailConfig;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;
abstract class  PaypalCallback
{
    use EmailConfig;

    public function __construct(public array $params){}
    /**
     * @return bool
     * @throws Throwable
     */
    public function paypal(): bool
    {
        $token = $this->params['token'];
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($token);
        try {
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                try {
                    return true;
                } catch (Throwable $e) {
                    Log::error('CourseReservationMail send failed: ' . $e->getMessage());
                }
            } else {
                Log::error('Paypal payment failed: ',['response' => $response]);
            }
        } catch (Throwable $e) {
            Log::error('CourseReservation update failed: ' . $e->getMessage());
        }
        return false;
    }

  
    /**
     * 处理支付回调
     * 
     * @return array
     */
    public function handle(): array
    {
        // 订单支付成功
        if($this->paypal()) {
            return $this->successCallback();
        } else{
            return $this->errorCallback();
        
        }
    }

    public function successCallback()
    {

    }
    public function errorCallback()
    {

    }
}
