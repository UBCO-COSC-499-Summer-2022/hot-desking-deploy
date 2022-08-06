<?php

namespace App\Listeners;

use App\Events\WorkSpaceDeletedOrClosed;
use App\Mail\BookingDeleted;
use App\Models\Bookings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EmailUsersEffectedByWorkspaceDeletionOrClosure
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
    public function handle(WorkSpaceDeletedOrClosed $event)
    {
        $desk = $event->desk;
        // check if any bookings exist for this desk
        if (!count($desk->users) < 1) {
            foreach ($desk->users as $user) {
                // Send email to effected users
                Mail::to($user->email)->send(new BookingDeleted($user));
                // delete booking if hasn't been deleted already
                if (Bookings::find($user->pivot->id)->exists()) {
                    Bookings::find($user->pivot->id)->delete();
                }
            }
        }
    }
}
