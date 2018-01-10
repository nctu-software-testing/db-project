<?php

namespace App\Http\Middleware;

use App\Service\IS_Encryption;
use Closure;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);
        $selfUrl = asset('/');
        $nonce = IS_Encryption::getNonce();
        $rule = [
            // "upgrade-insecure-requests",
            "block-all-mixed-content",
            "default-src 'none'",
            "script-src 'nonce-$nonce' $selfUrl",
            "style-src 'unsafe-inline' https://fonts.googleapis.com/ $selfUrl",
            "img-src data: blob: https://i.imgur.com/ $selfUrl",
            "font-src data: https://fonts.gstatic.com/ $selfUrl",
            "connect-src $selfUrl",
            "form-action $selfUrl",
            "frame-src " . action('CaptchaController@getIndex'),
        ];

        $ruleText = implode('; ', $rule);
        $response->headers->set('Content-Security-Policy', $ruleText);
        $response->headers->set('X-Content-Security-Policy', $ruleText);

        return $response;
    }
}
