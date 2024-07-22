<?php

namespace app\controllers;

use app\models\Product;
use app\models\Transactions;
use app\models\TransactionsSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\TransactionDetails;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class TransactionsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // '@' means authenticated users
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        return Yii::$app->response->redirect(['site/login']);
                    },
                ],
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
     * Lists all Transactions models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Transactions::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transactions model.
     * @param int $transaction_id Transaction ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($transaction_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($transaction_id),
        ]);
    }

    /**
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    
    
     public function actionCreate()
     {
         $model = new Transactions();
         $model->transaction_id = Yii::$app->security->generateRandomString();
     
         if ($model->load(Yii::$app->request->post())) {
             $transactionDetailsData = Yii::$app->request->post('TransactionDetailsJson');
             $model->transactionDetailsData = json_decode($transactionDetailsData, true);
     
             // Ambil nilai amount_paid dan hitung change_returned
             $model->amount_paid = Yii::$app->request->post('Transactions')['amount_paid'];
             $model->change_returned = $model->amount_paid - $model->total;
     
             if ($model->save()) {
                 $this->saveTransactionDetails($model);
                 return $this->redirect(['view', 'transaction_id' => $model->transaction_id]);
             }
         }
     
         return $this->render('create', [
             'model' => $model,
         ]);
     }
     
     public function actionUpdate($transaction_id)
     {
         $model = $this->findModel($transaction_id);
     
         if ($model->load(Yii::$app->request->post())) {
             $transactionDetailsData = Yii::$app->request->post('TransactionDetailsJson');
             $model->transactionDetailsData = json_decode($transactionDetailsData, true);
     
             // Ambil nilai amount_paid dan hitung change_returned
             $model->amount_paid = Yii::$app->request->post('Transactions')['amount_paid'];
             $model->change_returned = $model->amount_paid - $model->total;
     
             if ($model->save()) {
                 $this->saveTransactionDetails($model);
                 return $this->redirect(['view', 'transaction_id' => $model->transaction_id]);
             }
         }
     
         return $this->render('update', [
             'model' => $model,
         ]);
     }
     

    public function actionGetProductPrice($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $product = Product::findOne($id);
        if ($product) {
            return ['price' => $product->price];
        }
        return ['price' => 0];
    }

    /**
     * Deletes an existing Transactions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $transaction_id Transaction ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($transaction_id)
    {
        $this->findModel($transaction_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transactions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $transaction_id Transaction ID
     * @return Transactions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($transaction_id)
    {
        if (($model = Transactions::findOne(['transaction_id' => $transaction_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReport()
    {
        $searchModel = new TransactionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExportPdf($month = null)
    {
        $searchModel = new TransactionsSearch();
        $params = Yii::$app->request->queryParams;
        if ($month !== null) {
            $params['TransactionsSearch']['month'] = $month;
        }
        $dataProvider = $searchModel->search($params);
        $models = $dataProvider->getModels();

        $pdf = new \Mpdf\Mpdf();
        $pdfContent = $this->renderPartial('_reportPdf', ['models' => $models]);
        $pdf->WriteHTML($pdfContent);
        $pdf->Output('TransactionReport.pdf', 'D');
        exit;
    }

    public function actionExportExcel($month = null)
    {
        $searchModel = new TransactionsSearch();
        $params = Yii::$app->request->queryParams;
        if ($month !== null) {
            $params['TransactionsSearch']['month'] = $month;
        }
        $dataProvider = $searchModel->search($params);
        $models = $dataProvider->getModels();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Transaction ID');
        $sheet->setCellValue('B1', 'User ID');
        $sheet->setCellValue('C1', 'Total');
        $sheet->setCellValue('D1', 'Transaction Date');

        // Set data
        $row = 2;
        foreach ($models as $model) {
            $sheet->setCellValue('A' . $row, $model->transaction_id);
            $sheet->setCellValue('B' . $row, $model->user_id);
            $sheet->setCellValue('C' . $row, $model->total);
            $sheet->setCellValue('D' . $row, $model->transaction_date);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'TransactionReport.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    protected function saveTransactionDetails($model)
    {
        TransactionDetails::deleteAll(['transaction_id' => $model->transaction_id]);

        foreach ($model->transactionDetailsData as $detailData) {
            $detail = new TransactionDetails();
            $detail->transaction_id = $model->transaction_id;
            $detail->product_id = $detailData['product_id'];
            $detail->quantity = $detailData['quantity'];
            $detail->price = $detailData['price'];
            $detail->total_price = $detailData['quantity'] * $detailData['price'];
            $detail->save();
        }
    }

    public function actionPrintReceipt($transaction_id)
    {
        $transaction = Transactions::findOne($transaction_id);
    
        if (empty($transaction)) {
            throw new \Exception('Transaction not found.');
        }
    
        $pdf = new \Mpdf\Mpdf();
    
        $html = $this->renderPartial('_receipt', [
            'transaction' => $transaction,
        ]);
    
        $pdf->WriteHTML($html);
        return $pdf->Output('Receipt.pdf', \Mpdf\Output\Destination::INLINE);
    }    
}
