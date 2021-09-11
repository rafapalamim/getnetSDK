<?php

namespace GetNet\Parts;

/**
 * GetNet OAuth2 proccess
 */
class Auth
{

    /** @var string */
    public const SCOPE = 'oob';

    /** @var string */
    public const GRANT_TYPE = 'client_credentials';

    /** @var string */
    private $authorization;

    function __construct()
    {
    }

    /**
     * Set OAuth2 access
     *
     * @param string $authorization
     * @return Auth
     */
    public function setAuthorization(string $authorization): Auth
    {
        $this->authorization = $authorization;
        return $this;
    }

    /**
     * Get OAuth2
     *
     * @return string
     */
    public function getAuthorization():string
    {
        return $this->authorization;
    }
}
