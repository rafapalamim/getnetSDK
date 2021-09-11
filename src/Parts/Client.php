<?php

namespace GetNet\Parts;

class Client
{

    public const CPF = 'CPF';
    public const CNPJ = 'CNPJ';

    private $customerId;
    private $firstName;
    private $lastName;
    private $documentType;
    private $documentNumber;
    private $birthDate;
    private $phoneNumber;
    private $celphoneNumber;
    private $email;
    private $observation;
    private $address;
    private $card;

    private $isRecurrence;

    function __construct(array $data, ClientAddress $address, ClientCard $card, bool $recurrence)
    {
        $this->customerId = $data[0];
        $this->firstName = $data[1];
        $this->lastName = $data[2];
        $this->documentType = $data[3];
        $this->documentNumber = $data[4];
        $this->birthDate = $data[5];
        $this->phoneNumber = $data[6];
        $this->celphoneNumber = $data[7];
        $this->email = $data[8];
        $this->observation = $data[9];
        $this->address = $address;
        $this->card = $card;

        $this->isRecurrence = $recurrence;
    }

}