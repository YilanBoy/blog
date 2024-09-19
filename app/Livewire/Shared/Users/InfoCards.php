<?php

namespace App\Livewire\Shared\Users;

use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class InfoCards extends Component
{
    public string $userId;

    public function render(): View
    {
        $user = User::find($this->userId)
            ->loadCount(['posts as posts_count_in_this_year' => function ($query) {
                $query->whereYear('created_at', date('Y'));
            }]);

        $categories = Category::with(['posts' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view(
            'livewire.shared.users.info-cards',
            compact('user', 'categories'),
        );
    }
}
