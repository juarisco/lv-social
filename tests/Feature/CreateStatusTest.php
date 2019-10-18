<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Status;
use App\Events\StatusCreated;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\StatusResource;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_users_can_not_create_statuses()
    {
        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertStatus(401);
    }

    function test_an_authenticated_user_can_create_statuses()
    {
        Event::fake([StatusCreated::class]);

        // Para evitar que laravel maneje las excepciones
        $this->withoutExceptionHandling();

        // 1. Given => Teniendo un usuario autenticado
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // 2. When => Cuando hace un post request a status
        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertJson([
            'data' => ['body' => 'Mi primer status']
        ]);

        // 3. Then => Entonces veo un nuevo estado en la base de datos
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'Mi primer status'
        ]);
    }

    function test_an_event_is_fired_when_a_status_is_created()
    {
        Event::fake([StatusCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        // Para evitar que laravel maneje las excepciones
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user)->postJson(route('statuses.store'), ['body' => 'Mi primer status']);

        Event::assertDispatched(StatusCreated::class, function ($statusCreatedEvent) {
            $this->assertInstanceOf(ShouldBroadcast::class, $statusCreatedEvent);
            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertInstanceOf(Status::class, $statusCreatedEvent->status->resource);
            $this->assertEquals(Status::first()->id, $statusCreatedEvent->status->id);
            $this->assertEquals(
                'socket-id',
                $statusCreatedEvent->socket,
                'The event ' . get_class($statusCreatedEvent) . ' must call the method "dontBroadcastToCurrentUser" in the constructor.'
            );
            return true;
        });
    }

    function test_a_status_requires_a_body()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    function test_a_status_requires_a_minimum_length()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => 'asdf']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}
