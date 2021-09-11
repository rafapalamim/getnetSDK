<?php

namespace GetNet\Parts;

/**
 * Purchaser data
 */
class Client
{

    /** @var string */
    public const CPF = 'CPF';

    /** @var string */
    public const CNPJ = 'CNPJ';

    /** @var string */
    private $customerId;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $documentType;

    /** @var string */
    private $documentNumber;

    /** @var string */
    private $birthDate;

    /** @var string */
    private $phoneNumber;

    /** @var string */
    private $celphoneNumber;

    /** @var string */
    private $email;

    /** @var string */
    private $observation;

    function __construct(array $data)
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
    }
}
