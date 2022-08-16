<?php

namespace App\Listeners;

use App\Events\UserIsUnSuspended;
use App\Mail\UserUnSuspended;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailUnSuspendedUser
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
     * @param  \App\Events\UserIsUnSuspended  $event
     * @return void
     */
    public function handle(UserIsUnSuspended $event)
    {
        //
        $user = $event->user;
        $storageDestPath = public_path('EmailLogs.csv'); 
        Mail::to($user->email)->send(new UserUnSuspended($user));
        //Write to Log - Timestamp : Action : $user->id suspended
        $timeofLog = Carbon::now('GMT-7');
        $logInfo = ""; 
        if(!Storage::exists($storageDestPath))
        {
            $logInfo = "Time,Action,Description,". 
            Storage::put($storageDestPath, $logInfo);
            $firstEntry = $timeofLog . "," . "Unsuspend User," . "Unsuspended User: " . $user->id . "; " . $user->first_name . " " . $user->last_name . ",";
            Storage::append($storageDestPath, $firstEntry);
        } else {
            $logInfo = $timeofLog . "," . "Unsuspend User," . "Unsuspended User: " . $user->id . "; " . $user->first_name . " " . $user->last_name . ",";
            Storage::append($storageDestPath, $logInfo);
        }
        Log::channel("email-notif-channel")->notice("Unsuspend User", ["User ID" => $user->id, "Name" => $user->first_name . ' ' . $user->last_name]);
    }
}
