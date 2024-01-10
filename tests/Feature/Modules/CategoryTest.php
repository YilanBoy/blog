<?php


use App\Models\Category;
use App\Models\Post;

it('has posts', function () {
    $category = Category::factory()
        ->has(Post::factory()->count(3))
        ->create();

    expect($category->posts)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Post::class);
});

it('has link with name', function () {
    $category = Category::factory()->create();

    expect($category->link_with_name)
        ->toBe(route('categories.show', [
            'category' => $category->id,
            'name' => $category->name,
        ]));
})->group('has link with name');
