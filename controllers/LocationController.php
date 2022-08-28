<?php

namespace app\controllers;

use app\services\LocationService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class LocationController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['geocode'],
                        'roles' => ['customer'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $geocode
     * @return array
     * @throws Exception
     */
    public function actionGeocode(string $geocode): array
    {
        $apiKey = Yii::$app->params['geocoderApiKey'];
        $apiUri = 'https://geocode-maps.yandex.ru/1.x';
        $result = [];

        Yii::$app->response->format = Response::FORMAT_JSON;

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
                $result[] = (new LocationService())->getLocationData($geoObject);
            }
        } catch (GuzzleException $e) {
            $result = [];
        }

        return $result;
    }
}