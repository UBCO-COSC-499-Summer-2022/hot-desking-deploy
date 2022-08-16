<?php

namespace App\Mail;

use App\Models\Bookings;
use App\Models\Desks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirm extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.bookingCreated', [ 
            // pass variables
            'campus_name' => Desks::find($this->booking->desk_id)->room->floor->building->campus->name,
            'building_name' => Desks::find($this->booking->desk_id)->room->floor->building->name,
            'floor_num' => Desks::find($this->booking->desk_id)->room->floor->floor_num,
            'room_name' => Desks::find($this->booking->desk_id)->room->name,
            'book_time_start' => $this->booking->book_time_start,
            'book_time_end' => $this->booking->book_time_end,
            ])
        ->subject('Your Booking has been created!');
    }
}
