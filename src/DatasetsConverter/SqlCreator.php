<?php

namespace App\DatasetsConverter;

use App\DatasetsConverter\Exceptions\DestinationFolderException;
use App\DatasetsConverter\Exceptions\SqlFileException;

class SqlCreator
{
    private string $dataSetName;
    private string $destinationPath;
    private string $sqlFilePath;
    private string $columnTitlesSql;
    private bool $hasCoordinates = false;
    private array $latLongKeys = [];

    /**
     * @param string $dataSetName
     * @param string $destinationPath
     */
    public function __construct(string $dataSetName, string $destinationPath)
    {
        $this->dataSetName = $dataSetName;
        $this->destinationPath = $destinationPath;
        $this->sqlFilePath = "{$this->destinationPath}/{$this->dataSetName}.sql";
    }

    /**
     * @param array $headers
     * @return void
     */
    public function addHeaders(array $headers): void
    {
        $tableName = $this->dataSetName;

        if (in_array('lat', $headers) && in_array('long', $headers)) {
            $headers = $this->changeHeaders($headers);
            $this->hasCoordinates = true;
        }

        $columnTitles = implode(', ', $headers);
        $this->columnTitlesSql = "INSERT INTO $tableName ($columnTitles) VALUES";
    }

    /**
     * @param array $lineData
     * @return void
     * @throws DestinationFolderException
     * @throws SqlFileException
     */
    public function addLine(array $lineData): void
    {
        if (count($lineData) === 1 && is_null($lineData[0])) {
            return;
        }

        $preparedData = array_map(fn($item) => $this->prepareData($item), $lineData);

        if ($this->hasCoordinates) {
            $preparedData = $this->changeLineData($preparedData);
        }

        $columnValues = implode(', ', $preparedData);
        $sql = "{$this->columnTitlesSql} ($columnValues);\n";

        $this->whiteToSqlFile($sql);
    }

    /**
     * @param array $headers
     * @return array
     */
    private function changeHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $key => $value) {
            if ($value === 'lat') {
                $this->latLongKeys['lat'] = $key;
                $result[] = 'coordinates';
            } elseif ($value === 'long') {
                $this->latLongKeys['long'] = $key;
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $lineData
     * @return array
     */
    private function changeLineData(array $lineData): array
    {
        $result = [];
        $lat = $lineData[$this->latLongKeys['lat']];
        $long = $lineData[$this->latLongKeys['long']];
        $point = "ST_GeomFromText('POINT($lat $long)')";

        foreach ($lineData as $lineKey => $lineValue) {
            if ($lineKey === $this->latLongKeys['lat']) {
                $result[] = $point;
            } elseif ($lineKey === $this->latLongKeys['long']) {
                continue;
            } else {
                $result[] = $lineValue;
            }
        }

        return $result;
    }

    /**
     * @param string $item
     * @return string|int|float
     */
    private function prepareData(string $item): string|int|float
    {
        if (is_numeric($item)) {
            if (str_contains($item, '.')) {
                return (float) $item;
            }

            return (int) $item;
        }

        return "'$item'";
    }

    /**
     * @param string $sql
     * @return void
     * @throws DestinationFolderException
     * @throws SqlFileException
     */
    private function whiteToSqlFile(string $sql): void
    {
        if (!file_exists($this->destinationPath)) {
            if (!mkdir($this->destinationPath)) {
                throw new DestinationFolderException('Не удалось создать каталог для датасетов');
            }
        }

        if (!file_put_contents($this->sqlFilePath, $sql, FILE_APPEND)) {
            throw new SqlFileException('Не удалось записать данные в файл');
        }
    }
}
