<?php

class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $uri = str_replace($scriptName, '', $uri);
        }
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Eliminar coincidencia 
                
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $actionName)) {
                        call_user_func_array([$controller, $actionName], $matches);
                        return;
                    }
                }
            }
        }

        // 404 - error terrible
        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function convertToRegex($path) {
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
