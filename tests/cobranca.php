<?php

require "./../vendor/autoload.php";

use GetNet\GetNet;
use GetNet\Parts\Auth;
use GetNet\Parts\Client;
use GetNet\Parts\ClientCard;
use GetNet\Parts\ClientAddress;
use GetNet\Exception\SDKException;

try {

    require 'config.php';

    // Iniciando objeto GetNet com os dados de autenticação
    $getnet = new GetNet($clientId, $clientSecret, $sellerId);
    $getnet->setEnv(GetNet::ENV_SANDBOX);

    // Informando o endereço do cliente
    $clientAddress = new ClientAddress($addressData);

    // Informando os dados do cartão do cliente
    $clientCard = new ClientCard($cardData);

    // Dados gerais do cliente
    $client = new Client($clientData);

    // Efetuando OAuth2 e armazenando o access_token
    $getnet
        ->makeOAuth()
        ->setPurchaser($client, $clientAddress, $clientCard);

    echo '<pre>';
    var_dump($getnet);
    die;
} catch (SDKException $e) {
    var_dump($e->getMessage());
    die;
}
