<?php

namespace GetNet\Helpers;

use Vectorface\Whip\Whip;
use GetNet\Exception\SDKException;

/**
 * Using Vectorface\Whip\Whip to get client IP Address
 */
class IP
{

    /**
     * Get IP Address of client
     *
     * @return string|null
     */
    public static function getClientIP(bool $cloudfare = false)
    {

        if ($cloudfare) {
            $whip = new Whip(Whip::CLOUDFLARE_HEADERS | Whip::REMOTE_ADDR);
        } else {
            $whip = new Whip();
        }

        return $whip->getValidIpAddress();
    }
}
