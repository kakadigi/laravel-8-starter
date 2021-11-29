<?php

namespace Modules\Membership\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Modules\Membership\Mail\ResetPasswordMail;
use App\Models\User;
use Mail;

class SendResetPasswordMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $receipt = $user->email ?? null;
        if ($receipt) {
            $user->new_password = Str::random(8);
            $currentUser = User::where('id', $user->id)->first();
            if ($currentUser) {
                $currentUser->password = bcrypt($user->new_password);
                $currentUser->save();
            }
            Mail::to($receipt)->send(new ResetPasswordMail($this->user));
        }
    }
}
