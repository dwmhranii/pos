<?php

$this->title = 'Create Transactions';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
