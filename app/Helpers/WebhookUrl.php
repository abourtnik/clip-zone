<?php

namespace App\Helpers;

class WebhookUrl
{
    public static function for(string $routeName, array $parameters = []): string
    {
        $base = config('services.webhook_base_url');

        if ($base) {
            return rtrim($base, '/') . route($routeName, $parameters, absolute: false);
        }

        return route($routeName, $parameters);
    }
}
