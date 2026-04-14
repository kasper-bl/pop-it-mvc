<?php
const DIR_CONFIG = '/../config';

$projectRoot = dirname(__DIR__);
require_once __DIR__ . '/Src/Route.php';
require_once __DIR__ . '/../vendor/autoload.php';

function getConfigs(string $path = DIR_CONFIG): array
{
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name) && $name !== $file) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

require_once __DIR__ . '/../routes/web.php';
$app = new Src\Application(new Src\Settings(getConfigs()));

function app() {
   global $app;
   return $app;
}

return $app;
