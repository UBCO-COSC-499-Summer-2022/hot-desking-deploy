<?php

namespace Tests\Unit;

use App\Http\Controllers\User\UserBookingsController;
use App\Models\Bookings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserDatabaseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_Booking_Delete_Function()
    {
        $bookingToBeDeleted = Bookings::factory()->create();

        $controller = new UserBookingsController();
        $controller->cancel($bookingToBeDeleted->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingToBeDeleted->id,
        ]);
    }
}