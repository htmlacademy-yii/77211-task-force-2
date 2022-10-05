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
use yii\base\Module;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class ProfileController extends Controller
{
    private User $user;

    /**
     * @param string $id
     * @param Module $module
     * @param array $config
     */
    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->user = Yii::$app->user->identity;
    }

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
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Настройки профиля :: Taskforce';

        $profileForm = new ProfileForm();
        $categoriesList = Category::getCategoriesList();
        $userCategoriesList = ArrayHelper::getColumn($this->user->categories, 'id');

        return $this->render('index', [
            'user' => $this->user,
            'profileForm' => $profileForm,
            'categoriesList' => $categoriesList,
            'userCategoriesList' => $userCategoriesList,
        ]);
    }


    /**
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    public function actionUpdateProfile(): Response
    {
        $profileForm = new ProfileForm();
        $userService = new UserService();
        $fileService = new FileService();

        if (!Yii::$app->request->getIsPost()) {
            throw new BadRequestHttpException();
        }

        if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
            $avatar = null;
            $uploadedFile = UploadedFile::getInstance($profileForm, 'avatar');
            if (!empty($uploadedFile)) {
                $avatar = $fileService->upload($uploadedFile, 'avatar', $this->user->id);
            }

            $userService->updateUserProfile($profileForm, $this->user, $avatar);

            if ($this->user->role === User::ROLE_EXECUTOR) {
                $userService->updateUserCategories($profileForm->categories, $this->user);
            }

            if ($this->user->role === User::ROLE_CUSTOMER) {
                return $this->redirect(['profile/index']);
            }

            return $this->redirect(['user/view', 'id' => $this->user->id]);
        }

        throw new ServerErrorHttpException('Невозможно обновить профайл пользователя');
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionProfileFormAjaxValidate(): array
    {
        if (!Yii::$app->request->getIsPost() || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $profileForm = new ProfileForm();
        $profileForm->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ActiveForm::validate($profileForm);
    }

    /**
     * @return string
     */
    public function actionSecurity(): string
    {
        $this->view->title = 'Настройки безопасности :: Taskforce';

        $isUserShowContacts = $this->user->show_only_customer;
        $securityForm = new SecurityForm();

        return $this->render('security', [
            'user' => $this->user,
            'securityForm' => $securityForm,
            'isUserShowContacts' => $isUserShowContacts
        ]);
    }

    /**
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     */
    public function actionUpdateSecurity(): Response
    {
        $securityForm = new SecurityForm();
        $userService = new UserService();

        if (!Yii::$app->request->getIsPost()) {
            throw new BadRequestHttpException();
        }

        if ($securityForm->load(Yii::$app->request->post()) && $securityForm->validate()) {
            $userService->updateUserSecurity($securityForm, $this->user);

            if ($this->user->role === User::ROLE_CUSTOMER) {
                return $this->redirect(['profile/security']);
            }

            return $this->redirect(['user/view', 'id' => $this->user->id]);
        }

        throw new ServerErrorHttpException('Невозможно обновить настройки безопасности пользователя');
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionSecurityFormAjaxValidate(): array
    {
        if (!Yii::$app->request->getIsPost() || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $securityForm = new SecurityForm();
        $securityForm->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ActiveForm::validate($securityForm);
    }
}