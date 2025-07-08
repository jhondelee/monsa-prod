<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Closure;

      
class ThrottleMiddleWare extends ThrottleRequests
{
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);
        
        \Session::flash('message', __('auth.throttle', ['seconds' => $retryAfter]));
        \Session::flash('status', 'danger');
        
        $response = new Response(view('auth.login'), 429);
        
        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
