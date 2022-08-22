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
class DatabaseEngineAuthMiddleware
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
        $accessToken = $request->get('dqeAccessToken');
        if ($accessToken && $accessToken == env('DQE_TOKEN_ACCESS')) {
            return $next($request);
        }
        return $this->response
            ->setErrors('Authorization failed')
            ->setStatus(SymfonyResponse::HTTP_UNAUTHORIZED)
            ->get();
    }
}
