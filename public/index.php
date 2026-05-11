<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;

require __DIR__ . '/../bootstrap/app.php';

set_exception_handler(function (Throwable $e) use ($container): void {
	$debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL);

	try {
		$container->get(\App\Shared\Logger::class)->error($e->getMessage(), [
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'trace' => $e->getTraceAsString(),
		]);
	} catch (Throwable) {}

	http_response_code(500);
	if ($debug) {
		echo '<pre style="white-space:pre-wrap;font-family:monospace;padding:2rem">';
		echo '<strong>' . htmlspecialchars(get_class($e), ENT_QUOTES) . '</strong>: ';
		echo htmlspecialchars($e->getMessage(), ENT_QUOTES) . "\n\n";
		echo htmlspecialchars($e->getFile() . ':' . $e->getLine(), ENT_QUOTES) . "\n\n";
		echo htmlspecialchars($e->getTraceAsString(), ENT_QUOTES);
		echo '</pre>';
	} else {
		echo '<!doctype html><html><head><title>500</title></head><body style="font-family:sans-serif;text-align:center;padding:4rem">';
		echo '<h1>500 &mdash; Internal Server Error</h1>';
		echo '<p>An internal server error has occurred.</p>';
		echo '</body></html>';
	}
});

$request = Request::capture();
$response = $app->handle($request);
$response->send();
