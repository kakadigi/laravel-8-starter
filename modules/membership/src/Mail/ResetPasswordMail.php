<?php

namespace Modules\Membership\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user     = $this->user;
        $from     = config('membership.email.from');
        $loginUrl = config('membership.frontend_url') . '/login';
        return $this->from($from)
            ->subject('Reset Password')
            ->view('membership::emails.reset-password')
            ->with([
                'user' => $user,
                'action' => [
                    'url' => $loginUrl,
                    'text' => 'Login Sekarang'
                ]
            ]);
    }
}
