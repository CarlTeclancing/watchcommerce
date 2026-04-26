<?php

declare(strict_types=1);

use App\Core\Request;

require __DIR__ . '/../bootstrap/app.php';

$request = Request::capture();
$response = $app->handle($request);
$response->send();
