<?php

namespace Tests\Feature;

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
}
