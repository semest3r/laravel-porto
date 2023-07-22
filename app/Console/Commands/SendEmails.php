<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a email to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
