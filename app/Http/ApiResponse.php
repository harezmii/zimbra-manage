<?php

namespace App\Http;

class ApiResponse
{
    protected $statusCode;
    protected $message;
    protected $data;

    public function __construct($statusCode = 200, $message = '', $data = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data; // data null olabilir
    }

    public function toArray()
    {
        return [
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
