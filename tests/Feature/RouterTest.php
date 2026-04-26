<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Core\Response;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testDispatchesStaticRoute(): void
    {
        $container = new Container();
        $router = new Router($container);

        $handler = new class($container) {
            public function __construct(private Container $container)
            {
            }

            public function ping(Request $request, array $params): Response
            {
                return new Response('pong');
            }
        };

        $className = get_class($handler);
        $router->get('/ping', [$className, 'ping']);

        $response = $router->dispatch(new Request('GET', '/ping', [], [], [], []));

        ob_start();
        $response->send();
        $body = (string)ob_get_clean();
        $this->assertSame('pong', $body);
    }
}
