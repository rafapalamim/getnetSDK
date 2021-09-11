<?php

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
    '123' // CVV
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
    'Observação' // Observação
];

$recurrence = false;