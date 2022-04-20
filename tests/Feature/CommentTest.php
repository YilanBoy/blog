<?php

use App\Http\Livewire\Comments\CommentBox;
use App\Http\Livewire\Comments\CommentsGroup;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('guest can not comment', function () {
    $post = Post::factory()->create();

    $content = 'This is a comment';

    livewire(CommentBox::class, ['postId' => $post->id])
        ->set('content', $content)
        ->call('store')
        ->assertForbidden();
});

test('the authenticated user can make comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $content = 'This is a comment';

    livewire(CommentBox::class, ['postId' => $post->id])
        ->set('content', $content)
        ->call('store');

    $this->assertDatabaseHas('comments', [
        'content' => $content
    ]);
});

test('the author can delete his comment', function () {
    $comment = Comment::factory()->create();

    $this->actingAs(User::find($comment->user_id));

    livewire(CommentsGroup::class, [
        'postId' => $comment->post_id,
        'offset' => 0,
        'perPage' => 10
    ])
        ->call('destroy', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('post author can delete other users comment', function () {
    $comment = Comment::factory()->create();

    $this->actingAs(User::find($comment->post->user_id));

    livewire(CommentsGroup::class, [
        'postId' => $comment->post_id,
        'offset' => 0,
        'perPage' => 10
    ])
        ->call('destroy', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
