<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    function test_users_can_register()
    {
        $this->withoutExceptionHandling();

        $userData = [
            'name' => 'JosefWaelchi',
            'first_name' => 'Josef',
            'last_name' => 'Waelchi',
            'email' => 'josef@mail.com',
            'password' => 'secret', // secret
            'password_confirmation' => 'secret',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'JosefWaelchi',
            'first_name' => 'Josef',
            'last_name' => 'Waelchi',
            'email' => 'josef@mail.com',
        ]);

        $this->assertTrue(
            Hash::check('secret', User::first()->password),
            'The password needs to be hashed'
        );
    }
}
