<?php

declare(strict_types=1);

session_start();


try {
    
   global $app;
   $app = require_once __DIR__ . '/../core/bootstrap.php';
   $app->run();
} catch (\Throwable $exception) {
   echo '<pre>';
   print_r($exception);
   echo '</pre>';
}