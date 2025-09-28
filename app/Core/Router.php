<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if(isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];

            if(is_callable($callback)) {
                $callback();
            } elseif(is_string($callback)) {
                // مثال: "AuthController@login"
                [$controllerName, $methodName] = explode('@', $callback);
                $controllerPath = __DIR__ . '/../Controllers/' . $controllerName . '.php';
                if(file_exists($controllerPath)) {
                    require_once $controllerPath;
                    $controller = new $controllerName();
                    $controller->$methodName();
                } else {
                    echo "Controller not found: $controllerName";
                }
            }
        } else {
            http_response_code(404);
            echo "صفحة غير موجودة";
        }
    }
}
