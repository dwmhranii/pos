<?php
namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [   
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'], // Hanya berlaku untuk tindakan-tindakan ini
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'], // '@' berarti pengguna harus login
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['superadmin'], // Hanya pengguna dengan peran 'superadmin'
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['site/login']); // Redirect ke halaman login jika belum login
                    }
                    Yii::warning('Access denied: ' . Yii::$app->user->identity->username . ' tried to access ' . $action->id . ' with role ' . Yii::$app->user->identity->role);
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
                },
            ],
        ];
    }


    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                Yii::debug('Validation passed.');
                $model->password = Yii::$app->security->generatePasswordHash($model->password);
                $model->auth_key = Yii::$app->security->generateRandomString(); // Generate auth key
                if ($model->save()) {
                    Yii::debug('Model saved.');
                    return $this->redirect(['view', 'id' => $model->user_id]);
                } else {
                    Yii::error('Failed to save model: ' . json_encode($model->errors));
                }
            } else {
                Yii::error('Validation failed: ' . json_encode($model->errors));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!empty($model->password)) {
                $model->setPassword($model->password); // Hash the new password if it is set
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->user_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
