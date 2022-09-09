<?php

namespace app\services;

class LayoutService
{
    public static function addClassToMainSection(string $route): string
    {
        $classMap = [
            'tasks/create' => 'main-content--center',
            'profile/index' => 'main-content--left',
            'profile/security' => 'main-content--left',
        ];

        return $classMap[$route] ?? '';
    }
}