<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use App\Models\Like;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    function test_a_status_belongs_to_a_user()
    {
        $status = factory(Status::class)->create();

        $this->assertInstanceOf(User::class, $status->user);
    }

    function test_a_status_has_many_likes()
    {
        $status = factory(Status::class)->create();

        factory(Like::class)->create(['status_id' => $status->id]);

        $this->assertInstanceOf(Like::class, $status->likes->first());
    }

    function test_a_status_can_be_liked_and_unliked()
    {
        $status = factory(Status::class)->create();

        $this->actingAs(factory(User::class)->create());

        $status->like();

        $this->assertEquals(1, $status->fresh()->likes->count());

        $status->unlike();

        $this->assertEquals(0, $status->fresh()->likes->count());
    }

    function test_a_status_can_be_liked_once()
    {
        $status = factory(Status::class)->create();

        $this->actingAs(factory(User::class)->create());

        $status->like();

        $this->assertEquals(1, $status->likes->count());

        $status->like();

        $this->assertEquals(1, $status->fresh()->likes->count());
    }

    function test_a_status_knows_if_it_has_been_liked()
    {
        $status = factory(Status::class)->create();

        $this->assertFalse($status->isLiked());

        $this->actingAs(factory(User::class)->create());
        $this->assertFalse($status->isLiked());

        $status->like();

        $this->assertTrue($status->isLiked());
    }

    function test_a_status_knows_how_many_likes_it_has()
    {
        $status = factory(Status::class)->create();

        $this->assertEquals(0, $status->likesCount());

        factory(Like::class, 2)->create(['status_id' => $status->id]);

        $this->assertEquals(2, $status->likesCount());
    }
}
