<?php

declare(strict_types=1);

// Enable CORS headers

header('Access-Control-Allow-Origin: http://localhost:3001'); // Allow all origins, you can restrict to a specific origin if needed
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allow the methods you need
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers (e.g., custom headers)
//header('Access-Control-Allow-Credentials: true'); // Allow credentials (optional)

// Handle OPTIONS request for preflight (for PUT, DELETE, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // End execution for preflight request to prevent further processing
    http_response_code(204); // No content for preflight requests
    exit();
}


require __DIR__ . '/../vendor/autoload.php';

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$application->run();
