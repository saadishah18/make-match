<?php

namespace App\Console\Commands;

use App\Models\OTP;
use Illuminate\Console\Command;

class RemoveExpiredOTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove expired otps';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        OTP::where('created_at', '<=', now()->subMinutes(5)->toDateTimeString())->delete();
    }
}
