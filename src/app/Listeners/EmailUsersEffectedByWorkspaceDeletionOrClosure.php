<?php

namespace App\Listeners;

use App\Events\WorkSpaceDeletedOrClosed;
use App\Mail\BookingDeleted;
use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $storageDestPath = public_path('EmailLogs.csv'); 
        
        if (!count($desk->users) < 1) {
            foreach ($desk->users as $user) {
                // Send email to effected users
                Mail::to($user->email)->send(new BookingDeleted($user));
                // Add to Log - Timestamp: Action: WHO($user->id) & WHAT(BookingID, $user->pivot->id)
                $timeofLog = Carbon::now('GMT-7');
                $logInfo = ""; 
                if(!Storage::exists($storageDestPath))
                {   
                    $logInfo = "Time,Action,Description,";
                    Storage::put($storageDestPath, $logInfo);
                    Storage::append($storageDestPath, $timeofLog . "," . "Booking Deleted," . "Booking ID:" . $user->pivot->id . "; User Affected:" . $user->first_name . " " . $user->last_name . ",");
                } else {
                    $logInfo .= $timeofLog . "," . "Booking Deleted," . "Booking ID:" . $user->pivot->id . "; User Affected:" . $user->first_name . " " . $user->last_name . ",";
                    Storage::append($storageDestPath, $logInfo);
                }
                Log::channel("email-notif-channel")->notice("Booking Deleted", ["User" => $user->id, "Booking ID" => $user->pivot->id]);
                // delete booking if hasn't been deleted already
                if (Bookings::find($user->pivot->id)->exists()) {
                    Bookings::find($user->pivot->id)->delete();
                }
            }
        }
    }
}
