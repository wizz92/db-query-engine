<?php

namespace App\Service\DatabaseQueryEngine\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Helpers\Response;

/**
 * Class CustomQueries
 * @package App\Service\DatabaseQueryEngine\Models
 */
class AdminAuthMiddleware
{
    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->hasRole('Admin')) {
            return $next($request);
        }

        return $this
            ->response
            ->setErrors("Access denied. Action is not allowed")
            ->setStatus(SymfonyResponse::HTTP_FORBIDDEN)
            ->get();
    }
}
