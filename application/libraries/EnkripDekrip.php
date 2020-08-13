<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class EnkripDekrip
{
    function Proses($kata)
    {
        $hsl = array();
        $pisahbalik = str_split(strrev($kata));
        foreach ($pisahbalik as $pb1) {
            if (ord($pb1) < 79) {
                $hsl[] = chr(ord($pb1) + 47);
            } else {
                $hsl[] = chr(ord($pb1) - 47);
            }
        }
        return implode("", $hsl);
    }
}
