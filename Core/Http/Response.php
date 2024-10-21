<?php

namespace Core\Http;

use Core\Session\Session;

class Response
{
    protected mixed $content;
    protected int $statusCode;
    protected array $headers = [];

    public function __construct($content = '', $statusCode = 200, array $headers = [])
    {
        $this->content    = $content;
        $this->statusCode = $statusCode;
        $this->headers    = $headers;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getHeader($key)
    {
        return $this->headers[$key] ?? null;
    }

    public function removeHeader($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                unset($this->headers[$k]);
            }
            return $this;
        }

        unset($this->headers[$key]);
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;
    }

    public function json($data, $statusCode = 200, array $headers = [])
    {
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'application/json';
        }

        $this->headers    = array_merge($this->headers, $headers);
        $this->content    = json_encode($data);
        $this->statusCode = $statusCode;

        return $this;
    }

    public function plain($content, $statusCode = 200, array $headers = [])
    {
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/plain';
        }

        $this->headers    = array_merge($this->headers, $headers);
        $this->content    = $content;
        $this->statusCode = $statusCode;

        return $this;
    }

    public function html($content, $statusCode = 200, array $headers = [])
    {
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html';
        }

        $this->headers    = array_merge($this->headers, $headers);
        $this->content    = $content;
        $this->statusCode = $statusCode;

        return $this;
    }

    public function redirect($url, $statusCode = 302, array $headers = [])
    {
        $this->headers['Location'] = $url;
        $this->headers['Status']   = "$statusCode Found";
        $this->headers             = array_merge($this->headers, $headers);
        $this->content             = '';
        $this->statusCode          = $statusCode;

        return $this;
    }

    public function back()
    {
        $url                       = request()->getReferer() ?? session()->get('previous_url') ?? '/';
        $this->headers['Location'] = str_replace(url(), '', $url);
        $this->headers['Status']   = '302 Found';
        $this->content             = '';
        $this->statusCode          = 302;

        return $this;
    }

    public function with($key, $value)
    {
        Session::flash($key, $value);
        return $this;
    }

    public function withErrors($errors)
    {
        Session::flash('errors', $errors);
        return $this;
    }

    public function withInput()
    {
        Session::flash('old', request()->all());
        return $this;
    }
}
