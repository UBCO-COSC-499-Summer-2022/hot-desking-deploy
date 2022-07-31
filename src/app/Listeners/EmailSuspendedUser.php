<?php

namespace App\Listeners;

use App\Events\UserIsSuspended;
use App\Mail\UserSuspended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
        $user = $event->user;
        Mail::to($user->email)->send(new UserSuspended($user));
    }
}
