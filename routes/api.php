<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsappController;


// Webhooks
Route::post('/new_message', WhatsappController\NewMessage::class)
//    ->middleware('twilio_request')
    ->name('new_message');
//
