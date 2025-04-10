<?php

namespace App\Parsers;

interface Parseable
{
    public function parse(string $string) : string;
}
