<?php

namespace GetNet\Parts;

use GetNet\Exception\SDKException;
use GetNet\Helpers\Request;

class Auth
{

    private const SCOPE = 'oob';
    private const GRANT_TYPE = 'client_credentials';

    private $authorization;

    function __construct()
    {
    }

    public function makeAuth(\GetNet\GetNet $getnet)
    {

        if (!$getnet->getEnv()) {
            throw new SDKException("Set a environment before make auth");
        }

        $request = new Request($getnet->getUrlApi());

        $res = $request->makeRequest('POST', '/auth/oauth/v2/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($getnet->getClientId() . ':' . $getnet->getClientSecret())
            ],
            'form_params' => [
                'scope' =>  self::SCOPE,
                'grant_type' => self::GRANT_TYPE
            ]
        ]);

        if (!$res) {
            throw new SDKException("Fail on auth process: " . $request->getError());
        }

        $res = $request->getRespose();

        $this->authorization = $res['body']->token_type . ' ' . $res['body']->access_token;
    }

    public function getAuthorization()
    {
        return $this->authorization;
    }
}
