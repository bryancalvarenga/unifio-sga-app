<?php
namespace Core;

class Router {
    // Array de rotas
    private array $routes = [];

    // Adiciona uma rota
    public function get(string $path, callable|array $handler) { $this->map('GET', $path, $handler); }
    public function post(string $path, callable|array $handler) { $this->map('POST', $path, $handler); }

    // Dispara a rota correspondente
    private function map(string $method, string $path, $handler) { $this->routes[$method] [$this->normalize($path)] = $handler; }

    // Dispara a rota correspondente
    public function dispatch(string $uri, string $method) {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = $this->normalize($path);
        $handler = $this->routes[$method][$path] ?? null;

        // Se não encontrar a rota, retorna 404
        if (!$handler) { http_response_code(404); echo '404'; return; }

        // Se encontrar, executa o handler
        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();
            return $controller->$action();
        }
        // Se for uma função anônima, executa diretamente
        return $handler();
    }

    // Normaliza o caminho da rota
    private function normalize(string $p): string {
        $p = rtrim($p, '/'); return $p === '' ? '/' : $p;
    }
}

