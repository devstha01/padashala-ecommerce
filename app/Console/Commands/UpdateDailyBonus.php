<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDailyBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateDailyBonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UpdateDaily Daily Bonus';

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
     * @return mixed
     */
    public function handle()
    {
//        \Log::warning('Daily Bonus start');
//        app('App\Http\Controllers\backend\Member\MemberController')->updateDailyBonus();
//
//        \Log::warning('Daily Bonus end');
    }
}
