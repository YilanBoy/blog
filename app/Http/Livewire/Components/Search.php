<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Post;

class Search extends Component
{
    public string $search = '';

    public function render()
    {
        $results = collect();

        if (strlen($this->search) >= 2) {
            $results = Post::search($this->search)->take(10)->get();
        }

        return view('livewire.components.search', ['results' => $results]);
    }
}