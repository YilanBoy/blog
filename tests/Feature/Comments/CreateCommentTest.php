<?php

use App\Livewire\Shared\Comments\CreateCommentModal;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('non-logged-in users can leave a anonymous comment', function () {
    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertDispatched('create-new-comment-to-root-new-comment-group')
        ->assertDispatched('append-new-id-to-root-comment-list')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comments-count')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('logged-in users can leave a comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertDispatched('create-new-comment-to-root-new-comment-group')
        ->assertDispatched('append-new-id-to-root-comment-list')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comments-count')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('the message must be at least 5 characters long', function () {
    $post = Post::factory()->create();

    $body = str()->random(4);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['body' => 'min:5'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至少 5 個字元</p>');
});

test('the message must be less than 2000 characters', function () {
    $post = Post::factory()->create();

    $body = str()->random(2001);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['body' => 'max:2000'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至多 2000 個字元</p>');
});

test('the message must have the captcha token', function () {
    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->call('store')
        ->assertHasErrors()
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">未完成驗證</p>');
});

it('can see the comment preview', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->set('previewIsEnabled', true)
        ->assertSeeHtmlInOrder([
            '<p>Title</p>',
            '<p>This is a <strong>comment</strong></p>',
            '<p>Show a list</p>',
            '<ul>',
            '<li>item 1</li>',
            '<li>item 2</li>',
            '<li>item 3</li>',
            '</ul>',
        ]);
});

it('can reply to others comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id]);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', 'Hello World!')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store', parentId: $comment->id)
        ->assertDispatched('create-new-comment-to-'.$comment->id.'-new-comment-group')
        ->assertDispatched('append-new-id-to-'.$comment->id.'-comment-list')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comments-count')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );
});

it('will show alert, when user want to reply to deleted post', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();
    $postId = $post->id;

    $post->delete();

    livewire(CreateCommentModal::class, ['postId' => $postId])
        ->set('body', 'Hello World!')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertDispatched(event: 'info-badge', status: 'danger', message: '無法回覆！文章已被刪除！');
});

it('will show alert, when user want to reply to deleted comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id]);
    $commentId = $comment->id;

    $comment->delete();

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', 'Hello World!')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store', parentId: $commentId)
        ->assertDispatched(event: 'info-badge', status: 'danger', message: '無法回覆！留言已被刪除！');
});
