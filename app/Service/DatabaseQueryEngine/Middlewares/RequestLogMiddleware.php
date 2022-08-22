<?php

namespace App\Service\DatabaseQueryEngine\Middlewares;

use App\Service\DatabaseQueryEngine\Repositories\Contracts\RequestLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\RequestLogRepository;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Helpers\Response;

/**
 * Class CustomQueries
 * @package App\Service\DatabaseQueryEngine\Models
 */
class RequestLogMiddleware
{
    private $response;
    private $requestLogRepository;

    /**
     * RequestLogMiddleware constructor.
     * @param Response $response
     * @param RequestLogRepositoryInterface $requestLogRepository
     */
    public function __construct(Response $response, RequestLogRepositoryInterface $requestLogRepository)
    {
        $this->response = $response;
        $this->requestLogRepository = $requestLogRepository;
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

        $userId = $user->id ?? null;

        $log = $this->requestLogRepository->create([
            'headers' => json_encode($request->headers->all()),
            'input' => json_encode($request->all()),
            'ip' => $request->ip(),
            'route' => $request->path(),
            'user_id' => $userId,
        ]);

        $request->request->add(['request_log_id' => $log->id]);

        return $next($request);
    }
}
