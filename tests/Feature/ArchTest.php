<?php

arch()->preset()->laravel();

arch()->preset()->security();

arch()
    ->expect('App\Enums')
    ->toBeEnums();

arch('livewire full-page component must have a  \'Page\' suffix')
    ->expect('App\Livewire\Pages')
    ->toHaveSuffix('Page');
