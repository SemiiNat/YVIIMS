<?php

namespace App\Http;

class Response
{
    private array $headers = [];
    private $body;
    private int $statusCode;

    public function __construct($body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }

    public function sendJson($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function json($data): void
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->body = json_encode($data);
    }

    public function setBody($body): void
    {
        $this->body = $body;
    }
}
