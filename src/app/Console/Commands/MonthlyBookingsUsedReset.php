<?php

namespace App\Console\Commands;

use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonthlyBookingsUsedReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all monthly bookings counter for every user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = Users::all();
        foreach ($users as $user) {
            $user->bookings_used = 0;
            $user->save();
        }
        $this->info('Monthly Bookings Reset Completed');
        return 0;
    }
}
