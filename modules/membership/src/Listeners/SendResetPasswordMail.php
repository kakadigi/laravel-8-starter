<?php

namespace Modules\Membership\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Membership\Events\ResetPassword;
use Modules\Membership\Jobs\SendResetPasswordMailJob;

class SendResetPasswordMail
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
    public function handle(ResetPassword $event)
    {
        dispatch(new SendResetPasswordMailJob($event->user));
    }
}
