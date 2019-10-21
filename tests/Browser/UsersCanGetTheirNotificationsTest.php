<?php

namespace Tests\Browser;

use App\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_user_can_see_their_notifications_in_the_nav_bar()
    {
        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();

        $notification = factory(DatabaseNotification::class)->create([
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Haz recibido un like',
                'link' => route('statuses.show', $status)
            ]
        ]);

        $this->browse(function (Browser $browser) use ($user, $notification, $status) {
            $browser->loginAs($user)
                ->visit('/')
                ->resize(1024, 768)
                ->click('@notifications')
                ->assertSee('Haz recibido un like')
                ->click("@{$notification->id}")
                ->assertUrlIs($status->path());
        });
    }
}
