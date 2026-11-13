<?php
// app/Http/Middleware/VerifyRecaptcha.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next, string $action = '', float $minScore = 0.5){
        $token = $request->input('recaptcha_token') ?? $request->header('X-Recaptcha-Token');

        $fail = fn (string $msg) => $request->expectsJson()
            ? response()->json(['error' => $msg], 403)
            : back()
                ->withInput($request->except('password', 'password_confirmation', 'recaptcha_token'))
                ->withErrors(['recaptcha' => $msg]);

        if (! $token) {
            return $fail('Vérification reCAPTCHA manquante. Rechargez la page.');
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

        if ($action && ($response['action'] ?? '') !== $action) {
            return $fail('Action reCAPTCHA invalide.');
        }

        if (($response['score'] ?? 0) < $minScore) {
            return $fail('Comportement suspect détecté. Réessayez.');
        }
        return $next($request);
    }
}