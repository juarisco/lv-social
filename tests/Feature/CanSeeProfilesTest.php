<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;

    function test_can_see_profiles_test()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['name' => 'Josef']);

        $this->get('@Josef')->assertSee('Josef');
    }
}
