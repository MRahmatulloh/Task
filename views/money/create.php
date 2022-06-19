<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Money */

$this->title = 'Добавить валюту';
$this->params['breadcrumbs'][] = ['label' => 'Валюты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="money-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
