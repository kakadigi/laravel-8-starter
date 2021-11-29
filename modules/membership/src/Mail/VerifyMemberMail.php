<?php

namespace Modules\Membership\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Modules\Membership\Member;

class VerifyMemberMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $member;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $member    = $this->member;
        $from      = config('membership.email.from');
        $encryped  = Crypt::encryptString($member->uuid);
        $verifyUrl = route('api.membership.verify', ['verify' => $encryped, 'member' => $member->uuid]);
        return $this->from($from)
            ->subject('Account Verify')
            ->view('membership::emails.verify-member')
            ->with([
                'member' => $member,
                'action' => [
                    'url' => $verifyUrl,
                    'text' => 'Verifikasi Akun Saya'
                ]
            ]);
    }
}
