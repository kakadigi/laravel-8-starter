<?php

namespace Modules\Membership;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Membership\Events\MemberRegistered;
use Modules\Membership\Events\ResetPassword;
use Modules\Membership\Listeners\SendVerifyMemberMail;
use Modules\Membership\Listeners\SendResetPasswordMail;

/**
 * 
 */
class MembershipEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MemberRegistered::class => [
            SendVerifyMemberMail::class,
        ],
        ResetPassword::class => [
            SendResetPasswordMail::class,
        ]
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    // TODO : investigate why this doesn't work! (Paul)

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
