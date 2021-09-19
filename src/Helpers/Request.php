<?php

namespace GetNet\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7;

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

        $this->response = null;
        $this->error = null;

        try {

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $client = new Client(['base_uri' => $this->baseUri]);
            } else {
                $client = new Client();
            }

            $this->response = $client->request($method, $url, $options);

            return true;
        } catch (BadResponseException $e) {
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

    public function getError(bool $string = false)
    {
        if ($string) {
            return Psr7\Message::toString($this->error->getResponse());
        }

        $response = $this->error->getResponse();
        return [
            'status_code' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'body' => json_decode($response->getBody()->getContents())
        ];
    }
}
