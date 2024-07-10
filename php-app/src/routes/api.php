<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Welcome to the Frisbo Takeathome project. Good luck!";
});
