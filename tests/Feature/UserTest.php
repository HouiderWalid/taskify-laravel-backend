<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CustomRefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unique_email_signup()
    {
        $fullName = fake()->name();
        $email = fake()->email();
        $password = fake()->password();

        User::factory()->credentials($email, $password)->create();

        $response = $this->post('/api/sign_up', [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertJsonPath('code', 401);
    }

    public function test_successful_register(): void
    {
        $fullName = fake()->name();
        $email = fake()->email();
        $password = fake()->password();
        $response = $this->post('/api/sign_up', [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertJsonPath('code', 200);

        $signedUpUser = User::where(User::getEmailAttributeName(), $email)
            ->where(User::getFullNameAttributeName(), $fullName)
            ->first();

        $this->assertNotNull($signedUpUser);
    }

    public function test_successful_login(): void
    {
        $email = fake()->email();
        $password = fake()->password();
        User::factory()->credentials($email, $password)->create();
        $response = $this->post('/api/sign_in', [
            'email' => $email,
            'password' => $password,
        ]);
        $response->assertJsonPath('code', 200);
    }
}
