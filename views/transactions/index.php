<?php

use app\models\Transactions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-index">


    <p>
        <?= Html::a('Create Transactions', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'transaction_id',
            'transaction_code',
            'user_id',
            'total',
            'transaction_date',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Transactions $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'transaction_id' => $model->transaction_id]);
                 },
                 'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View',
                            'aria-label' => 'View',
                            'data-pjax' => '0',
                            'class' => 'btn btn-info btn-sm',
                        ]);
                    },
                    // 'update' => function ($url, $model, $key) {
                    //     return Html::a('<i class="fas fa-edit"></i>', $url, [
                    //         'title' => 'Update',
                    //         'aria-label' => 'Update',
                    //         'data-pjax' => '0',
                    //         'class' => 'btn btn-primary btn-sm',
                    //     ]);
                    // },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                            'title' => 'Delete',
                            'aria-label' => 'Delete',
                            'data-pjax' => '0',
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
