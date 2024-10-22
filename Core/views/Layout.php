<?php

namespace Core\views;

class Layout
{
    protected $sections = [];
    protected $layoutContent;
    protected $viewContent;
    protected array $data = [];

    /**
     * @throws \Exception
     */
    private function getViewsPath($path)
    {
        if (!str_starts_with($path, views_path())) {
            $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            $path = str_ends_with($path, '.php') ? $path : $path . '.php';
            $path = views_path($path);
        }

        if (!file_exists($path)) {
            throw new \Exception(sprintf('View file [%s] not found.', $path));
        }

        return $path;
    }

    public function setLayout($layoutPath) {
        ob_start();
        include $this->getViewsPath($layoutPath);
        $this->layoutContent = ob_get_clean();
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function setView($viewPath) {
        ob_start();
        extract($this->data);
        include $viewPath;
        $this->viewContent = ob_get_clean();
        return $this;
    }

    public function extend($layoutPath) {
        $this->setLayout($layoutPath);
    }

    public function section($name) {
        ob_start();
    }

    public function endSection($name) {
        $this->sections[$name] = ob_get_clean();
    }

    public function yield($name) {
        return $this->sections[$name] ?? '';
    }

    public function include($viewPath) {
        ob_start();
        include $this->getViewsPath($viewPath);
        return ob_get_clean();
    }

    public function render()
    {
        $content = $this->layoutContent;

        foreach ($this->sections as $name => $value) {
            $content = str_replace(["@yield('$name')", "@yield(\"$name\")"], $value, $content);
        }

        return $content;
    }
}
