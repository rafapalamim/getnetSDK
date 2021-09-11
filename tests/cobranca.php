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

    $getnet = new GetNet($clientId, $clientSecret, $sellerId);
    $getnet->setEnv(GetNet::ENV_SANDBOX);

    $auth = new Auth();
    $auth->makeAuth($getnet);

    $clientAddress = new ClientAddress($addressData);
    $clientCard = new ClientCard($cardData);
    $client = new Client($clientData, $clientAddress, $clientCard, $recurrence);

    echo '<pre>';
    var_dump($client); die;

    var_dump($auth->getAuthorization());
} catch (SDKException $e) {
    var_dump($e->getMessage());
    die;
}
