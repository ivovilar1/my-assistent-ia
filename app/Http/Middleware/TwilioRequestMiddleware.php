<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Security\RequestValidator;

class TwilioRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = new RequestValidator(config('services.twilio.auth_token'));
        $signature = $request->headers->get('X-Twilio-Signature') ?? "";
        $is_valid = $validator->validate($signature, config('services.twilio.new_message_url'), $request->all());
        if ($is_valid) {
            return $next($request);
        }
        abort(Response::HTTP_FORBIDDEN);
    }
}
