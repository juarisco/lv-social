<?php


namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    function test_a_friendship_request_belongs_to_a_sender()
    {
        $sender = factory(User::class)->create();

        $friendship = factory(Friendship::class)->create(['sender_id' => $sender->id]);

        $this->assertInstanceOf(User::class, $friendship->sender);
    }

    function test_a_friendship_request_belongs_to_a_recipient()
    {
        $recipient = factory(User::class)->create();

        $friendship = factory(Friendship::class)->create(['recipient_id' => $recipient->id]);

        $this->assertInstanceOf(User::class, $friendship->recipient);
    }
}
