<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\Facade\ApiResponse;
use App\Models\NikahType;
use App\Models\User;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use PHPUnit\Exception;

class StripeController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function getToken()
    {
        try {
            $request = request();
            $stripe = $this->stripe;
            $token =  $stripe->tokens()->create([
                'card' => [
                    'number' => $request->get('card', '4242424242424242'),
                    'exp_month' => $request->get('month', 12),
                    'cvc' => $request->get('cvv', 314),
                    'exp_year' => $request->get('year', 2023),
                ],
            ]);
            return Api::response($token);
        }catch (Exception $exception){
            Api::error($exception->getMessage());
        }
    }


    public function makeCustomer($user)
    {
        $customer = $this->stripe->customers()->create([
            'email' => $user->email,
        ]);
        $customer_id = $customer['id'];
        $user->stripe_customer = $customer_id;
        $user->update();
        return $user->stripe_customer;
    }

    public function updateCustomerSource(Request $request)
    {
        ApiResponse::validateRequest($request->all(), [
            'card_id' => 'required',
        ]);

        if (ApiResponse::validationFailed()) {
            return ApiResponse::validationFailedResponse();
        }
        try {
            $user = Auth::user();
            $stripe = $this->stripe;
            $customer = $stripe->customers()->update($user->stripe_customer, [
                'default_source' => $request->card_id,
            ]);

            return ApiResponse::response(['customer' => $customer]);

        } catch (\Exception $exception) {
            return ApiResponse::setStatusCode(500)->setMessage($exception->getMessage())->response();
        }
    }

    public function deleteCard($card_id)
    {
        try {
            $user = Auth::user();
            $this->stripe->cards()->delete($user->stripe_customer, $card_id);

            $customer_id = $user->stripe_customer;
            $customer = $this->stripe->customers()->find($customer_id);

            return ApiResponse::response(['card' => $customer['default_source']]);
        } catch (\Exception $exception) {
            return ApiResponse::setStatusCode(500)->setMessage($exception->getMessage())->response();
        }

    }


    public function getCustomer()
    {
        try {
            $user = Auth::user();
            $stripe = $this->stripe;
            $customer = null;
            $cards = [];
            $customer_id = $user->stripe_customer;
            if ($customer_id) {
                $customer = $stripe->customers()->find($customer_id);
                if ($customer)
                    $cards = $customer['sources']['data'] ?? [];
            }
            if (env('app_env') == 'production') $stripe_key = config('services.stripe.secret');
            else $stripe_key = config('services.stripe_testing.secret');
            return Api::response(compact('customer', 'cards', 'stripe_key'));
        } catch (Exception $exception) {
            dd($exception->getMessage());
//            return Api::setStatusCode(500)->setMessage($exception->getMessage())->response();
        }
    }

    public function makeLink(){
        try {
            $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
            $user = auth()->user();
            $customer_id = $user->strie_customer;
            if($customer_id == null){
                $customer_id = $this->makeCustomer($user);
            }
            $stripe_customer = $stripe->customers()->find($customer_id);
            $paymentIntent = $stripe->paymentIntents()->create([
                'amount' => '20',
                'currency' => 'usd',
                'customer' => auth()->user()->name,
                'payment_method_types' => ['card']
            ]);

            if (env('app_env') == 'production') $stripe_key = config('services.stripe.secret');
            else $stripe_key = config('services.stripe_testing.secret');

            return Api::response([
                'intent' => $paymentIntent,
                'stripe_key' => $stripe_key,
            ]);
        }catch (Exception $exception){
            Api::error($exception->getMessage());
        }
    }

    public function getPaymentMethods($customer_id)
    {
        $payment_methods = \Stripe\PaymentMethod::all([
            'customer' => $customer_id,
            'type' => 'card'
        ]);
        return $payment_methods;
    }

    public function saveNikahPayment($payment_information){
        $validator = Validator::make($payment_information, [
            'card_no' => 'required|min:16|max:16',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvv' => 'required',
//            'amount' => 'required',
        ]);
        if ($validator->passes()) {
            $stripe = \Cartalyst\Stripe\Laravel\Facades\Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $payment_information['card_no'],
                        'exp_month' => $payment_information['expiry_month'],
                        'exp_year' => $payment_information['expiry_year'],
                        'cvc' => $payment_information['cvv'],
                    ],
                ]);

                if (!isset($token['id'])) {
                    dd('token id not found');
                    return redirect()->route('addmoney.paymentstripe');
                }
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => 20.49,
                    'description' => 'Nikah Fees with all included services',
                ]);

                if($charge['status'] == 'succeeded') {
                    echo "<pre>";
                    print_r($charge);exit();
                    return redirect()->route('addmoney.paymentstripe');
                } else {
                    dd($charge);
                }
            } catch (\Exception $e) {
                dd($e->getMessage(),'1st catch');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                dd($e->getMessage(),'2nd last catch');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                dd($e->getMessage(),'last catch');
            }
        }
    }

    public function oldStripeIntegration($price, $nikah_data)
    {
//        $stripe = \Stripe\Stripe::setApiKey('sk_test_51M34RYMMIeKsa2Rfj0xUuh26QRBvowKwwPqMBqoxqR8iLfAXcw1HPPxTCguMmxBeF4UPDvy24P5MhYZbwnThG726Fk00AcLN4r0s');
        $user = auth()->user();

        $nikah_type = NikahType::find($nikah_data['nikah_type_id']);
        $nikah_type_name = $nikah_type->name;

//
//            if ($nikah_type->stripe_product_id == null) {
//                $product = $stripe->products->create([
//                    'name' => $nikah_type_name,
//                ]);
//
//                $nikah_type->stripe_product_id = $product['id'];
//                $nikah_type->update();
//                $nikah_type->refresh();
//
//            }
//
//            if ($nikah_type->stripe_price_id == null) {
//                $set_price = $stripe->prices->create([
//                    'unit_amount' => (int)$nikah_type->price,
//                    'currency' => strtoupper('eur'),
//                    'product' =>  $nikah_type->stripe_product_id ,
//                ]);
//
//                $nikah_type->stripe_price_id = $set_price['id'];
//                $nikah_type->update();
//                $nikah_type->refresh();
//            }
//            $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = User::find($nikah_data['user_applied_nikah_id']);
        $session = \Stripe\Checkout\Session::create([
            'success_url' => url('/'),
            'cancel_url' => url('/'),
            'mode' => 'payment',
            'payment_intent_data'=>['metadata' =>  [
                'user_id' => $nikah_data['user_applied_nikah_id'],
                'nikah_draft_id' => $nikah_data['draft_id'],
                'quantity' => 1,
                'date_time' => now() . ' ' . now()->format('M d, Y h:i A'),
                'amount_with_all_services'=> (int)$price,
                'currency' => strtoupper('eur'),
                'type' => 'Nikah Service',
            ]],
            'client_reference_id' => $nikah_data['user_applied_nikah_id'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $nikah_type->name,
                    ],
                    'unit_amount' => (int)round($price) * 100,
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


}
