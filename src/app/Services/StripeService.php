<?php

namespace App\Services;

use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        if (app()->environment() !== 'testing'){
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        }
    }

    //チェックアウトセッションを作成
    public function createCheckoutSession(array $params)
    {
        return StripeSession::create($params);
    }

    //チェックアウトセッションを取得
    public function retrieveCheckoutSession(string $sessionId)
    {
        return StripeSession::retrieve($sessionId);
    }
}