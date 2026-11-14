<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $fail = fn (string $msg) => $request->expectsJson()
            ? response()->json(['error' => $msg], 403)
            : back()
                ->withInput($request->except('password', 'password_confirmation', 'g-recaptcha-response'))
                ->withErrors(['recaptcha' => $msg]);

        $token = $request->input('g-recaptcha-response');

        if (! $token) {
            return $fail('Cochez la case « Je ne suis pas un robot ».');
        }

        try {
            $response = Http::asForm()->timeout(5)->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]
            )->json();
        } catch (ConnectionException $e) {
            report($e);
            return $fail('Service de vérification indisponible. Réessayez.');
        }

        if (! ($response['success'] ?? false)) {
            return $fail('La vérification reCAPTCHA a échoué. Réessayez.');
        }
        return $next($request);
    }
}