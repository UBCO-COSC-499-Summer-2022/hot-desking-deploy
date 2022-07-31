<?php

namespace App\Mail;

use App\Models\Bookings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.bookingDeleted', [ 
            // pass variables
            'booking_id' => $this->user->pivot->id,
            // 'campus_name' => $this->desk->room->floor->building->campus->name,
            // 'building_name' => $this->desk->room->floor->building->name,
            // 'floor_num' => $this->desk->room->floor->floor_num,
            // 'room_name' => $this->desk->room->name,
            'desk_id' => $this->user->pivot->desk_id,
            'book_time_start' => $this->user->pivot->book_time_start,
            'book_time_end' => $this->user->pivot->book_time_end,
            ])
        ->subject('Your Booking has been canceled');
    }
}
