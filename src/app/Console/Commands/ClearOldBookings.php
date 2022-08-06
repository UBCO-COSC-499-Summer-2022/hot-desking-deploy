<?php

namespace App\Console\Commands;

use App\Models\BookingHistory;
use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearOldBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old bookings from booking table and store them into the booking_history table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get all bookings
        $bookings = Bookings::all();
        foreach($bookings as $booking) {
            if ((Carbon::now('GMT-7')->gte($booking->book_time_start)) && (Carbon::now('GMT-7')->gte($booking->book_time_end))) {
                // Store Old Booking in history table
                $booking_history = new BookingHistory;
                $booking_history->user_id = $booking->user_id;
                $booking_history->desk_id = $booking->desk_id;
                $booking_history->book_time_start = $booking->book_time_start;
                $booking_history->book_time_end = $booking->book_time_end;
                $booking_history->save();
                // remove old bookings from bookings table
                $booking->delete();
            }
        }
        $this->info('Verified all bookings, removed any old bookings, and stored them in the bookings_history table');
        return 0;
    }
}
