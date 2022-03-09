<?php

namespace App\Services\Transaction;


use App\Entity\Payment\Cart;

class StripeService
{
    private $privateKey;


    public function __construct()
    {
        if($_ENV['APP_ENV']  === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    /**
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Cart $cart)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $cart->getTotal() * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card']
        ]);
    }
    public function paiement(
        $amount,
        $currency,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if($stripeParameter['stripeIntentStatus'] === 'succeeded') {
            //TODO
        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }
    /**
     * @param array $stripeParameter
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter,Cart $cart)
    {
        return $this->paiement(
            $cart->getTotal() * 100,
            "eur",
            $stripeParameter
        );
    }
}