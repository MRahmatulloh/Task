<?php

use app\models\Kurs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\KursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kurs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="w-100 text-right">
        <?= Html::a('Get Latest', ['kurs/get-latest'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'money.name',
            'rate',
            'date',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Kurs $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
