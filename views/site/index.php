<?php

/** @var yii\web\View $this */
/** @var $dataProvider */
/** @var $searchModel */

use app\widgets\rate\Rate;
use app\assets\AppAsset;

AppAsset::register($this);

$this->title = 'Курсы валют';
?>
<div class="site-index">

    <div class=" text-center bg-transparent">
        <h1 class="display-4">Курсы валют на <?=date('d.m.Y')?></h1>
        <p class="lead"></p>
    </div>
    <br>

    <div class="body-content">

        <?= Rate::widget()?>

        <br>

        <div class="row">
            <div class="col-lg-4">
                <h4>Виджет маленькего размера</h4>
                <p class="lead">с временем обновления 2 мин.</p>
                <p><?= Rate::widget(['time' => 2])?></p>
            </div>
            <div class="col-lg-8">
                <h4>Виджет среднего размера</h4>
                <p class="lead">со временем обновления, указанным в настройках.</p>
                <p><?= Rate::widget()?></p>
            </div>

        </div>

    </div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    // alert(5555)
 });
JS;
$this->registerJs($script);
?>
