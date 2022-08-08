<?php

namespace Database\Seeders;

use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $b = new Bookings;
        $b->user_id = 1;
        $b->desk_id = 1;
        $b->book_time_start = Carbon::now()->subYear();
        $b->book_time_end = Carbon::now()->subYear();
        $b->save();

        $b = new Bookings;
        $b->user_id = 1;
        $b->desk_id = 1;
        $b->book_time_start = Carbon::now()->subMonth();
        $b->book_time_end = Carbon::now()->subMonth();
        $b->save();

        $b = new Bookings;
        $b->user_id = 1;
        $b->desk_id = 1;
        $b->book_time_start = Carbon::now()->subDay();
        $b->book_time_end = Carbon::now()->subDay();
        $b->save();
    }
}
