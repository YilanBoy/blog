<?php

namespace Tests\Feature;

use App\Http\Livewire\Posts;
use App\Models\Category;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_index_can_be_rendered()
    {
        $user = User::factory()->create();

        $posts = Post::factory(10)->create(
            [
                'category_id' => rand(1, 3),
                'user_id' => $user->id,
            ]
        );

        $this->get(route('posts.index'))
            ->assertStatus(200)
            ->assertSeeLivewire('posts');
    }

    public function test_category_can_filter_posts()
    {
        $user = User::factory()->create();

        $categoryOnePost = Post::factory()->make([
            'title' => 'this post belongs to category one',
            'user_id' => $user->id,
            'category_id' => 1,
        ]);

        $categoryOnePost->save();

        Livewire::test(Posts::class)
            ->set('category', Category::first())
            ->assertViewHas('posts', function ($posts) {
                return $posts->count() === 1;
            });

        Livewire::test(Posts::class)
            ->set('category', Category::find(2))
            ->assertViewHas('posts', function ($posts) {
                return $posts->count() === 0;
            });
    }

    public function test_order_query_string_filters_correctly()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'title' => 'this post is updated recently',
            'user_id' => $user->id,
            'created_at' => now()->subDays(20),
            'updated_at' => now(),
        ]);

        Post::factory()->create([
            'title' => 'this post is the latest',
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        Post::factory()->create([
            'title' => 'this post has the most comments',
            'user_id' => $user->id,
            'comment_count' => 10,
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(15),
        ]);

        Livewire::withQueryParams(['order' => 'latest'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post is the latest';
            });

        Livewire::withQueryParams(['order' => 'recent'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post is updated recently';
            });

        Livewire::withQueryParams(['order' => 'comment'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post has the most comments';
            });
    }

    public function test_user_can_view_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->make();
        $post->user_id = $user->id;
        $post->category_id = 3;
        $post->save();

        $this->get('/posts/' . $post->id)
            ->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    public function test_guest_can_not_visit_create_post_page()
    {
        $this->get(route('posts.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_login_user_can_visit_create_post_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('posts.create'))
            ->assertSuccessful();
    }

    public function test_guest_can_not_create_post()
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'This is a test post title',
            'category_id' => 1,
            'body' => 'This is a test post body'
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_login_user_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'This is a test post title',
            'category_id' => 1,
            'body' => 'This is a test post body'
        ]);

        $latestPost = Post::latest()->first();

        $response->assertStatus(302)
            ->assertRedirect(route('posts.show', ['post' => $latestPost->id, 'slug' => $latestPost->slug]));

        $this->assertDatabaseHas('posts', [
            'title' => 'This is a test post title',
            'category_id' => 1,
            'body' => 'This is a test post body'
        ]);
    }

    public function test_author_can_soft_delete_own_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => 'This is a test post title',
            'user_id' => $user->id,
            'category_id' => 1,
            'body' => 'This is a test post body'
        ]);

        $response = $this->actingAs($user)
            ->delete(route('posts.destroy', ['id' => $post->id]));

        $response->assertStatus(302)
            ->assertRedirect(route('users.index', ['user' => $user->id]));
    }
}
