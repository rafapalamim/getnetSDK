<?php

namespace GetNet;

use GetNet\Parts\Client;
use GetNet\Parts\ClientAddress;
use GetNet\Parts\ClientCard;
use GetNet\Parts\Auth;
use GetNet\Helpers\Request;
use GetNet\Exception\SDKException;
use GetNet\Interfaces\MethodPaymentInterface;

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

    /** @var Auth */
    private $auth;
    private $request;

    private $purchaser;

    function __construct(string $clientId, string $clientSecret, string $sellerId)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->sellerId = $sellerId;
    }

    #########################################
    ############## PROCCESSORS ##############
    #########################################

    /**
     * Set a environment and default URL API GetNet
     *
     * @param string $env
     * @return GetNet
     */
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

        $this->request = new Request($this->getUrlApi());

        return $this;
    }

    /**
     * Make OAuth2 with GetNet
     *
     * @return GetNet
     */
    public function makeOAuth(): GetNet
    {

        $this->validateEnv();

        $this->auth = new Auth();

        $res = $this->request->makeRequest('POST', '/auth/oauth/v2/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->getClientId() . ':' . $this->getClientSecret())
            ],
            'form_params' => [
                'scope' =>  Auth::SCOPE,
                'grant_type' => Auth::GRANT_TYPE
            ]
        ]);

        if (!$res) {
            throw new SDKException("Fail on auth process: " . $this->request->getError());
        }

        $res = $this->request->getRespose();

        $access_token = $res['body']->token_type . ' ' . $res['body']->access_token;
        $this->auth->setAuthorization($access_token);

        return $this;
    }

    /**
     * Set data of purchaser
     *
     * @param Client $client
     * @param ClientAddress $address
     * @param ClientCard $card
     * @return GetNet
     */
    public function setPurchaser(Client $client, ClientAddress $address, MethodPaymentInterface $methodPayment): GetNet
    {

        $this->purchaser = new \stdClass();

        $this->purchaser->client = $client;
        $this->purchaser->address = $address;
        $this->purchaser->methodPayment = $methodPayment;

        return $this;
    }

    /**
     * Tokenizer the card number
     *
     * @return GetNet
     */
    public function makeCardToken(): GetNet
    {

        $this->validateEnv();

        $res = $this->request->makeRequest('POST', '/v1/tokens/card', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => $this->auth->getAuthorization()
            ],
            'body' => json_encode([
                'card_number' => $this->getClientMethodPayment()->numberCard,
                'customer_id' => $this->getClientData()->customerId
            ])
        ]);

        if (!$res) {
            throw new SDKException("Fail on tokenizer the card: " . $this->request->getError());
        }

        $res = $this->request->getRespose();

        $this->getClientMethodPayment()->setTokenCard($res['body']->number_token);

        return $this;
    }

    /**
     * Validate the client card
     *
     * @return GetNet
     */
    public function validateClientCard(): GetNet
    {

        $this->validateEnv();

        $res = $this->request->makeRequest('POST', '/v1/cards/verification', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => $this->auth->getAuthorization()
            ],
            'body' => json_encode([
                'number_token' => $this->getClientMethodPayment()->tokenCard,
                'brand' => $this->getClientMethodPayment()->brand,
                'cardholder_name' => $this->getClientMethodPayment()->cardHolderName,
                'expiration_month' => $this->getClientMethodPayment()->expirationMonth,
                'expiration_year' => $this->getClientMethodPayment()->expirationYear,
                'security_code' => $this->getClientMethodPayment()->securityCode
            ])
        ]);

        if (!$res) {
            throw new SDKException("Fail on validate the card: " . $this->request->getError());
        }

        $res = $this->request->getRespose();

        $this->getClientMethodPayment()->saveCardVerification($res['body']);

        return $this;
    }


    #########################################
    ########### HELPERS VALIDATOR ###########
    #########################################

    /**
     * Helper to validate env
     *
     * @return void
     */
    private function validateEnv(): void
    {
        if (!$this->getEnv()) {
            throw new SDKException("Set a environment before make auth");
        }
    }

    #########################################
    ################ GETTERS ################
    #########################################

    /**
     * Get GETNET ClientID
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Get GETNET ClientSecret
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * Get GETNET SellerId
     *
     * @return string
     */
    public function getSellerId(): string
    {
        return $this->sellerId;
    }

    /**
     * Get environment
     *
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * Get GETNET BASE URI API
     *
     * @return string
     */
    public function getUrlApi(): string
    {
        return $this->urlApi;
    }

    /**
     * Get purchaser card on object
     *
     * @return ClientCard
     */
    public function getClientMethodPayment()
    {
        return $this->purchaser->methodPayment;
    }

    /**
     * Get purchaser address on object
     *
     * @return ClientAddress
     */
    public function getClientAddress(): ClientAddress
    {
        return $this->purchaser->address;
    }

    /**
     * Get purchaser data on object
     *
     * @return Client
     */
    public function getClientData(): Client
    {
        return $this->purchaser->client;
    }
}
