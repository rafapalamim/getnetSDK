<?php

namespace GetNet\Parts;

class ClientAddress
{

    private $street;
    private $number;
    private $complement;
    private $district;
    private $city;
    private $state;
    private $country;
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
