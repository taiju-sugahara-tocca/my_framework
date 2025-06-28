<?php
namespace Framework;

class Request
{
    protected array $get;
    protected array $post;
    protected array $server;
    protected array $cookie;
    protected array $files;

    public function __construct()
    {
        $this->get    = $_GET ?? [];
        $this->post   = $_POST ?? [];
        $this->server = $_SERVER ?? [];
        $this->cookie = $_COOKIE ?? [];
        $this->files  = $_FILES ?? [];
    }

    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function allGet(): array
    {
        return $this->get;
    }

    public function allPost(): array
    {
        return $this->post;
    }

    public function server(string $key, $default = null)
    {
        return $this->server[$key] ?? $default;
    }

    public function cookie(string $key, $default = null)
    {
        return $this->cookie[$key] ?? $default;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

}