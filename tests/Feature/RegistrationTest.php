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
        // $this->withoutExceptionHandling();

        $this->get(route('register'))->assertSuccessful();

        $response = $this->post(route('register'), $this->userValidData());

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'JosefWaelchi2',
            'first_name' => 'Josef',
            'last_name' => 'Waelchi',
            'email' => 'josef@mail.com',
        ]);

        $this->assertTrue(
            Hash::check('secret', User::first()->password),
            'The password needs to be hashed'
        );
    }


    function test_the_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => null])
        )->assertSessionHasErrors('name');
    }

    function test_the_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => 1234])
        )->assertSessionHasErrors('name');
    }

    function test_the_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => str_random(61)])
        )->assertSessionHasErrors('name');
    }

    function test_the_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => 'as'])
        )->assertSessionHasErrors('name');
    }

    function test_the_name_must_be_unique()
    {
        factory(User::class)->create(['name' => 'JosefWaelchi']);
        $this->post(
            route('register'),
            $this->userValidData(['name' => 'JosefWaelchi'])
        )->assertSessionHasErrors('name');
    }

    function test_name_may_only_contain_letters_and_numbers()
    {
        $this->post(
            route('register'),
            $this->userValidData(['name' => 'Josef Waelchi'])
        )->assertSessionHasErrors('name');

        $this->post(
            route('register'),
            $this->userValidData(['name' => 'JosefWaelchi<>'])
        )->assertSessionHasErrors('name');
    }

    function test_the_first_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => null])
        )->assertSessionHasErrors('first_name');
    }

    function test_the_first_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 1234])
        )->assertSessionHasErrors('first_name');
    }

    function test_the_first_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => str_random(61)])
        )->assertSessionHasErrors('first_name');
    }


    function test_the_first_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 'as'])
        )->assertSessionHasErrors('first_name');
    }

    function test_first_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 'Josef2'])
        )->assertSessionHasErrors('first_name');

        $this->post(
            route('register'),
            $this->userValidData(['first_name' => 'JosefWaelchi<>'])
        )->assertSessionHasErrors('first_name');
    }

    function test_the_last_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => null])
        )->assertSessionHasErrors('last_name');
    }

    function test_the_last_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 1234])
        )->assertSessionHasErrors('last_name');
    }

    function test_the_last_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => str_random(61)])
        )->assertSessionHasErrors('last_name');
    }

    function test_the_last_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 'as'])
        )->assertSessionHasErrors('last_name');
    }

    function test_last_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 'Josef2'])
        )->assertSessionHasErrors('last_name');

        $this->post(
            route('register'),
            $this->userValidData(['last_name' => 'JosefWaelchi<>'])
        )->assertSessionHasErrors('last_name');
    }

    function test_the_email_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['email' => null])
        )->assertSessionHasErrors('email');
    }

    function test_the_email_must_be_a_valid_email_address()
    {
        $this->post(
            route('register'),
            $this->userValidData(['email' => 'invalid@mail'])
        )->assertSessionHasErrors('email');
    }

    function test_the_email_must_be_unique()
    {
        factory(User::class)->create(['email' => 'josef@mail.com']);
        $this->post(
            route('register'),
            $this->userValidData(['email' => 'josef@mail.com'])
        )->assertSessionHasErrors('email');
    }


    function test_the_password_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => null])
        )->assertSessionHasErrors('password');
    }

    function test_the_password_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => 1234])
        )->assertSessionHasErrors('password');
    }

    function test_the_password_must_be_at_least_6_characters()
    {
        $this->post(
            route('register'),
            $this->userValidData(['password' => 'asdfg'])
        )->assertSessionHasErrors('password');
    }

    function test_the_password_must_be_confirmed()
    {
        $this->post(
            route('register'),
            $this->userValidData([
                'password' => 'secret',
                'password_confirmation' => null
            ])
        )->assertSessionHasErrors('password');
    }

    protected function userValidData($overrides = []): array
    {
        return array_merge([
            'name' => 'JosefWaelchi2',
            'first_name' => 'Josef',
            'last_name' => 'Waelchi',
            'email' => 'josef@mail.com',
            'password' => 'secret', // secret
            'password_confirmation' => 'secret',
        ], $overrides);
    }
}
