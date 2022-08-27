<?php

namespace app\services;

use app\models\City;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class LocationService
{
    public function getUsersCityCoordinates()
    {
        $query = new Query();
        $userCityId = Yii::$app->user->identity->city_id;
        ['lat' => $lat, 'long' => $long] = $query->select(['ST_X(coordinates) as lat, ST_Y(coordinates) as long'])
            ->from('city')
            ->where(['id' => $userCityId])
            ->one();

        return [
            'lat' => $lat,
            'long' => $long,
        ];
    }

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

    public function isCityExistsInDB(string $cityName): bool
    {
        return City::find()->where(['name' => $cityName])->exists();
    }

    public function getCityIdByName(string $cityName): int
    {
        $city = City::find()->where(['name' => $cityName])->limit(1)->one();
        return $city->id;
    }

    public function setPointCoordinatesToTask(int $taskId, string $lat, string $long): void
    {
        $point = "POINT($lat $long)";

        Yii::$app->db->createCommand("UPDATE task SET coordinates=ST_GeomFromText(:point) WHERE id=:id", [
                ':point' => $point,
                ':id' => $taskId
            ])->execute();
    }
}