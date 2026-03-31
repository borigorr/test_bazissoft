<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../routing.php';

use App\Dto\HttpResponseDto;
use App\Routing\Router;
use App\Routing\MethodsEnum;
use App\Exceptions\NotFoundException;
use \App\Exceptions\ValidateException;

/**
 * @var $container DI\Container
 */

$method  = $_SERVER['REQUEST_METHOD'];
$uri     = $_SERVER['REQUEST_URI'];
$path = explode("?", $uri)[0];
header('Content-Type: application/json; charset=utf-8');
try {
    $action = Router::math(MethodsEnum::from($method), $path);
    $controller = $container->get($action->controller);
    $response = $controller->{$action->action}();
    if ($response instanceof HttpResponseDto) {
        http_response_code($response->statusCode);
        echo json_encode($response->message);
    } else {
        throw new \Exception("Invalid response");
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Not Found',
    ]);
} catch (ValidateException $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage(),
    ]);
} catch (\Exception $e) {
    throw $e;
    echo json_encode([
        'error' => 'Internal Server Error',
    ]);
}
