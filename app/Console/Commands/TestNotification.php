<?php

namespace App\Console\Commands;

use App\Mail\TestNotification as MailTestNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = config('uptime-monitor.notifications.mail.to');
        if (empty($to)) {
            $this->error('env variable UPTIME_MONITOR_EMAIL is not set.');

            return 1;
        }

        $this->line('Sending test notification to: ' . $to);
        Mail::to($to)->send(new MailTestNotification());
    }
}
