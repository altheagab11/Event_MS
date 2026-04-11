<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/login', function () {
    return view('admin-login');
})->name('admin.login');
