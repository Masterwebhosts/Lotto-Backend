<?php
class Router {
  private array $routes = [];
  public function get(string $path, callable $h){ $this->routes['GET'][$path]=$h; }
  public function post(string $path, callable $h){ $this->routes['POST'][$path]=$h; }
  public function dispatch(){
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = strtok($_SERVER['REQUEST_URI'],'?');
    $base = '/lotto-backend/public';
    $path = preg_replace('#^'.preg_quote($base,'#').'#','',$uri);
    $h = $this->routes[$method][$path] ?? null;
    if (!$h) { http_response_code(404); echo '404'; return; }
    $h();
  }
}
