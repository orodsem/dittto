<?php

namespace AppBundle\Service\Security;

/**
* Class Date
 * Does what you need to do with DateTime object
*
 * @package AppBundle\Service\DateTime
*/
class Encryption
{
    /**
     * @return null|string
     */
    public function encrypt($key)
    {
        if (empty($key)) {
            return null;
        }

        for ($i = 0; $i < strlen($key); $i++) {
            $r[] = ord($key[$i]) + 2;
        }

        return implode('.', $r);
    }

    /**
     * @return null|string
     */
    function decrypt($key)
    {
        if (empty($key)) {
            return null;
        }

        $key = explode(".", $key);
        for($i = 0; $i < count($key); $i++)
            $key[$i] = chr($key[$i] - 2);
        return implode('', $key);
    }
}