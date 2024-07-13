<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

$this->title = 'Transaction Report';
$this->params['breadcrumbs'][] = $this->title;

$months = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December',
];
?>
<div class="transactions-report">

    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
        <?= $form->field($searchModel, 'month')->dropDownList($months, ['prompt' => 'Select Month'])->label('Filter by Month') ?>
        <div class="form-group">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <p>
        <?= Html::a('Export to PDF', ['export-pdf', 'month' => $searchModel->month], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Export to Excel', ['export-excel', 'month' => $searchModel->month], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'transaction_id',
            'user_id',
            'total',
            'transaction_date',
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
