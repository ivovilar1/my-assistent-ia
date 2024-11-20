<?php

namespace App\Action;

use App\Models\User;
use App\Notifications\NewUserNotification;

readonly class CreatePayment
{
    public function handle(User $user): array
    {
        $result = $user->checkout('price_1QNGbqFlGzA0kBgwEcdeeYOg', [
            'phone_number_collection' => ['enabled' => true ],
            'mode' => 'subscription',
            'success_url' => 'https://wa.me/' . str_replace("+", "", config('services.twilio.from')),
            'cancel_url' => 'https://wa.me/' . str_replace("+", "", config('services.twilio.from')),
        ])->toArray();

        $user->notify(new NewUserNotification($user->name, str_replace("https://checkout.stripe.com/c/pay/", "", $result["url"])));
        return $result;
    }
}
