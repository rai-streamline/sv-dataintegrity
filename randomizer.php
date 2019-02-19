<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity;

class Randomizer
{
    public function randomString($length = 6, $allowNull = false, Array $additionalCharacters = array())
    {
        if (true === $allowNull) {
            $randNull = rand(1, 10);
            if (10 == $randNull) {
                return null;
            }
        }
        $str = "";
        $characters = array_merge(
            range('A', 'Z'), range('a', 'z'), range('0', '9'), $additionalCharacters);
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function randomDate($allowNull = false, $dateOnly = false)
    {
        if (true === $allowNull) {
            $randNull = rand(1, 10);
            if (10 == $randNull) {
                return null;
            }
        }

        if (true === $dateOnly) {
            return ('20' . rand(0, 1) . rand(0, 9) . '-' . str_pad(rand(1, 12), 2, '0') .
                '-' . str_pad(rand(1, 28), 2, '0'));
        }
        return ('20' . rand(0, 1) . rand(0, 9) . '-' . str_pad(rand(1, 12), 2, '0') .
            '-' . str_pad(rand(1, 28), 2, '0') . ' 00:00:00');
    }
}
