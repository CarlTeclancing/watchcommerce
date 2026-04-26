<?php

declare(strict_types=1);

use App\Core\Container;
use App\Core\Router;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Security\Auth;
use App\Infrastructure\Security\Csrf;
use App\Shared\AppSettings;
use App\Shared\Logger;

// Load .env file
if (file_exists(__DIR__ . '/../.env')) {
    foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

// Load autoloader (no Composer required)
require __DIR__ . '/../src/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$container = new Container();
$appConfig = require __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/database.php';

$container->singleton('config', static fn () => $appConfig);
$container->singleton('logger', static fn () => new Logger(__DIR__ . '/../storage/logs/app.log'));
$container->singleton(Connection::class, static fn () => new Connection($dbConfig));
$container->singleton(Csrf::class, static fn () => new Csrf());
$container->singleton(Auth::class, static fn (Container $c) => new Auth($c->get(Connection::class), $c->get('logger')));
$container->singleton(AppSettings::class, static fn (Container $c) => new AppSettings($c->get(Connection::class)));
$container->singleton(Router::class, static fn (Container $c) => new Router($c));

$router = $container->get(Router::class);
$routes = require __DIR__ . '/../config/routes.php';
$routes($router);

$app = new class($router) {
    public function __construct(private Router $router)
    {
    }

    public function handle(App\Core\Request $request): App\Core\Response
    {
        return $this->router->dispatch($request);
    }
};
