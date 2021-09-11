<?php

namespace GetNet\Parts;

class ClientCard
{

    const MASTERCARD = 'Mastercard';
    const VISA = 'Visa';
    const AMEX = 'Amex';
    const ELO = 'Elo';
    const Hipercard = 'Hipercard';

    private $numberCard;
    private $brand;
    private $cardHolderName;
    private $expirationMonth;
    private $expirationYear;
    private $securityCode;

    private $tokenCard;

    function __construct(array $data)
    {
        $this->numberCard = $data[0];
        $this->brand = $data[1];
        $this->cardHolderName = $data[2];
        $this->expirationMonth = $data[3];
        $this->expirationYear = $data[4];
        $this->securityCode = $data[5];
    }
}
