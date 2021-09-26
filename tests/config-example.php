<?php

use GetNet\Parts\ClientCard;
use GetNet\Parts\Client;
use GetNet\Helpers\IP;

$clientId = 'CLIENT_ID';
$clientSecret = 'CLIENT_SECRET';
$sellerId = 'SELLER_ID';

$addressData = [
    'Rua das Flores', // Rua
    '33', // Número
    'Complemento',
    'Paulista', // Bairro
    'São Paulo', // Cidade
    'SP', // UF
    'Brasil', // País
    '04569000' // CEP
];

$cardData = [
    '5155901222280001', // Número do cartão
    \GetNet\Parts\ClientCard::MASTERCARD, // Bandeira
    'José Paulo', // Nome presente no cartão
    '12', // Mẽs (vencimento)
    '23', // Ano (vencimento)
    '123', // CVV
    ClientCard::CREDIT // CREDIT ou DEBIT
];

$clientData = [
    '1', // ID do cliente (sua plataforma)
    'José', // Nome
    'Paulo', // Sobrenome
    \GetNet\Parts\Client::CPF, // Tipo do documento (CPF,CNPJ))
    '22111586099', // CPF ou CNPJ
    '1995-12-31', // Nascimento
    '551133334444', // Telefone fixo
    '5511933334444', // Celular
    'josepaulo@email.com', // E-mail
    'Observação', // Observação
    IP::getClientIP() // IP do cliente
];

$paymentAttributesCredit = [
    'delayed' => false,
    'pre_authorization' => false,
    'save_card_data' => false,
    'transaction_type' => \GetNet\Parts\Transaction::TRANSACTION_TYPE_FULL,
    'number_installments' => 1, 
    'soft_descriptor' => "TesteC",
    'dynamic_mcc' => null
];

$paymentAttributesDebit = [
    'cardholder_mobile' => '5511912345678',
    'authenticated' => true,
    'soft_descriptor' => "TesteD",
    'dynamic_mcc' => null
];

// Opção caso queira salvar o cartão na getnet
$saveCard = true;

// Caso o cartão estiver salvo na getnet (dados que serão fornecidos pelo seu banco de dados)
$idCard = null;
$securityCodeSaved = 123;
$typeCardSaved = ClientCard::CREDIT;
