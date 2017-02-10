<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 25/08/16
 * Time: 17:02
 */

namespace App\Helpers;

class StringHelper {
    static public function strFormat($string, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($string[$k]))
                    $maskared .= $string[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    static public function phoneFormat($phone) {
        $format = (strlen($phone) == 11) ? '(##) #####-####' : '(##) ####-####';
        return self::strFormat($phone, $format);
    }

    static public function onlyNumbers($string) {
        preg_match_all('!\d+!', $string, $matches);
        $var = implode('', $matches[0]);
        return $var;
    }
}