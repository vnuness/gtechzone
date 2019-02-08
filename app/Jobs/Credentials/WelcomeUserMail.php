<?php

namespace App\Jobs\Credentials;

use App\Notifications\Credentials\NewUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WelcomeUserMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_to;

    private $user_from;

    private $pass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_to, $pass, $user_from)
    {
        $this->user_to = $user_to;
        $this->user_from = $user_from;
        $this->pass = $pass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user_to->notify(
            new NewUser($this->user_to, $this->pass, $this->user_from)
        );
    }
}
