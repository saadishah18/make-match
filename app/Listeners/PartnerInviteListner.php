<?php

namespace App\Listeners;

use App\Events\SendPartnerInvitationEmail;
use App\Mail\LinkPartnerMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PartnerInviteListner implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */


    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendPartnerInvitationEmail  $event
     * @return void
     */
    public function handle(SendPartnerInvitationEmail $event)
    {
       $send =  Mail::to($event->email)->queue(new LinkPartnerMail($event->user));
    }
}
