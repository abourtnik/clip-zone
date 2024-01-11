<?php

namespace App\Helpers;

class Parser
{
    const PARSERS = [
        'links' => 'parseLinks',
        'timecodes' => 'parseTimeCodes',
    ];

    public static function applyParsers(string|null $string, array $parsers): string|null {

        $result = htmlspecialchars($string);

        foreach ($parsers as $parser) {

            if (!in_array($parser, array_keys(self::PARSERS))) {
                throw new \Exception('Invalid parser :'. $parser);
            }

            $method = self::PARSERS[$parser];

            $result = self::$method($result);
        }

        return $result;
    }

    private static function parseLinks(string $string): string|null {

        $regex = '/(https?:\/\/)?([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/\S*)?)/m';

        return preg_replace($regex, '<a class="text-decoration-none" href="//${2}" target="_blank" title="$0" rel="external nofollow">$0</a>', $string);
    }

    private static function parseTimeCodes(string $string): string|null
    {
        $regex = '/(\d{1,2}:){1,2}\d{2}/m';

        return preg_replace_callback($regex, function ($matches) {

            $times = array_reverse(explode(':', $matches[0]));

            $timecode = array_reduce(array_keys($times), function($carry, $index) use ($times) {
                return $carry + $times[$index] * pow(60, $index);
            }  , 0);

            return "<button style='vertical-align: inherit' onclick='time($timecode)' class='btn btn-link btn-sm p-0 text-decoration-none' data-timecode='$timecode'>{$matches[0]}</button>";

        }, $string);
    }
}
