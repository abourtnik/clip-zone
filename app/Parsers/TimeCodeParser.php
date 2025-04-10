<?php

namespace App\Parsers;

class TimeCodeParser implements Parseable
{
    public function parse(string $string): string
    {
        $regex = '/(\d{1,2}:){1,2}\d{2}/m';

        return preg_replace_callback($regex, function ($matches) {

            $times = array_reverse(explode(':', $matches[0]));

            $timeCode = array_reduce(array_keys($times), function($carry, $index) use ($times) {
                return $carry + $times[$index] * pow(60, $index);
            }  , 0);

            return "<button style='vertical-align: inherit' onclick='time($timeCode)' class='btn btn-link btn-sm p-0 text-decoration-none' data-timecode='$timeCode'>{$matches[0]}</button>";

        }, $string);
    }
}
