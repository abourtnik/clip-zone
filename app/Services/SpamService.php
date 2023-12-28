<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SpamService {

    public function getWordsAsString(): null|string
    {
        return Storage::disk('local')->get('words.txt');
    }

    public function getWords(): array
    {
        $content =  $this->getWordsAsString();

        $words = preg_split('/\r\n|\r|\n/', $content);

        return collect($words)
            ->map(fn (string $word) => trim($word))
            ->filter(fn (string $word) => !empty($word))
            ->toArray();
    }

    public function checkIfSpam(string $string): bool
    {
        $words =  $this->getWords();

        foreach ($words as $word) {
            if (str_contains(strtolower($string),strtolower($word))) {
                return true;
            }
        }

        return false;
    }

    public function update(string $words): void
    {
        Storage::disk('local')->put('words.txt', $words);
    }
}
