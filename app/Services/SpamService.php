<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SpamService {

    public const string SPAM_FOLDER = 'spams';

    public const string WORDS_FILE = 'words.txt';
    public const string EMAILS_FILE = 'emails.txt';


    public function getWordsAsString(): null|string
    {
        return Storage::disk('local')->get(self::SPAM_FOLDER.DIRECTORY_SEPARATOR.self::WORDS_FILE);
    }

    public function getEmailsAsString(): null|string
    {
        return Storage::disk('local')->get(self::SPAM_FOLDER.DIRECTORY_SEPARATOR.self::EMAILS_FILE);
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

    public function getEmails(): array
    {
        $content =  $this->getEmailsAsString();

        $words = preg_split('/\r\n|\r|\n/', $content);

        return collect($words)
            ->map(fn (string $word) => trim($word))
            ->filter(fn (string $word) => !empty($word))
            ->toArray();
    }

    public function checkIfSpam(string $string): bool
    {
        $words = $this->getWords();

        foreach ($words as $word) {
            if (str_contains(strtolower($string),strtolower($word))) {
                return true;
            }
        }

        return false;
    }

    public function update(array $data): void
    {
        foreach ($data as $key => $value) {
            Storage::disk('local')->put(self::SPAM_FOLDER.DIRECTORY_SEPARATOR.$key.'.txt', $value ?? '');
        }
    }
}
