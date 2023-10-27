<?php
namespace App\Listeners;


use App\Events\StripeWebhookEvent;
use App\Models\Nikah;
use App\Models\NikahDraft;
use App\Models\User;
use App\Repositories\NikahRepository;
use App\Service\NikahRelatedService;
use App\Service\web\KhuluService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Cartalyst\Stripe\Stripe;
use Pusher\Pusher;

class StripeWebhookListner implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public $nikah_service;
    public function __construct(NikahRepository $nikahRepository)
    {
        $this->nikah_service = $nikahRepository;
    }

    /**
     * Handle the event.
     *
     * @param StripeWebhookEvent $event
     * @return void
     */
    public function handle(StripeWebhookEvent $event)
    {

        $request_data = $event->request_data;

       /* $meta_data = $event->meta_data;
        $stripe_object = $event->stripe_object;


        $nikah_request = NikahDraft::find($meta_data['draft_id']);
        $request_data = json_decode($nikah_request->request_data);

        $this->nikah_service->saveNikKah($request_data);*/


        $metadata = $request_data['data']['object']['metadata'];

        $check_status = $request_data['data']['object']['captured'];

//        Log::info($check_status == true ? 'true': 'false' );
        if($check_status == true ){
//            Log::info($metadata['webhook_type']);
            if($metadata['webhook_type'] == 'khulu_service'){
                $khula_service = (new KhuluService())->updateKhuluPaymentStatus($request_data);
                return response('success', 200);
            }else{
//                Log::info('Nikah event working');
                $id = $request_data['data']['object']['id'];
                $nikah_request = NikahDraft::find($metadata['nikah_draft_id']);
                if($nikah_request){
                    $request_data = json_decode($nikah_request->request_data);
                    $params = [
                        'transaction_id' => $id
                    ];
                   $result =  $this->nikah_service->saveNikKah($request_data,$params);

                    // After the transaction block, check if the transaction was successful.
                    if ($result != 'Transaction completed successfully') {
                        $draft_id = $metadata['nikah_draft_id'];
                        NikahDraft::where('id',$draft_id)->forceDelete();
                    }
                }
            }
        }
    }

    private function sendPusherEvent($userId)
    {
        $options = [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => true,
        ];
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            $options
        );
        $pusher->trigger('user-' . $userId, 'payment-intent-expired', ['message' => 'Your payment intent has expired.']);
    }
}
