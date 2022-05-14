<?php

namespace App\DatasetsConverter;

use App\DatasetsConverter\Exceptions\SourceFileException;
use RuntimeException;
use SplFileObject;

class CsvParser
{
    private SplFileObject $fileObject;

    /**
     * @param string $filePath
     * @throws SourceFileException
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new SourceFileException('Файла с данными не существует');
        }

        try {
            $this->fileObject = new SplFileObject($filePath);
        } catch (RuntimeException $exception) {
            throw new SourceFileException('Не удалось открыть файл на чтение');
        }
    }

    /**
     * @return string
     */
    public function getDatasetName(): string
    {
        return $this->fileObject->getBasename('.csv');
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }

    /**
     * @return iterable
     */
    public function getNextLine(): iterable
    {
        $this->fileObject->seek(1);

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }
}
