<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

});
