<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ListStatusesTest extends TestCase
{
    use DatabaseMigrations;

    function test_can_get_all_statuses()
    {
        $this->withoutExceptionHandling();

        $status1 = factory(Status::class)->create(['created_at' => now()->subDays(4)]);
        $status2 = factory(Status::class)->create(['created_at' => now()->subDays(3)]);
        $status3 = factory(Status::class)->create(['created_at' => now()->subDays(2)]);
        $status4 = factory(Status::class)->create(['created_at' => now()->subDays(1)]);

        $response = $this->getJson(route('statuses.index'));

        $response->assertJson([
            'meta' => ['total' => 4]
        ]);

        $response->assertJsonStructure([
            'data', 'links' => ['prev', 'next']
        ]);

        // dd($response->json('data.0.id'));
        // dd($response->json('data'));
        $this->assertEquals(
            $status4->body,
            $response->json('data.0.body')
        );
    }

    public function test_can_get_statuses_for_a_specific_user()
    {
        $user = factory(User::class)->create();
        $status1 = factory(Status::class)->create(['user_id' => $user->id, 'created_at' => now()->subDay()]);
        $status2 = factory(Status::class)->create(['user_id' => $user->id]);

        $otherStatuses = factory(Status::class, 2)->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.statuses.index', $user));

        $response->assertJson([
            'meta' => ['total' => 2]
        ]);

        $response->assertJsonStructure([
            'data', 'links' => ['prev', 'next']
        ]);

        $this->assertEquals(
            $status2->body,
            $response->json('data.0.body')
        );
    }
}
