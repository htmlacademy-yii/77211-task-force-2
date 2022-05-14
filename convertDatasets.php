<?php

use App\DatasetsConverter\Converter;

require_once 'vendor/autoload.php';

$destinationFolder = 'sql/datasets';

(new Converter('data/categories.csv', $destinationFolder))->run();
(new Converter('data/cities.csv', $destinationFolder))->run();

echo "Готово! Все данные сконвертированы успешно.\n";
