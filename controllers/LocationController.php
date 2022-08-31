<?php

namespace app\controllers;

use app\services\LocationService;
use Exception;
use Yii;
use yii\filters\AccessControl;
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
        $locationService = new LocationService();
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $locationService->getGeocodeData($geocode);
    }
}