<?php

namespace App\Listeners;

use App\Events\BookingConfirmation;
use App\Mail\BookingConfirm;
use App\Mail\BookingDeleted;
use App\Models\Bookings;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EmailUserEffectedByBookingConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DeskDeleted  $event
     * @return void
     */
    public function handle(BookingConfirmation $event)
    {
        $booking = $event->booking;
        $user = User::find($booking->user_id);
        Mail::to($user->email)->send(new BookingConfirm($booking));
        
    }
}
