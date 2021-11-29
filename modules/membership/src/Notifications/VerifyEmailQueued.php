<?php

namespace Modules\Membership\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    // Nothing else needs to go here unless you want to customize
    // the notification in any way.
}
