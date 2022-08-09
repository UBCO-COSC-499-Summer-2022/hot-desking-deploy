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
        Mail::to($user->email)->send(new UserUnSuspended($user));
        //Write to Log - Timestamp : Action : $user->id suspended
        $timeofLog = Carbon::now('GMT-7');
        $logInfo = "[".$timeofLog."] Unsuspended User { User ID: " . $user->id . ", Name: " . $user->first_name . " " . $user->last_name . " }"; 
        Log::channel("email-notif-channel")->notice("Suspended User", ["User ID" => $user->id, "Name" => $user->first_name . ' ' . $user->last_name]);
        Storage::prepend('Email-Logs.txt', $logInfo);
    }
}
