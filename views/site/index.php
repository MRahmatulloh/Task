<?php

/** @var yii\web\View $this */
/** @var $dataProvider */
/** @var $searchModel */

use app\models\Kurs;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Курсы валют</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>
    </div>

    <div class="body-content">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

//                'date',
                'money.char_code',
                'rate',
//                [
//                    'attribute' => 'lastDay',
//                    'format' => 'html',
//                    'value' => function($data){
//                        $date = date ("Y-m-d" , strtotime (date('Y-m-d').'-1 day'));
//                        $lastDay = Kurs::getLastRate($date, $data['num_code']);
//                        return $lastDay->rate .' '.Html::img(Yii::getAlias('@web').'/img/up.down');
//                    }
//                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function($data){
                        $status = false;
                        $date = date ("Y-m-d" , strtotime (date('Y-m-d').'-1 day'));
                        $lastDay = Kurs::getLastRate($date, $data['num_code']);
                        if($data['rate']>$lastDay->rate)
                            $status = true;
                        return ($status)?
                            Html::img(Yii::getAlias('@web').'/img/up.png'):
                            Html::img(Yii::getAlias('@web').'/img/up.down');
                    }
                ],
            ],
        ]); ?>

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <h1><?= \app\widgets\rate\Rate::widget()?></h1>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>

        </div>

    </div>
</div>
