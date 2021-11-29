<?php

namespace Modules\Membership\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Membership\Member;
use Modules\Membership\Mail\VerifyMemberMail;
use Mail;

class SendVerifyMemberMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $member;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $receipt = $this->member->user->email ?? null;
        if ($receipt) {
            Mail::to($receipt)->send(new VerifyMemberMail($this->member));
        }
    }
}
