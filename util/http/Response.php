<?php

namespace Bookstore\Util\Http;


class Response
{

    protected $headers;

    protected $content;

    protected $statusCode = 200; // Default to 200 OK

    public function setContentType($contentType)
    {
        $this->setHeader("Content-Type", $contentType);
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function setResponseBody($content)
    {
        $this->content = $content;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        $this->sendHeaders();
        $this->sendContent();
        exit;
    }

    public function redirect($to)
    {
        $this->setHeader("Location", $to);
        $this->send();
    }

    protected function sendHeaders()
    {
        foreach ($this->headers as $key => $val) {
            header($key . ': ' . $val);
        }
    }

    protected function sendContent()
    {
        echo $this->content;
    }

}