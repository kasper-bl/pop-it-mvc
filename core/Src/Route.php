<?php

namespace Src;

use Error;

class Route
{
    private static array $routes = [];
    private static string $prefix = '';

    public static function setPrefix($value)
    {
        self::$prefix = $value;
    }

    public static function add(string $route, array $action): void
    {
        if (!array_key_exists($route, self::$routes)) {
            self::$routes[$route] = $action;
        }
    }

    public function start(): void
    {
        $path = $_GET['url'] ?? '';
        $path = trim($path, '/');

        if (!array_key_exists($path, self::$routes)) {
            throw new Error('Этот путь не существует');
        }

        $class = self::$routes[$path][0];
        $action = self::$routes[$path][1];

        if (!class_exists($class)) {
            throw new Error('This class does not exist');
        }

        if (!method_exists($class, $action)) {
            throw new Error('This method does not exist');
        }

        $result = call_user_func([new $class, $action], new Request());
        
        if ($result instanceof \Src\View) {
            echo $result;
        } 
        elseif (is_string($result)) {
            echo $result;
        }
    }
    public function redirect(string $url): void
    {
        header('Location: ' . $this->getUrl($url));
    }

    public function getUrl(string $url): string
    {
        return self::$prefix . $url;
    }

    public function __construct(string $prefix = '')
    {
        self::setPrefix($prefix);
    }
}