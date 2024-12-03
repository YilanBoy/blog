<?php

namespace App\Livewire\Shared;

use App\Services\FileService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Random\RandomException;

class UploadImage extends Component
{
    use WithFileUploads;

    #[Validate('nullable')]
    #[Validate('mimes:jpeg,png,jpg', message: '圖片格式必須是 jpeg, png, jpg')]
    #[Validate('max:1024', message: '圖片大小不能超過 1024 KB')]
    public $image = null;

    #[Modelable]
    public string $imageUrl = '';

    /**
     * @throws RandomException
     */
    public function save(): void
    {
        $this->validate();

        if (is_null($this->image)) {
            return;
        }

        $imageName = app(FileService::class)
            ->generateFileName($this->image->getClientOriginalExtension());

        $path = $this->image->storeAs('images', $imageName, config('filesystems.default'));

        $this->imageUrl = Storage::disk()->url($path);
    }

    /**
     * @throws RandomException
     */
    public function updatedImage(): void
    {
        $this->save();
    }

    public function render()
    {
        return view('livewire.shared.upload-image');
    }
}
