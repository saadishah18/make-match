<?php

namespace App\Traits;

use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahType;
use App\Models\User;
use App\Service\Facades\Api;

//use Cartalyst\Stripe\Laravel\Facades\Stripe;
//use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

use Stripe\StripeClient;
use Cartalyst\Stripe\Stripe;
use function PHPUnit\Framework\isNull;

trait NikahTrait
{

    public function checkUserNikahExits($user)
    {
        $nikah_exits = Nikah::where('user_id', $user->id)->orWhere('partner_id', $user->id)->count();
        return $nikah_exits;
    }

    public function checkNikahStatus($user)
    {
        $nikah_history = NikahDetailHistory::where('male_id', $user->id)->orWhere('female_id', $user->id)->first();
        return $nikah_history;
    }

    public function nikahDetail($user)
    {
        $nikah_detail = Nikah::where('user_id', $user->id)->orWhere('partner_id', $user->id)->get();
        return $nikah_detail;
    }


    public function makeLinkOld($price, $nikah_data)
    {
        $stripe = \Stripe\Stripe::setApiKey(config('services.stripe_testing.secret'));
        $nikah_type = NikahType::find($nikah_data['nikah_type_id']);
        $user = User::find($nikah_data['user_applied_nikah_id']);
        $session = \Stripe\Checkout\Session::create([
            'success_url' => url('payment-complete'),
            'cancel_url' => url('payment-failed'),

            'mode' => 'payment',
            'payment_intent_data' => ['metadata' => [
                'user_id' => $nikah_data['user_applied_nikah_id'],
                'nikah_draft_id' => $nikah_data['draft_id'],
                'quantity' => 1,
                'date_time' => now() . ' ' . now()->setTimezone('UTC')->format('M d, Y h:i A'),
                'amount_with_all_services' => formatNumbers($price),
                'currency' => strtoupper('GBP'),
                'type' => 'Nikah Service',
                'webhook_type' => 'nikah_service',
            ]],
            'client_reference_id' => $nikah_data['user_applied_nikah_id'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'GBP',
                    'product_data' => [
                        'name' => $nikah_type->name,
                    ],
                    'unit_amount' => formatNumbers($price) * 100,
                ],
//                    'price' => $nikah_type->stripe_price_id,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,

        ]);
        /* $create_link = $stripe->paymentLinks->create(
            [
                'line_items' => [['price' => $nikah_type->stripe_price_id, 'quantity' => 1]],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => url('/')],
                ],
                'metadata' => [
                    'user_id' => auth()->id(),
                    'quantity' => 1,
                    'date_time' => now() . ' ' . now()->setTimezone('Asia/Karachi')->format('M d, Y h:i A'),
                    'amount_with_all_services'=> (int)$price,
                    'currency' => strtoupper('eur'),
                    'site' => url('/'),
                    'type' => 'Nikah Service',
                ]
            ]);
        dd($create_link);*/
        return [
            'checkout_url' => $session['url'],
        ];
    }

    public function makeLink($price, $nikah_data)
    {
        $stripe = env('stripe_secret');
        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => 0.20,
            'currency' => strtoupper('GBP'),

            'payment_method_types' => [
                'card',
            ],
            'setup_future_usage' => 'off_session', // Set to off_session for future usage
            'metadata' => [
                'user_id' => $nikah_data['user_applied_nikah_id'],
                'nikah_draft_id' => $nikah_data['draft_id'],
                'quantity' => 1,
                'date_time' => now() . ' ' . now()->setTimezone('UTC')->format('M d, Y h:i A'),
                'created_at' => now()->toIso8601String(), // Record creation time

//                'amount_with_all_services' => formatNumbers($price),
                'amount_with_all_services' => 0.20,
                'currency' => strtoupper('GBP'),
                'type' => 'Nikah Service',
                'webhook_type' => 'nikah_service',
                'client_reference_id' => $nikah_data['user_applied_nikah_id'],
                'customer_email' => auth()->user()->email,
            ]
        ]);
        if((!empty($paymentIntent['id']) && !empty($paymentIntent['client_secret'])) || (!is_null($paymentIntent['id']) && !is_null($paymentIntent['client_secret']))){
            $result['id'] = $paymentIntent['id'];
            $result['client_secret'] = $paymentIntent['client_secret'];
            return $result;
        }else{
            return false;
        }
    }


}
