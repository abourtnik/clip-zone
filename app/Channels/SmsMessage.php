<?php

namespace App\Channels;

class SmsMessage
{
    protected string $to;
    protected string $from;
    protected array $lines;

    /**
     * SmsMessage constructor.
     */
    public function __construct()
    {
        $this->from = config('app.name');
    }

    public function line(string $line): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getLines(): string
    {
        return implode("\n", $this->lines);
    }
}
