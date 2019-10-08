<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CanLikeStatusesTest extends TestCase
{
    // use DatabaseMigrations;
    use RefreshDatabase;

    function test_guests_users_can_not_like_statuses()
    {
        // $this->withoutExceptionHandling();
        $status = factory(Status::class)->create();

        $response = $this->post(route('statuses.likes.store', $status));

        $response->assertRedirect('login');
    }

    function test_an_authenticated_user_can_like_statuses()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();

        $this->actingAs($user)->postJson(route('statuses.likes.store', $status));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);
    }

    function test_an_authenticated_user_can_unlike_statuses()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();

        $this->actingAs($user)->postJson(route('statuses.likes.store', $status));

        $this->actingAs($user)->deleteJson(route('statuses.likes.destroy', $status));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);
    }
}
