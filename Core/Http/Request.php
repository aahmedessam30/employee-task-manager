<?php

namespace Core\Http;

use Core\Session\Session;
use Random\RandomException;

class Request
{
    protected $get;
    protected $post;
    protected $server;
    protected $files;
    protected $cookies;
    protected $headers;

    public function __construct()
    {
        $this->get     = $_GET;
        $this->post    = $_POST;
        $this->server  = $_SERVER;
        $this->files   = $_FILES;
        $this->cookies = $_COOKIE;
        $this->headers = $this->getHeaders();
    }

    public static function capture()
    {
        return (new static())->storeSession();
    }

    /**
     * @throws RandomException
     */
    protected function storeSession(): static
    {
        if ($this->isAjax() || $this->expectsJson()) {
            return $this;
        }

        Session::start();
        Session::set('_token', session()->csrfToken());
        Session::set('url', $this->getUri());
        Session::set('previous_url', $this->getReferer());
        Session::set('method', $this->method());
        Session::set('input', $this->all());
        Session::set('files', $this->file());
        Session::set('cookies', $this->cookie());
        Session::set('server', $this->server());
        Session::set('headers', $this->header());

        return $this;
    }

    public function get($key = null, $default = null)
    {
        return $key ? ($this->get[$key] ?? $default) : $this->get;
    }

    public function all()
    {
        return array_merge($this->get, $this->post);
    }

    public function input($key, $default = null)
    {
        $data = $this->all();
        return $data[$key] ?? $default;
    }

    public function filled($key)
    {
        return isset($this->all()[$key]) && !empty($this->all()[$key]);
    }

    public function hasFile($key)
    {
        return isset($this->files[$key]);
    }

    public function has($key)
    {
        return isset($this->all()[$key]);
    }

    public function only(...$keys)
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    public function except($keys)
    {
        return array_diff_key($this->all(), array_flip((array)$keys));
    }

    public function query($key = null, $default = null)
    {
        return $key ? ($this->get[$key] ?? $default) : $this->get;
    }

    public function post($key = null, $default = null)
    {
        return $key ? ($this->post[$key] ?? $default) : $this->post;
    }

    public function file($key = null)
    {
        return $key ? ($this->files[$key] ?? null) : $this->files;
    }

    public function cookie($key = null, $default = null)
    {
        return $key ? ($this->cookies[$key] ?? $default) : $this->cookies;
    }

    public function server($key = null, $default = null)
    {
        return $key ? ($this->server[$key] ?? $default) : $this->server;
    }

    public function header($key = null, $default = null)
    {
        if ($key) {
            $key = strtoupper(str_replace('-', '_', $key));
            return $this->headers[$key] ?? $default;
        }
        return $this->headers;
    }

    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    public function isMethod($method)
    {
        return strtoupper($method) === $this->method();
    }

    public function url()
    {
        return $this->server('REQUEST_URI');
    }

    public function fullUrl()
    {
        $scheme = $this->isSecure() ? 'https' : 'http';
        return $scheme . '://' . $this->server('HTTP_HOST') . $this->url();
    }

    public function scheme()
    {
        $schema = $this->server('REQUEST_SCHEME');

        if ($schema !== '://') {
            return $this->isSecure() ? 'https' : 'http';
        }

        return $schema;
    }

    public function baseUrl()
    {
        return $this->scheme() . '://' . $this->server('HTTP_HOST');
    }

    public function ip()
    {
        return $this->server('REMOTE_ADDR');
    }

    public function isAjax()
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }

    public function isSecure()
    {
        return $this->server('HTTPS') === 'on';
    }

    protected function getHeaders()
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'])) {
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public function getPathInfo()
    {
        $pathInfo = $this->server('REQUEST_URI');
        $baseUrl  = $this->baseUrl();
        $pathInfo = str_replace($baseUrl, '', $pathInfo);

        return '/' . ltrim($pathInfo, '/');
    }

    public function setRouteParameters($parameters)
    {
        $this->get = array_merge($this->get, $parameters);
    }

    public function getRouteParameters()
    {
        return $this->get;
    }

    public function expectsJson()
    {
        return $this->header('Accept') === 'application/json';
    }

    public function getReferer()
    {
        return $this->header('Referer');
    }

    public function getUri()
    {
        return $this->server('REQUEST_URI');
    }

    public function getBaseUrl()
    {
        return $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST');
    }

    public function session(): \Core\Session\SessionManager
    {
        return Session::init();
    }

    public function __set(string $name, $value)
    {
        $this->post[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->input($name);
    }

    public static function setTrustedHosts(array $hosts)
    {
        $_SERVER['TRUSTED_HOSTS'] = $hosts;
    }

    public function bearerToken()
    {
        $header = $this->header('Authorization');

        if ($header && str_starts_with($header, 'Bearer ')) {
            return str_after($header, 'Bearer ');
        }

        return null;
    }

    public function setMethod($method)
    {
        $this->server['REQUEST_METHOD'] = strtoupper($method);
    }

    public function merge(array $data)
    {
        $this->post = array_merge($this->post, $data);
    }

    public function remove(string $key)
    {
        unset($this->post[$key]);
    }
}
