<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security headers for every web response.
 * - No framing by other sites (clickjacking), no MIME sniffing, limited referrer data.
 * - Browser features the site never uses are switched off.
 * - HSTS only on HTTPS in production (so local HTTP keeps working).
 * - Admin pages are never stored by the browser or a proxy (no cached copies after logout).
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        if ($request->isSecure() && app()->isProduction()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if ($request->is('admin', 'admin/*')) {
            $headers->set('Cache-Control', 'no-store, private');
            $headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
