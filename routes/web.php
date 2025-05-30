<?php

declare(strict_types=1);

use App\Livewire\CollectPage;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'front.',
], function () {
    Route::get('/', CollectPage::class);
});
