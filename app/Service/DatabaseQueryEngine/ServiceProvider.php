<?php
namespace App\Service\DatabaseQueryEngine;

use App\Base\ContextualDependency\Contracts\ContainerInterface;
use App\Service\DatabaseQueryEngine\Contracts\DatabaseQueryEngineInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\BaseInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\FactoryInterface as InvokerFactoryInterface;
use App\Service\DatabaseQueryEngine\Invoker\Factory as InvokerFactory;
use App\Service\DatabaseQueryEngine\Invoker\Invoker;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\RequestLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\CustomQueryLogRepository;
use App\Service\DatabaseQueryEngine\Repositories\CustomQueryRepository;
use App\Service\DatabaseQueryEngine\Log\Contracts\QueryLoggerInterface;
use App\Service\DatabaseQueryEngine\Repositories\RequestLogRepository;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesEngineInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesResolverInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\SecurityProxyInterface;
use App\Service\DatabaseQueryEngine\Log\QueryLogger;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptsContainerInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations\MongoDbQueryAttemptsContainer;
use App\Service\DatabaseQueryEngine\Security\Rules\RulesEngine;
use App\Service\DatabaseQueryEngine\Security\Rules\RulesResolver;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations\MysqlDbQueryAttemptsContainer;
use App\Service\DatabaseQueryEngine\Security\SecurityProxy;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\ConnectionReadOnly as SqlConnectionReadOnly;
use App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ConnectionReadOnly as MongoDbConnectionReadOnly;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 * @package App\Service\FacebookBot
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @var string $controllersNamespace
     */
    private $controllersNamespace = '\App\Service\DatabaseQueryEngine\Controllers';

    /**
     * @param Router $router
     */
    public function boot(Router $router)
    {
        $router->group(['namespace' => $this->controllersNamespace], function () {
            require __DIR__.'/Routes/routes.php';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DatabaseQueryEngineInterface::class, DatabaseQueryEngine::class);
        $this->app->bind(QueryLoggerInterface::class, QueryLogger::class);
        $this->app->bind(SecurityProxyInterface::class, SecurityProxy::class);
        $this->app->bind(InvokerFactoryInterface::class, InvokerFactory::class);
        $this->app->bind(CustomQueryRepositoryInterface::class, CustomQueryRepository::class);
        $this->app->bind(CustomQueryLogRepositoryInterface::class, CustomQueryLogRepository::class);
        $this->app->bind(BaseInvokerInterface::class, Invoker::class);
        $this->app->bind(RulesEngineInterface::class, RulesEngine::class);
        $this->app->bind(RulesResolverInterface::class, RulesResolver::class);
        $this->app->bind(RequestLogRepositoryInterface::class, RequestLogRepository::class);

        $this
            ->app
            ->when(SqlConnectionReadOnly::class)
            ->needs(DbQueryAttemptsContainerInterface::class)
            ->give(MysqlDbQueryAttemptsContainer::class);
        $this
            ->app
            ->when(MongoDbConnectionReadOnly::class)
            ->needs(DbQueryAttemptsContainerInterface::class)
            ->give(MongoDbQueryAttemptsContainer::class);

        $readOnlyDatabases = require __DIR__.'/Configs/read-only-db.php';

        config()->set([
            'database.connections' => array_merge(
                config()->get('database.connections'),
                $readOnlyDatabases
            )
        ]);
    }
}
