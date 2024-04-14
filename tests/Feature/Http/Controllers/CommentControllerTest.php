<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_store_a_comment(): void
    {
        $comment = 'great book';
        $normalUser = User::factory()->create();
        $listing = Listing::factory()->create();

        $this->actingAs($normalUser)
            ->postJson(route('comment.store'), ['comment' => $comment, 'listing' => $listing->id])
            ->assertCreated();

        $commentModel = Comment::query()->where('content', $comment)->first();
        $this->assertNotEmpty($commentModel, "The comment was not created.");
        $createdComment = $listing->fresh()->comments()->first();
        $this->assertTrue($createdComment->is($commentModel), "The comment was not assigned to the listing");
        $this->assertTrue($createdComment->user->is($normalUser), 'The user was not recorded correctly');
    }

    #[Test]
    public function user_who_created_a_comment_can_delete_that_comment()
    {
        $commentOwner = User::factory()->create();
        $comment = Comment::factory()->for(Listing::factory(), 'commentable')
            ->create(['user_id' => $commentOwner->id]);

        $this->actingAs($commentOwner)->deleteJson(route('comment.destroy', $comment))->assertStatus(204);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
