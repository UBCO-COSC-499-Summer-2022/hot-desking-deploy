<?php

namespace Tests\Unit;

use App\Http\Controllers\User\ChangeEmailController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ChangeEmailTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that an email can be submitted for updating
     *
     * @return void
     */
    public function test_change_email_address_pending_success() {
        // Create a fake user
        $user = User::factory()->create();

        // Create a fake email address
        $email = $this->faker->unique()->safeEmail();

        // Fake the email change request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "new-email" => $email, 
            "new-email-confirm" => $email, 
        ]);

        // Create a controller
        $controller = new ChangeEmailController();

        // Pass in our request
        $controller->changeEmailPost($request);

        // Assert that the email is now in the pending emails table
        $this->assertDatabaseHas("pending_user_emails", [
            "email" => $email,
        ]);
    }

    /**
     * Test that an email can fail to be submitted due to the user entering in 2 different emails
     *
     * @return void
     */
    public function test_change_email_address_pending_failure_on_email_mismatch() {
        // We expect an exception in this code, so define that for this test
        $this->expectException(ValidationException ::class);

        // Create a fake user
        $user = User::factory()->create();

        // Create a fake email address
        $firstEmail = $this->faker->unique()->safeEmail();
        $secondEmail = $this->faker->unique()->safeEmail();

        // Fake the email change request
        $request = Request::create('', '', [
            "user_id" => $user->id,
            "new-email" => $firstEmail, 
            "new-email-confirm" => $secondEmail, 
        ]);

        // Create a controller
        $controller = new ChangeEmailController();

        // Pass in our request
        $controller->changeEmailPost($request);

        // Assert that the email is not in the pending emails table
        $this->assertDatabaseMissing("pending_user_emails", [
            "email" => $firstEmail,
        ]);
    }

    /**
     * Test that an email can fail when submitted an email that already belongs to a user
     *
     * @return void
     */
    public function test_change_email_address_pending_failure_on_existing_email() {
        // Create two fake users
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        // Create a fake email address and assign it to the firstUser
        $email = $this->faker->unique()->safeEmail();
        $secondUser->email = $email;
        // Save our changes to the database
        $secondUser->save();

        // Fake the email change request
        $request = Request::create('', '', [
            "user_id" => $firstUser->id,
            "new-email" => $email, 
            "new-email-confirm" => $email, 
        ]);

        // Create a controller
        $controller = new ChangeEmailController();

        // Pass in our request
        $controller->changeEmailPost($request);

        // Assert that the email is not in the pending emails table
        $this->assertDatabaseMissing("pending_user_emails", [
            "user_id" => $firstUser->id,
            "email" => $email,
        ]);
    }
}
