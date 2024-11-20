<?php

namespace App\Http\Controllers\WhatsappController;

use App\Action\CreatePayment;
use App\Action\CreateUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Http\Request;

class NewMessage extends Controller
{
    public function __invoke(Request $request, CreateUser $createUser, CreatePayment $createPayment): void
    {
        $phone = "+" . $request->post('WaId');
        $user = User::query()
            ->where('phone', $phone)
            ->first();
        if (!$user) {
            $user = $createUser->handle($request->all());
        }
        if (!$user->subscribed()) {
            $createPayment->handle($user);
        }
    }
}
