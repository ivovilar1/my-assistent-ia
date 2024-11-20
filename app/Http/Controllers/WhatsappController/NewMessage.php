<?php

namespace App\Http\Controllers\WhatsappController;

use App\Action\Conversation;
use App\Action\CreatePayment;
use App\Action\CreateUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Http\Request;

class NewMessage extends Controller
{
    public function __invoke(Request $request, CreateUser $createUser, CreatePayment $createPayment, Conversation $conversation): void
    {
        $phone = "+" . $request->post('WaId');
        $data = $request->all();
        $user = User::query()
            ->where('phone', $phone)
            ->first();
        if (!$user) {
            $user = $createUser->handle($data);
        }
        if (!$user->subscribed()) {
            $createPayment->handle($user);
        }
        $user->last_whatsapp_at = now();
        $user->save();

        $conversation->setUser($user);
        $conversation->handleIncomingMessage($data);
    }
}
