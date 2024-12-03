<?php

namespace App\Livewire\Pages\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePostPage extends Component
{
    use WithFileUploads;

    public PostForm $form;

    public string $autoSaveKey;

    public Collection $categories;

    #[Locked]
    public int $bodyMaxCharacter = 20_000;

    public function mount(): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_create_post';

        $this->form->user_id = auth()->id();

        $this->categories = Category::all(['id', 'name']);

        $this->form->setDataFromAutoSave($this->autoSaveKey);
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        $this->form->autoSave($this->autoSaveKey);
    }

    public function store(): void
    {
        $this->form->validatePost($this->bodyMaxCharacter);

        $post = $this->form->createPost();

        $this->form->clearAutoSave($this->autoSaveKey);

        $this->dispatch('info-badge', status: 'success', message: '成功新增文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('新增文章')]
    public function render(): View
    {
        return view('livewire.pages.posts.create-post-page');
    }
}
