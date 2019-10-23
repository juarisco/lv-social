<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NewCommentNotificationTest extends TestCase
{
    use RefreshDatabase;

    function test_the_notification_is_store_in_the_database()
    {
        $status = factory(Status::class)->create();
        $comment = factory(Comment::class)->create(['status_id' => $status->id]);

        $statusOwner = $status->user;

        $statusOwner->notify(new NewCommentNotification($comment));

        $this->assertCount(1, $statusOwner->notifications);

        $notificationData = $statusOwner->notifications->first()->data;

        $this->assertEquals($comment->path(), $notificationData['link']);
        $this->assertEquals("{$comment->user->name} comentó tu publicación.", $notificationData['message']);
    }
}
