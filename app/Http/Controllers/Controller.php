<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Controller
{
    /**
     * Retourne une URL "intended" sûre (même domaine), ou null.
     * Accepte soit un Request, soit une chaîne d'URL.
     */
    protected function safeIntendedUrl(Request|string|null $source): ?string
    {
        // Si on reçoit un Request, on lit la session
        if ($source instanceof Request) {
            $url = $source->session()->get('url.intended');
        } else {
            // Sinon on utilise directement la chaîne fournie
            $url = $source;
        }

        if (!$url) {
            return null;
        }

        $appUrl = config('app.url');

        // Autoriser les URLs relatives (ex: /client/panier)
        if (Str::startsWith($url, '/') && !Str::startsWith($url, '//')) {
            return $url;
        }

        // Autoriser uniquement les URLs du même domaine
        if (!Str::startsWith($url, $appUrl)) {
            return null;
        }

        return $url;
    }
}