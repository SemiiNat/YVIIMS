<?php

namespace App\Http;

class Response
{
    private array $headers = [];
    private $body;
    private int $statusCode;

    /**
     * Response constructor.
     *
     * @param string $body The response body.
     * @param int $statusCode The HTTP status code.
     * @param array $headers The response headers.
     */
    public function __construct($body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Set a header for the response.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @return void
     */
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Send the response.
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }

    /**
     * Send a JSON response.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @param int $status The HTTP status code.
     * @return void
     */
    public function sendJson($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Set the response body as JSON.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @return void
     */
    public function json($data): void
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->body = json_encode($data);
    }

    /**
     * Set the response body.
     *
     * @param mixed $body The response body.
     * @return void
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }
}
