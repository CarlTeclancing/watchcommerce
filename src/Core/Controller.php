<?php

declare(strict_types=1);

namespace App\Core;

use App\Infrastructure\Security\Csrf;
use App\Shared\AppSettings;

abstract class Controller
{
    public function __construct(protected Container $container)
    {
    }

    protected function view(string $template, array $data = []): Response
    {
        /** @var AppSettings $settings */
        $settings = $this->container->get(AppSettings::class);
        $data['app_settings'] = $settings->all();
        return new Response(View::render($template, $data));
    }

    protected function json(array $data, int $status = 200): Response
    {
        return Response::json($data, $status);
    }

    protected function redirect(string $url): Response
    {
        return Response::redirect($url);
    }

    protected function verifyCsrf(Request $request): void
    {
        /** @var Csrf $csrf */
        $csrf = $this->container->get(Csrf::class);
        if (!$csrf->verify((string)$request->input('_token', ''))) {
            throw new \RuntimeException('Invalid CSRF token');
        }
    }
}
