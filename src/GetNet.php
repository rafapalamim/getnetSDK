<?php

namespace GetNet;

use GetNet\Exception\SDKException;

class GetNet
{

    const ENV_SANDBOX = 'sandbox';
    const ENV_HOMOLOGATION = 'homologation';
    const ENV_PRODUCTION = 'production';

    private const URL_SANDBOX = 'https://api-sandbox.getnet.com.br';
    private const URL_HOMOLOGATION = 'https://api-sandbox.getnet.com.br';
    private const URL_PRODUCTION = 'https://api-sandbox.getnet.com.br';

    private $clientId;
    private $clientSecret;
    private $sellerId;

    private $urlApi;
    private $env;

    function __construct(string $clientId, string $clientSecret, string $sellerId)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->sellerId = $sellerId;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getSellerId()
    {
        return $this->sellerId;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getUrlApi()
    {
        return $this->urlApi;
    }

    public function setEnv(string $env): GetNet
    {

        if (!in_array($env, [self::ENV_SANDBOX, self::ENV_HOMOLOGATION, self::ENV_PRODUCTION])) {
            throw new SDKException("Environment not recognized");
        }

        if ($env === self::ENV_SANDBOX) {
            $this->env = self::ENV_SANDBOX;
            $this->urlApi = self::URL_SANDBOX;
        }

        if ($env === self::ENV_HOMOLOGATION) {
            $this->env = self::ENV_HOMOLOGATION;
            $this->urlApi = self::URL_HOMOLOGATION;
        }

        if ($env === self::ENV_PRODUCTION) {
            $this->env = self::ENV_PRODUCTION;
            $this->urlApi = self::URL_PRODUCTION;
        }

        return $this;
    }
}
