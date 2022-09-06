<?php

namespace app\controllers;

use app\models\Category;
use app\models\ProfileForm;
use app\models\SecurityForm;
use app\models\User;
use app\services\FileService;
use app\services\UserService;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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

    /**
     * @return Response|array|string
     * @throws Exception
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    public function actionIndex(): Response|array|string
    {
        $this->view->title = 'Настройки профиля :: Taskforce';

        $user = Yii::$app->user->identity;
        $userService = new UserService();
        $fileService = new FileService();

        $profileForm = new ProfileForm();
        $categoriesList = Category::getCategoriesList();
        $userCategoriesList = ArrayHelper::getColumn($user->categories, 'id');

        if (Yii::$app->request->getIsPost()) {
            $profileForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($profileForm);
            }

            if ($profileForm->validate()) {
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
        }

        return $this->render('index', [
            'user' => $user,
            'profileForm' => $profileForm,
            'categoriesList' => $categoriesList,
            'userCategoriesList' => $userCategoriesList,
        ]);
    }

    /**
     * @return Response|array|string
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionSecurity(): Response|array|string
    {
        $this->view->title = 'Настройки безопасности :: Taskforce';

        $user = Yii::$app->user->identity;
        $isUserShowContacts = $user->show_only_customer;
        $securityForm = new SecurityForm();
        $userService = new UserService();

        if (Yii::$app->request->getIsPost()) {
            $securityForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($securityForm);
            }

            if ($securityForm->validate()) {
                $userService->updateUserSecurity($securityForm, $user);
                return $this->redirect(['user/view', 'id' => $user->id]);
            }
        }

        return $this->render('security', [
            'user' => $user,
            'securityForm' => $securityForm,
            'isUserShowContacts' => $isUserShowContacts
        ]);
    }
}