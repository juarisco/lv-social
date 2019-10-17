<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CanRequestFriendshipTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_users_cannot_create_friendship_request()
    {
        // $this->withoutExceptionHandling();
        $recipient = factory(User::class)->create();

        $response = $this->postJson(route('friendships.store', $recipient));

        $response->assertStatus(401);
    }

    function test_can_create_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $response = $this->actingAs($sender)->postJson(route('friendships.store', $recipient));

        $response->assertJson([
            'friendship_status' => 'pending'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);

        $this->actingAs($sender)->postJson(route('friendships.store', $recipient));
        $this->assertCount(1, Friendship::all());
    }

    function test_senders_can_delete_sent_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($sender)->deleteJson(route('friendships.destroy', $recipient));

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
    }

    function test_senders_cannot_delete_denied_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $response = $this->actingAs($sender)->deleteJson(route('friendships.destroy', $recipient));

        // $response->assertJson([
        //     'friendship_status' => 'denied'
        // ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);
    }

    function test_recipients_can_delete_received_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($recipient)->deleteJson(route('friendships.destroy', $sender));

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
    }

    function test_guests_users_cannot_delete_friendship_request()
    {
        // $this->withoutExceptionHandling();
        $recipient = factory(User::class)->create();

        $response = $this->deleteJson(route('friendships.destroy', $recipient));

        $response->assertStatus(401);
    }

    function test_can_accept_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($recipient)->postJson(route('accept-friendships.store', $sender));

        $response->assertJson([
            'friendship_status' => 'accepted'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);
    }

    function test_guests_users_cannot_accept_friendship_request()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->postJson(route('accept-friendships.store', $user));

        $response->assertStatus(401);
    }

    function test_can_deny_frienship_request()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($recipient)->deleteJson(route('accept-friendships.destroy', $sender));

        $response->assertJson([
            'friendship_status' => 'denied'
        ]);


        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied',
        ]);
    }

    function test_guests_users_cannot_deny_friendship_request()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->deleteJson(route('accept-friendships.destroy', $user));

        $response->assertStatus(401);
    }
}
