<?php

namespace App\DatasetsConverter;

class Converter
{
    private string $filePath;
    private string $destinationPath;

    /**
     * @param string $filePath
     * @param string $destinationPath
     */
    public function __construct(string $filePath, string $destinationPath)
    {
        $this->filePath = $filePath;
        $this->destinationPath = $destinationPath;
    }

    /**
     * @return void
     * @throws Exceptions\SourceFileException
     */
    public function run(): void
    {
        $parser = new CsvParser($this->filePath);
        $creator = new SqlCreator($parser->getDatasetName(), $this->destinationPath);

        $creator->addHeaders($parser->getHeaders());

        foreach ($parser->getNextLine() as $lineData) {
            $creator->addLine($lineData);
        }
    }
}
