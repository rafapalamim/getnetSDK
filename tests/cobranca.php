<?php

require "./../vendor/autoload.php";

use GetNet\GetNet;
use GetNet\Parts\Client;
use GetNet\Parts\ClientCard;
use GetNet\Parts\ClientAddress;
use GetNet\Parts\Transaction;
use GetNet\Exception\SDKException;

try {

    require 'config.php';

    // Iniciando objeto GetNet com os dados de autenticação
    $getnet = new GetNet($clientId, $clientSecret, $sellerId);
    $getnet->setEnv(GetNet::ENV_SANDBOX);

    // Informando o endereço do cliente
    $clientAddress = new ClientAddress($addressData);

    // Informando os dados do cartão do cliente
    $methodPayment = new ClientCard($cardData, ClientCard::CREDIT);

    // Dados gerais do cliente
    $client = new Client($clientData);

    // Gerando informações para efetuar a transação
    $getnet
        ->makeOAuth()
        ->setPurchaser($client, $clientAddress, $methodPayment)
        ->makeCardToken()
        ->validateClientCard();

    $trans = new Transaction($getnet, 'trackCode');
    $trans
        ->setAmount(50.25)
        ->setCurrency(Transaction::CURRENCY_BR)
        ->setOrder('1', 0, Transaction::PRODUCT_TYPE_SERVICE)
        ->setShipping(0)
        ->setPaymentAttributes($paymentAttributesCredit)
        ->runWithAntiFraud('123');
        

    echo '<pre>';
    var_dump($trans);
    die;
} catch (SDKException $e) {
    var_dump($e->getMessage());
    die;
}
