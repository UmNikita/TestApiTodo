<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task;

Route::get('/', function () {
    return view('welcome');
});