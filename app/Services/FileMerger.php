<?php

namespace App\Services;

class FileMerger
{
    /**
     * @var bool|resource
     */
    protected $destinationFile;

    /**
     * FileMerger constructor.
     *
     * @param string $targetFile
     *
     * @throws \Exception
     */
    public function __construct(string $targetFile)
    {
        // open the target file
        if (!$this->destinationFile = @fopen($targetFile, 'ab')) {
            throw new \Exception('Failed to open output stream.', 102);
        }
    }

    /**
     * Appends given file.
     *
     * @param string $sourceFilePath
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function appendFile(string $sourceFilePath): FileMerger
    {
        // open the new uploaded chunk
        if (!$in = @fopen($sourceFilePath, 'rb')) {
            @fclose($this->destinationFile);
            throw new \Exception('Failed to open input stream', 101);
        }

        // read and write in buffs
        while ($buff = fread($in, 4096)) {
            fwrite($this->destinationFile, $buff);
        }

        @fclose($in);

        return $this;
    }

    /**
     * Closes the connection to the file.
     */
    public function close(): void
    {
        @fclose($this->destinationFile);
    }
}
