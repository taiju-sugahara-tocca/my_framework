<?php
namespace Framework;

class Response
{
    protected string $content = '';
    protected int $status = 200;
    protected array $headers = [];

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send()
    {
        // ステータスコード送信
        http_response_code($this->status);

        // ヘッダー送信
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // 本文送信
        echo $this->content;
        exit;
    }

    /**
     * リダイレクトレスポンスを生成
     */
    public static function redirect(string $url, int $status = 302)
    {
        $response = new self('', $status, ['Location' => $url]);
        $response->send();
    }
}