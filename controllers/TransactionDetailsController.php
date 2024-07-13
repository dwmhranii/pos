<?php

namespace app\controllers;

use Yii;
use app\models\TransactionDetails;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionDetailsController implements the CRUD actions for TransactionDetails model.
 */
class TransactionDetailsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TransactionDetails models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TransactionDetails::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'detail_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransactionDetails model.
     * @param int $detail_id Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($detail_id),
        ]);
    }

    /**
     * Creates a new TransactionDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TransactionDetails();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'detail_id' => $model->detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TransactionDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $detail_id Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($detail_id)
    {
        $model = $this->findModel($detail_id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'detail_id' => $model->detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransactionDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $detail_id Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($detail_id)
    {
        $this->findModel($detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TransactionDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $detail_id Detail ID
     * @return TransactionDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($detail_id)
    {
        if (($model = TransactionDetails::findOne(['detail_id' => $detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
