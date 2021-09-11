<?php

namespace GetNet\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Request
{

    private $error;
    private $baseUri;
    private $response;

    function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function makeRequest(string $method, string $url, array $options): bool
    {

        try {

            $client = new Client(['base_uri' => $this->baseUri]);
            $this->response = $client->request($method, $url, $options);

            return true;
        } catch (RequestException $e) {
            $this->error = $e;
            return false;
        }
    }

    public function getRespose(): array
    {
        return [
            'status_code' => $this->response->getStatusCode(),
            'reason' => $this->response->getReasonPhrase(),
            'body' => json_decode($this->response->getBody()->getContents())
        ];
    }

    public function getError(): string
    {
        return Psr7\Message::toString($this->error->getResponse());
    }
}
