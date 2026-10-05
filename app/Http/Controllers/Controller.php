<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Retourne l'URL si elle est interne à l'application (chemin relatif ou même origine),
     * sinon null. Empêche les open redirects (ex: https://shop.ci.evil.com).
     */
    protected function safeIntendedUrl(mixed $url): ?string
    {
        if (!is_string($url) || $url === '' || preg_match('/[\x00-\x1F\x7F\\\\]/', $url)) {
            return null;
        }

        // Chemin relatif : "/client/panier" ok, "//evil.com" refusé
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            return url($url);
        }

        // URL absolue : même schéma/hôte/port que l'application, sans identifiants
        $target = parse_url($url);
        $self   = parse_url(url('/'));
        if ($target === false || !isset($target['host'], $target['scheme']) || isset($target['user']) || isset($target['pass'])) {
            return null;
        }

        $sameOrigin = in_array($target['scheme'], ['http', 'https'], true)
            && strcasecmp($target['host'], $self['host'] ?? '') === 0
            && ($target['port'] ?? null) === ($self['port'] ?? null);

        return $sameOrigin ? $url : null;
    }
}