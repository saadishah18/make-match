<?php

namespace App\Repositories;


use App\Http\Resources\RujuResource;
use App\Models\Khulu;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahType;
use App\Models\PortalSetting;
use App\Models\Ruju;
use App\Models\Talaq;
use App\Models\User;
use Illuminate\Support\Str;
use App\Service\Facades\Api;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\KhuluResource;
use App\Repositories\Interfaces\KhuluInterface;

use Cartalyst\Stripe\Stripe;

class KhuluRepository implements KhuluInterface
{

    public function applyKhuluOLD($request)
    {
        if (Str::lower(Auth::user()->gender) == 'female') {
            $nikahId = $request->nikah_id;
            $femaleUser = Auth::user();
            $nikah = Nikah::where('user_id', $femaleUser->id)->orWhere('partner_id', $femaleUser->id)->find($nikahId);
            if ($nikah) {
                $khulu = Khulu::where('nikah_id', $nikahId)->first();
                if ($khulu) {
//                    if ($khulu->{'2nd_khulu_applied_date'} == null) {
                        $khulu->{'2nd_khulu_applied_date'} = Carbon::now()->toDateTimeString();
                        $khulu->khulu_counter = 2;
                        $khulu->second_khulu_status = 'requested';
                        $khulu->second_khulu_reason = $request->reason;
                        $khulu->second_khulu_detail = $request->detail;
                        $khulu->imam_id = $khulu->imam_id != null || $khulu->imam_id != '' ? $khulu->imam_id : $nikah->imam_id;
                        $khulu->update();
//                    } else {
//                        return Api::error('You has already applied for 2nd khulu');
//                    }
                } else {
                    $khulu = Khulu::create([
                        'male_id' => $femaleUser->id == $nikah->user_id ? $nikah->partner_id : $nikah->user_id,
                        'partner_id' => $femaleUser->id,
                        'nikah_id' => $nikahId,
                        'khulu_counter' => 1,
                        'otp_verified' => 1,
                        'first_khulu_status' => 'requested',
                        '1st_khulu_applied_date' => Carbon::now()->toDateTimeString(),
                        'reason' => $request->reason,
                        'details' => $request->detail,
                        'imam_id' => $nikah->imam_id,
                    ]);
                }
                $data = [];

                $khula_payment_link = $this->KhuluPayamentLink($khulu);
                $khulu['checkout_url'] = $khula_payment_link;
                $data['khulu'] = new KhuluResource($khulu);

                return Api::response($data, 'Khulu applied successfully');
            }
            return Api::error('You are not partner of this nikah');
        }
        return Api::error('Male can not apply for khulu');

    }


    public function applyKhulu($request)
    {
        if (Str::lower(Auth::user()->gender) == 'female') {
            $nikahId = $request->nikah_id;
            $femaleUser = Auth::user();
            $nikah = Nikah::where('user_id', $femaleUser->id)->orWhere('partner_id', $femaleUser->id)->find($nikahId);
            if ($nikah) {
                $khulu = Khulu::where('nikah_id', $nikahId)->first();

                $male_id = $femaleUser->id == $nikah->user_id ? $nikah->partner_id : $nikah->user_id;
                $partner_id = $femaleUser->id;
                $nikah_id = $nikahId;


                $data = [
                    'male_id' => $male_id,
                    'partner_id' => $partner_id,
                    'nikah_id' => $nikah_id,
                    'email' => $femaleUser->email,
                    'reason' => $request->reason,
                    'detail' => $request->detail,
                ];

                $khula_payment_link = $this->KhuluPayamentLink($data);
                return $khula_payment_link;
            }
            return Api::error('You are not partner of this nikah');
        }
        return Api::error('Male can not apply for khulu');

    }

