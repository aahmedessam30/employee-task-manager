<?php

namespace Core\views;

class Layout
{
    protected $sections = [];
    protected $layoutContent;
    protected $viewContent;

    public function setLayout($layoutPath) {
        ob_start();
        include $layoutPath;
        $this->layoutContent = ob_get_clean();
    }

    public function setView($viewPath) {
        ob_start();
        include $viewPath;
        $this->viewContent = ob_get_clean();
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

    public function render() {
        $content = $this->layoutContent;
        foreach ($this->sections as $name => $value) {
            $content = str_replace("@yield('$name')", $value, $content);
        }
        return $content;
    }
}
