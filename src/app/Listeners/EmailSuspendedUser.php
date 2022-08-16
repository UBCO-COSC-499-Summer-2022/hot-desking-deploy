<?php

namespace App\Listeners;

use App\Events\UserIsSuspended;
use App\Mail\UserSuspended;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\File as File;

class EmailSuspendedUser
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
     * @param  \App\Events\UserIsSuspended  $event
     * @return void
     */
    public function handle(UserIsSuspended $event)
    {
        //
        $storageDestPath = public_path('EmailLogs.csv'); 
        $user = $event->user;
        Mail::to($user->email)->send(new UserSuspended($user));
        //Write to Log - Timestamp : Action : $user->id suspended
        $timeofLog = Carbon::now('GMT-7');
        $logInfo = "";
        if(!Storage::exists($storageDestPath))
        {
            $logInfo = "Time,Action,Description,";
            $firstEntry = $timeofLog . "," . "Suspend User," . "Suspended User: " . $user->id . "; " . $user->first_name . " " . $user->last_name . ",";
            Storage::put($storageDestPath, $firstEntry);
        } else {
            $logInfo .= $timeofLog . "," . "Suspend User," . "Suspended User: " . $user->id . "; " . $user->first_name . " " . $user->last_name . ",";
            Storage::append($storageDestPath, $logInfo);
        }
        Log::channel("email-notif-channel")->notice("Suspended User", ["User ID" => $user->id, "Name" => $user->first_name . ' ' . $user->last_name]);
    }
}
