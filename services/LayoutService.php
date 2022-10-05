<?php

namespace app\services;

class LayoutService
{
    /**
     * @param string $route
     * @return string
     */
    public static function addClassToMainSection(string $route): string
    {
        $classMap = [
            'tasks/create' => 'main-content--center',
            'profile/index' => 'main-content--left',
            'profile/security' => 'main-content--left',
        ];

        return $classMap[$route] ?? '';
    }

    /**
     * @param string $controllerId
     * @return bool
     */
    public static function showNavigation(string $controllerId): bool
    {
        return $controllerId !== 'registration' && !\Yii::$app->user->isGuest;
    }
}