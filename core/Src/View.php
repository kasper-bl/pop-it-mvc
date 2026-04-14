<?php

namespace Src;

use Exception;

class View
{
    private string $view = '';
    private array $data = [];
    private string $root = '';
    private string $layout = '/layouts/main.php';

    public function __construct(string $view = '', array $data = [])
    {
        $this->root = $this->getRoot();
        $this->view = $view;
        $this->data = $data;
    }

    private function getRoot(): string
    {
        $projectRoot = dirname(__DIR__, 2);
        
        global $app;
        $viewsPath = $app->settings->getViewsPath();
        
        return rtrim($projectRoot, '/') . '/' . ltrim($viewsPath, '/');
    }

    private function getPathToMain(): string
    {
        return $this->root . $this->layout;
    }

    private function getPathToView(string $view = ''): string
    {
        $viewPath = str_replace('.', '/', $view);
        return rtrim($this->root, '/') . '/' . $viewPath . '.php';
    }

    public function render(string $view = '', array $data = []): string
    {
        $viewPath = $this->getPathToView($view !== '' ? $view : $this->view);
        $layoutPath = $this->getPathToMain();
        $renderData = $data !== [] ? $data : $this->data;

        if (!file_exists($layoutPath)) {
            throw new Exception('Layout not found: ' . $layoutPath);
        }
        
        if (!file_exists($viewPath)) {
            throw new Exception('View not found: ' . $viewPath);
        }

        extract($renderData, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        ob_start();
        require $layoutPath;
        return ob_get_clean();
    }

    public function __toString(): string
    {
        try {
            return $this->render($this->view, $this->data);
        } catch (Exception $e) {
            return '<!-- View render error: ' . htmlspecialchars($e->getMessage()) . ' -->';
        }
    }
}