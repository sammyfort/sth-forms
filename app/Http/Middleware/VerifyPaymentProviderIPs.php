<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaymentProviderIPs
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = [
            '52.50.116.54', '18.202.122.131', '52.31.15.68', // Hubtel IPs
        ];

        $requestIp = $request->ip();

        if (!in_array($requestIp, $allowedIps)) {
            Log::critical(json_encode([
                'message' => "🚫 Unauthorized IP trying to access Payment webhook: $requestIp",
                'request' => $request->all()
            ]));
            abort(403, 'Unauthorized request.');
        }

        return $next($request);
    }
}