    public function acceptKhuluRequest($request)
    {
        $khulu = Khulu::find($request->khulu_id);
        if ($khulu->male_id == Auth::user()->id) {
            if (($khulu->first_khulu_status == 'requested' || $khulu->first_khulu_status == 'rejected') && $khulu->khulu_counter == 1) {
                $khulu->update(['first_khulu_status' => 'complete']);
            } elseif (($khulu->second_khulu_status == 'requested' || $khulu->second_khulu_status == 'rejected') && $khulu->khulu_counter == 2) {
                $khulu->update(['second_khulu_status' => 'complete']);
            } else {
                return Api::response([], "Khulu Accepted Already");
            }

            if ($khulu->first_khulu_status == 'complete' || $khulu->second_khulu_status == 'complete') {
                $nikah_history = NikahDetailHistory::where('nikah_id', $khulu->nikah_id)->first();
                $nikah_history->is_khulu_applied = 1;
                $nikah_history->khulu_id = $khulu->id;
                $nikah_history->current_status = 'Divorced';
                $nikah_history->update();
            }

            $data = [];
            $data['khulu'] = new KhuluResource($khulu);
            return Api::response($data, 'Khulu Accepted');
        }
        return Api::error('Unauthorized you can not accept this khulu');
    }

    public function rejectKhuluRequest($request)
    {
        $khulu = Khulu::find($request->khulu_id);
//        if ($khulu->male_id == Auth::user()->id) {
            if ($khulu->first_khulu_status != 'completed' && $khulu->khulu_counter == 1) {
                $khulu->update(['first_khulu_status' => 'discarded']);
            } elseif ($khulu->second_khulu_status != 'completed' && $khulu->khulu_counter == 2) {
                $khulu->update(['second_khulu_status' => 'discarded']);
            } else {
                return Api::response([], "Khulu could\'t be discarded");
            }

            $data = [];
            $data['khulu'] = new RujuResource($khulu);
            return Api::response($data, 'Khulu discarded successfully.');
//        }
//        return Api::error('Unauthorized you can not reject this khulu');
    }


    public function KhuluPayamentLinkOLD($khula_data)
    {
//        $stripe = \Stripe\Stripe::setApiKey('sk_test_51MRYMMI43eKsa2Rfj0xUuh26QRBvowKwwPqMBqoxqR8iLfAXcw1HPPxTCguMmxBeF4UPDvy24P5MhYZbwnThG726Fk00AcLN4r0s');
        $khula_price = formatNumbers(PortalSetting::where('name', 'khulu_fees')->first()->value);
        $total_price = includeVatInPrice($khula_price);
        $session = \Stripe\Checkout\Session::create([
            'success_url' => url('payment-complete'),
            'cancel_url' => url('payment-failed'),
            'mode' => 'payment',
            'payment_intent_data' => ['metadata' => [
                'male_id' => $khula_data['male_id'],
                'partner_id' => $khula_data['partner_id'],
                'nikah_id' => $khula_data['nikah_id'],
                'female_user_id' => Auth::user()->id,
                'date_time' => now() . ' ' . now()->setTimezone('UTC')->format('M d, Y h:i A'),
                'amount_with_all_services' => $total_price,
                'currency' => strtoupper('GBP'),
                'type' => 'Khulu Service',
                'webhook_type' => 'khulu_service',
                'reason' => $khula_data['reason'],
                'detail' => $khula_data['detail'],
            ]],
            'client_reference_id' => $khula_data['partner_id'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'GBP',
                    'product_data' => [
                        'name' => 'Khula Service',
                    ],
                    'unit_amount' => formatNumbers($total_price) * 100,
                ],
//                    'price' => $nikah_type->stripe_price_id,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
            'customer_email' => $khula_data['email'],
        ]);
        return $session['url'];
    }


    public function KhuluPayamentLink($khula_data)
    {
        $stripe = env('stripe_secret');
        $khula_price = formatNumbers(PortalSetting::where('name', 'khulu_fees')->first()->value);
        $total_price = includeVatInPrice($khula_price);
        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => $total_price,
            'currency' => strtoupper('GBP'),
            'payment_method_types' => [
                'card',
            ],
            'metadata' => [
                'male_id' => $khula_data['male_id'],
                'partner_id' => $khula_data['partner_id'],
                'nikah_id' => $khula_data['nikah_id'],
                'female_user_id' => Auth::user()->id,
                'date_time' => now() . ' ' . now()->setTimezone('UTC')->format('M d, Y h:i A'),
                'amount_with_all_services' => $total_price,
                'currency' => strtoupper('GBP'),
                'type' => 'Khulu Service',
                'webhook_type' => 'khulu_service',
                'reason' => $khula_data['reason'],
                'detail' => $khula_data['detail'],
            ]
        ]);
        $result['id'] = $paymentIntent['id'];
        $result['client_secret'] = $paymentIntent['client_secret'];
        return $result;
    }

}
