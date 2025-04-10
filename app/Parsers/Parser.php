<?php

namespace App\Parsers;

class Parser
{
    /**
     * @param string|null $string
     * @param array $parsers
     * @return string|null
     */
    public static function apply(string|null $string, array $parsers): string|null {

        $parsedString = htmlspecialchars($string);

        foreach ($parsers as $parser) {

            if (!is_string($parser) || !class_exists($parser)) {
                throw new \InvalidArgumentException('Invalid parser :'. $parser);
            }

            $parserClass = new $parser();

            if (!$parserClass instanceof Parseable) {
                throw new \InvalidArgumentException('Parser '. $parser. ' must implement ' .Parseable::class. ' interface');
            }

            $parsedString = $parserClass->parse($parsedString);
        }

        return $parsedString;
    }
}
