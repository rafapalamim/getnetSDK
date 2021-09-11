<?php

namespace GetNet\Parts;

/**
 * Purchaser address data
 */
class ClientAddress
{

    /** @var string */
    private $street;

    /** @var string */
    private $number;

    /** @var string */
    private $complement;

    /** @var string */
    private $district;

    /** @var string */
    private $city;

    /** @var string */
    private $state;

    /** @var string */
    private $country;

    /** @var string */
    private $postalCode;

    function __construct(array $data)
    {
        $this->street = $data[0];
        $this->number = $data[1];
        $this->complement = $data[2];
        $this->district = $data[3];
        $this->city = $data[4];
        $this->state = $data[5];
        $this->country = $data[6];
        $this->postalCode = $data[7];
    }
}
