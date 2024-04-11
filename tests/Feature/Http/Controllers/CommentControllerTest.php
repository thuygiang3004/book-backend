<?php

namespace Tests\Feature\Http\Controllers;

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

        $this->actingAs($normalUser)
            ->postJson(route('comment.store'), ['comment' => $comment])
            ->assertCreated();
    }
}
