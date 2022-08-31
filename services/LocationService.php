<?php

namespace app\services;

use app\models\City;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class LocationService
{
    /**
     * @param string $geocode
     * @return array
     * @throws Exception
     */
    public function getGeocodeData(string $geocode): array
    {
        $apiKey = Yii::$app->params['geocoderApiKey'];
        $apiUri = 'https://geocode-maps.yandex.ru/1.x';
        $result = [];
        $client = new Client();

        try {
            $response = $client->request('GET', $apiUri, [
                'query' => [
                    'geocode' => $geocode,
                    'apikey' => $apiKey,
                    'format' => 'json'
                ],
            ]);

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);

            $geoObjects = ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember');

            foreach ($geoObjects as $geoObject) {
                $result[] = $this->getLocationData($geoObject);
            }
        } catch (GuzzleException $e) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param string $table
     * @param int $id
     * @return array
     */
    public function getCoordinatesFromPoint(string $table, int $id): array
    {
        $query = new Query();
        ['lat' => $lat, 'long' => $long] = $query->select(['ST_X(coordinates) as lat, ST_Y(coordinates) as long'])
            ->from($table)
            ->where(['id' => $id])
            ->one();

        return [
            'lat' => $lat,
            'long' => $long,
        ];
    }

    /**
     * @return array
     */
    public function getUsersCityCoordinates(): array
    {
        $userCityId = Yii::$app->user->identity->city_id;
        return $this->getCoordinatesFromPoint('city', $userCityId);
    }

    /**
     * @param int $taskId
     * @return array
     */
    public function getTaskCoordinates(int $taskId): array
    {
        return $this->getCoordinatesFromPoint('task', $taskId);
    }

    /**
     * @param array $geoObject
     * @return array
     * @throws Exception
     */
    public function getLocationData(array $geoObject): array
    {
        $geocoderMetaData = ArrayHelper::getValue($geoObject, 'GeoObject.metaDataProperty.GeocoderMetaData');
        $addressComponents = ArrayHelper::map(
            ArrayHelper::getValue($geocoderMetaData, 'Address.Components'),
            'kind',
            'name'
        );

        $location = ArrayHelper::getValue($geocoderMetaData, 'text');
        $city = ArrayHelper::getValue($addressComponents, 'locality');
        $address = ArrayHelper::getValue($geoObject, 'GeoObject.name');
        [$long, $lat] = explode(' ', ArrayHelper::getValue($geoObject, 'GeoObject.Point.pos'));

        return [
            'location' => $location,
            'city' => $city,
            'address' => $address,
            'lat' => $lat,
            'long' => $long,
        ];
    }

    /**
     * @param string $cityName
     * @return bool
     */
    public function isCityExistsInDB(string $cityName): bool
    {
        return City::find()->where(['name' => $cityName])->exists();
    }

    /**
     * @param string $cityName
     * @return int
     */
    public function getCityIdByName(string $cityName): int
    {
        $city = City::find()->where(['name' => $cityName])->limit(1)->one();
        return $city->id;
    }

    /**
     * @param int $taskId
     * @param string $lat
     * @param string $long
     * @return void
     * @throws \yii\db\Exception
     */
    public function setPointCoordinatesToTask(int $taskId, string $lat, string $long): void
    {
        $point = "POINT($lat $long)";

        Yii::$app->db->createCommand("UPDATE task SET coordinates=ST_GeomFromText(:point) WHERE id=:id", [
                ':point' => $point,
                ':id' => $taskId
            ])->execute();
    }
}