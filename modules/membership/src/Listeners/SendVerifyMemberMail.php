<?php

namespace Modules\Membership\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Membership\Events\MemberRegistered;
use Modules\Membership\Jobs\SendVerifyMemberMailJob;

class SendVerifyMemberMail
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
     * @param  object  $event
     * @return void
     */
    public function handle(MemberRegistered $event)
    {
        dispatch(new SendVerifyMemberMailJob($event->member));
    }
}
