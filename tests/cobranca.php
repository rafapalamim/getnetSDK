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

    $attempsPath = dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'attemps';
    $sessionUser = 'teste';
    $maxAttemps = 50;
    $timeAttemps = 60;

    if (!saveAttemp($sessionUser, $attempsPath, $maxAttemps, $timeAttemps)) {
        throw new SDKException("Você alcançou o máximo de tentativas. Aguarde " . ($timeAttemps / 60) . ' minuto(s) antes de tentar novamente.');
    }

    // Iniciando objeto GetNet com os dados de autenticação
    $getnet = new GetNet($clientId, $clientSecret, $sellerId);
    $getnet->debugMode($pathToLog);
    $getnet->setEnv(GetNet::ENV_SANDBOX);

    // Informando o endereço do cliente
    $clientAddress = new ClientAddress($addressData);

    // Informando os dados do cartão do cliente    
    $methodPayment = new ClientCard([], $idCard, $securityCodeSaved, $typeCardSaved);
    // $methodPayment = new ClientCard($cardData);

    // Dados gerais do cliente
    $client = new Client($clientData);

    // Gerando informações para efetuar a transação
    $getnet
        ->makeOAuth()
        ->setPurchaser($client, $clientAddress, $methodPayment)
        ->makeCardToken()
        ->validateClientCard();

    // Salvar dados do cartão para cobranças futuras
    if ($saveCard && $getnet->getClientMethodPayment()->cardIsVerified()) {
        $getnet->saveCard();
    }

    $trans = new Transaction($getnet, 'trackCode');
    $res = $trans
        ->setAmount(50.25)
        ->setCurrency(Transaction::CURRENCY_BR)
        ->setOrder('1', 0, Transaction::PRODUCT_TYPE_SERVICE)
        ->setShipping(0)
        ->setPaymentAttributes($paymentAttributesCredit)
        // ->setPaymentAttributes($paymentAttributesDebit)
        ->runWithAntiFraud('123');

    // Inserir log do seu sistema
    $json = json_encode($res);

    if(!$res['status']){
        // Tratado pelo seu sistema
    }

    // Completar lógica da compra para o seu sistema

    removeAttemp($sessionUser, $attempsPath);


    echo '<pre>';
    var_dump($res);

    
} catch (SDKException $e) {
    // Inserir log do seu sistema
    var_dump($e->getMessage());
    die;
}
