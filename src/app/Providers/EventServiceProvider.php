<?php

namespace App\Providers;

use App\Events\BookingConfirmation;
use App\Events\UserIsSuspended;
use App\Events\UserIsUnSuspended;
use App\Events\WorkSpaceDeletedOrClosed;
use App\Listeners\EmailSuspendedUser;
use App\Listeners\EmailUnSuspendedUser;
use App\Listeners\EmailUserEffectedByBookingConfirmation;
use App\Listeners\EmailUsersEffectedByWorkspaceDeletionOrClosure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WorkSpaceDeletedOrClosed::class => [
            EmailUsersEffectedByWorkspaceDeletionOrClosure::class,
        ],
        UserIsSuspended::class => [
            EmailSuspendedUser::class,
        ],
        UserIsUnSuspended::class => [
            EmailUnSuspendedUser::class
        ],
        BookingConfirmation::class => [
            EmailUserEffectedByBookingConfirmation::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
