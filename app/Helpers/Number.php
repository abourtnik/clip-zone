<?php

namespace App\Helpers;

class Number
{
    public static function humanize(int $number): string {

        $abbrevs = [12 => "T", 9 => "B", 6 => "M", 3 => "k", 0 => ""];

        foreach($abbrevs as $exponent => $abbrev) {
            if($number >= pow(10, $exponent)) {
                $display_num = $number / pow(10, $exponent);
                $decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return number_format($display_num,$decimals, '.') . ' ' . $abbrev;
            }
        }

        return '0';
    }

    public static function unique(): string {
        $time = str_split(time());
        shuffle($time);
        return implode('', $time);
    }
}
