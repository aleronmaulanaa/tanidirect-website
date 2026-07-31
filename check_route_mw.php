<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$router = $app->make('router');
$request = Illuminate\Http\Request::create('/chatbot/message', 'POST');
$route = $router->getRoutes()->match($request);
echo "Route: " . $route->uri() . "\n";
echo "Middleware:\n";
print_r($route->gatherMiddleware());
