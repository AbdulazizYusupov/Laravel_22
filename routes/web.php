<?php

use App\Livewire\AttCharLivewire;
use App\Livewire\AttributeLivewire;
use App\Livewire\CategoryLivewire;
use App\Livewire\CharacterLivewire;
use App\Livewire\ElementLivewire;
use App\Livewire\OptionLivewire;
use App\Livewire\ProductLivewire;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/category', CategoryLivewire::class);
Route::get('/attribute', AttributeLivewire::class);
Route::get('/character',CharacterLivewire::class);
Route::get('/attchar',AttCharLivewire::class);
Route::get('/product',ProductLivewire::class);
Route::get('/element',ElementLivewire::class);
Route::get('/option',OptionLivewire::class);