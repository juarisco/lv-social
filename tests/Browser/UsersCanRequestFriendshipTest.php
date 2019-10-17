<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_senders_can_create_and_delete_friendship_requests()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $recipient))
                ->press('@request-friendship')
                ->waitForText('Cancelar solicitud')
                ->assertSee('Cancelar solicitud')
                ->visit(route('users.show', $recipient))
                ->assertSee('Cancelar solicitud')
                ->press('@request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad');
        });
    }

    function test_senders_can_delete_accepted_friendship_requests()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $recipient))
                ->assertSee('Eliminar de mis amigos')
                ->press('@request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
                ->visit(route('users.show', $recipient))
                ->assertSee('Solicitar amistad');
        });
    }

    function test_senders_cannot_delete_denied_friendship_requests()
    {
        $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $recipient))
                ->assertSee('Solicitud denegada')
                ->press('@request-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visit(route('users.show', $recipient))
                ->assertSee('Solicitud denegada');
        });
    }

    function test_recipients_can_accept_friendship_requests()
    {
        // $this->markTestIncomplete();
        // $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@accept-friendship')
                ->waitForText('son amigos')
                ->assertSee('son amigos')
                ->visit(route('accept-friendships.index'))
                ->assertSee('son amigos');
        });
    }

    function test_recipients_can_deny_friendship_requests()
    {
        // $this->markTestIncomplete();
        // $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@deny-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visit(route('accept-friendships.index'))
                ->assertSee('Solicitud denegada');
        });
    }

    function test_recipients_can_delete_friendship_requests()
    {
        // $this->markTestIncomplete();
        // $this->withoutExceptionHandling();

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@delete-friendship')
                ->waitForText('Solicitud eliminada')
                ->assertSee('Solicitud eliminada')
                ->visit(route('accept-friendships.index'))
                ->assertDontSee('Solicitud eliminada')
                ->assertDontSee($sender->name);
        });
    }
}
