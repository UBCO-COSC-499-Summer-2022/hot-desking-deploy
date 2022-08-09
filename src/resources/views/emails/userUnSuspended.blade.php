@component('mail::message')

# Your account has been unsuspended

You may now make bookings again using the website.

@component('mail::button', ['url' => config('app.url')])
    Book Now
@endcomponent

@endcomponent