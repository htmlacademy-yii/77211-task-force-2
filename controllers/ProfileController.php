<?php

namespace app\controllers;

use app\models\Category;
use app\models\ProfileForm;
use app\models\User;
use app\services\FileService;
use app\services\UserService;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Настройки профиля :: Taskforce';

        $user = Yii::$app->user->identity;
        $userService = new UserService();
        $fileService = new FileService();

        $profileForm = new ProfileForm();
        $categoriesList = Category::getCategoriesList();
        $userCategoriesList = ArrayHelper::getColumn($user->categories, 'id');

        if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
            $avatar = null;
            $uploadedFile = UploadedFile::getInstance($profileForm, 'avatar');
            if (!empty($uploadedFile)) {
                $avatar = $fileService->upload($uploadedFile, 'avatar', $user->id);
            }

            $userService->updateUserProfile($profileForm, $user, $avatar);

            if ($user->role === User::ROLE_EXECUTOR) {
                $userService->updateUserCategories($profileForm->categories, $user);
            }

            return $this->redirect(['user/view', 'id' => $user->id]);
        }

        return $this->render('index', [
            'user' => $user,
            'profileForm' => $profileForm,
            'categoriesList' => $categoriesList,
            'userCategoriesList' => $userCategoriesList,
        ]);
    }

    public function actionSecurity()
    {
        return $this->render('security');
    }
}