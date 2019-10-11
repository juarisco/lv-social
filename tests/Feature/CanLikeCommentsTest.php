<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CanLikeCommentsTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_users_can_not_like_comments()
    {
        // $this->withoutExceptionHandling();
        $comment = factory(Comment::class)->create();

        $response = $this->postJson(route('comments.likes.store', $comment));

        $response->assertStatus(401);
    }

    function test_an_authenticated_user_can_like_comments()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();

        $this->actingAs($user)->postJson(route('comments.likes.store', $comment));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            // 'status_id' => $status->id
        ]);
    }
}
