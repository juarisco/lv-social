<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\Status;
use App\Http\Resources\CommentResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    function test_a_comment_resources_must_have_the_necessary_fields()
    {
        $comment = factory(Status::class)->create();

        $commentResource = CommentResource::make($comment)->resolve();
        // dd($commentResource);

        $this->assertEquals($comment->body, $commentResource['body']);
    }
}
