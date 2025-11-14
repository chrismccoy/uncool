<?php

declare(strict_types=1);

use App\Api\CoolifyClient;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($requestUri) {
    case '/':
	require __DIR__ . '/../views/layout.php';
        break;

    case '/api/resources':
        try {
            $client = new CoolifyClient(
                $_ENV['COOLIFY_API_URL'] ?? '',
                $_ENV['COOLIFY_API_TOKEN'] ?? '',
            );
            $resources = $client->getResources();

            $processedResources = array_map(function ($resource) {
                $statusParts = explode(':', $resource['status'] ?? 'unknown');
                $baseStatus = $statusParts[0];
                $status = strtolower($baseStatus) === 'exited'
                    ? 'Stopped'
                    : ucfirst($baseStatus);

                return [
                    'name' => basename($resource['name']),
                    'url' => $resource['fqdn'] ?? '#',
                    'status' => $status,
                ];
            }, $resources);

            // Sort resources alphabetically by name, case-insensitively.
            usort($processedResources, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });

            http_response_code(200);
            header('Content-Type: application/json');
    	    echo json_encode($processedResources);
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
    	    echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        $publicPath = __DIR__ . $requestUri;
        if (file_exists($publicPath) && is_file($publicPath)) {
            return false;
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        break;
}
