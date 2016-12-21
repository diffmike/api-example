<?php
/**
 * User: diffmike
 * Date: 22.11.2016
 * Time: 3:47 PM
 */
namespace App\Services;

class Barcode
{
    public static function generate($number)
    {
        $code = '200' . str_pad($number, 9, '0');
        $weightFlag = true;
        $sum = 0;
        // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
        // loop backwards to make the loop length-agnostic. The same basic functionality
        // will work for codes of different lengths.
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightFlag ? 3 : 1);
            $weightFlag = !$weightFlag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;
    }
}