<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class CanManageNotificationsTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_users_cannot_access_notifications()
    {
        $this->getJson(route('notifications.index'))->assertStatus(401);
    }

    function test_authenticated_users_can_get_their_notifications()
    {
        $user = factory(User::class)->create();

        $notification = factory(DatabaseNotification::class)->create(['notifiable_id' => $user->id]);

        $this->actingAs($user)->getJson(route('notifications.index'))
            ->assertJson([
                [
                    'data' => [
                        'link' => $notification->data['link'],
                        'message' => $notification->data['message'],
                    ]
                ]
            ]);
    }

    function test_authenticated_users_can_mark_notifications_as_read()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $notification = factory(DatabaseNotification::class)->create([
            'notifiable_id' => $user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($user)->postJson(route('read-notifications.store', $notification));

        $response->assertJson($notification->fresh()->toArray());

        $this->assertNotNull($notification->fresh()->read_at);
    }

    function test_guests_users_cannot_mark_notifications()
    {
        $notification = factory(DatabaseNotification::class)->create();

        $this->postJson(route('read-notifications.store', $notification))->assertStatus(401);
        $this->deleteJson(route('read-notifications.destroy', $notification))->assertStatus(401);
    }

    function test_authenticated_users_can_mark_notifications_as_unread()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $notification = factory(DatabaseNotification::class)->create([
            'notifiable_id' => $user->id,
            'read_at' => now()
        ]);

        $response = $this->actingAs($user)->deleteJson(route('read-notifications.destroy', $notification));

        $response->assertJson($notification->fresh()->toArray());

        $this->assertNull($notification->fresh()->read_at);
    }
}
