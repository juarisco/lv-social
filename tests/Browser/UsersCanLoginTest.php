<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_registered_users_can_login()
    {
        factory(User::class)->create([
            'email' => 'john@doe.com'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('login')
                ->type('email', 'john@doe.com')
                ->type('password', 'secret')
                ->press('@login-btn')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }

    function test_User_cannot_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', '')
                ->press('@login-btn')
                ->assertPathIs('/login')
                ->assertPresent('@validation-errors');
        });
    }
}
