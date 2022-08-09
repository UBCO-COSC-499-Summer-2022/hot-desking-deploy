<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use Tests\TestCase;

class BookMaxCapacityRoomTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_before_case()
    {
        $data = [
        'make' => 'Ford'
        ];

        $this->post('/cars', $data);

        $this->assertDatabaseHas('cars', $data);
    }

    public function test_user_can_book_room_that_is_under_capacity() {
        $user = User::factory()->create();

        $this->assertDatabaseHas('bookings', [
            'email' => 'sally@example.com',
        ]);
    }

    public function test_user_can_book_room_that_is_at_capacity() {
        $user = User::factory()->create();

        $this->assertDatabaseMissing('bookings', [
            'email' => 'sally@example.com',
        ]);
    }

    public function test_user_can_book_room_that_is_under_capacity_with_percentage_applied() {
        $user = User::factory()->create();

        $this->assertDatabaseHas('bookings', [
            'email' => 'sally@example.com',
        ]);
    }

    public function test_user_can_book_room_that_is_over_capacity_with_percentage_applied() {
        $user = User::factory()->create();

        $this->assertDatabaseMissing('bookings', [
            'email' => 'sally@example.com',
        ]);
    }
}
