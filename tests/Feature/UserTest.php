<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\Helpers\CustomRefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use CustomRefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_successful_login(): void
    {
        $email = 'houiderwalid@gmail.com';
        $password = '123456789';
        User::factory()->credentials($email, $password)->create();
        $response = $this->post('/api/sign_in', [
            'email' => $email,
            'password' => $password,
        ]);
        $response->assertStatus(200);
    }
}
