<?php

namespace App\Livewire\Shared\Users;

use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use MarkdownConverter;
    use WithPagination;

    public int $userId;

    public function updatedPaginators(): void
    {
        $javaScript = <<<'JS'
            window.scrollTo({ top: 0, behavior: 'smooth' })
        JS;

        $this->js($javaScript);
    }

    public function render()
    {
        // get the comments from this user
        $comments = Comment::whereUserId($this->userId)
            ->select(['created_at', 'post_id', 'body'])
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post:id,title,slug')
            ->latest()
            ->paginate(10, ['*'], 'comments-page')
            ->withQueryString();

        // convert the body from markdown to html
        $comments->getCollection()->transform(function ($comment) {
            $comment->body = $this->convertToHtml($comment->body);

            return $comment;
        });

        return view('livewire.shared.users.comments', ['comments' => $comments]);
    }
}
