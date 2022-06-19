<?php

use app\models\Money;
use yii\helpers\Html;
use yii\grid\GridView;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MoneySearch */
/* @var $model app\models\Money */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $time ArrayObject */

$this->title = 'Настройки виджета';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="money-index">
        <br>
        <h3><?= Html::encode('Новые настройки') ?></h3>

        <div class="setting-form">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'in_kurs')->dropdownList(
                        Money::selectList(),
                        [
                            'class' => 'select2 form-control in_kurs',
                            'multiple' => true
                        ])->label('Выберите валюту для получения курса') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'in_widget')->dropdownList(
                        [],
                        [
                            'class' => 'select2 form-control in_widget',
                            'multiple' => true
                        ])->label('Выберите валюту для виджета') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'num_code')->textInput()->label('Время обновления виджета (мин)') ?>
                </div>
                <div class="col-md-2 text-right">
                    <h5> </h5>
                    <?= Html::submitButton('Cохранить', ['class' => 'btn btn-success']) ?>
                </div>

            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <br>
        <h3><?= Html::encode('Текущие настройки') ?></h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'num_code',
                    'label' => 'Bалюта',
                    'value' => 'name',
                    'filter' => Money::selectList(),
                    'filterInputOptions' => ['class' => 'form-control select2', 'prompt' => 'Bce'],
                    'contentOptions' => ['style' => 'width: 35%;'],
                ],

                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['checked' => (bool)$model->in_kurs];
                    },
                    'contentOptions' => ['class'=>'text-center'],

                ],

                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        $cond = (bool)$model->in_widget;
                        return ['checked' => $cond, 'disabled' => !$cond];
                    },
                    'contentOptions' => ['class'=>'text-center'],

                ],

                [
                    'label' => 'Время обновления (мин)',
                    'value' => function () use ($time) {
                        return $time['time'];
                    },
                    'contentOptions' => ['class'=>'text-center'],
                ]
            ],
        ]); ?>


    </div>


<?php
$script = <<< JS

    $(document).ready(function() {
        
        $('.select2').select2();

        $('.table thead th:nth-child(3)').text('Выбрано для получения курса').addClass('text-primary')
        $('.table thead th:nth-child(4)').text('Выбрано для виджета').addClass('text-primary')
        $('.table thead th:nth-child(5)').addClass('text-primary')
                
        $('.in_kurs').select2().on('change', function() {
            
            let data = $('.in_kurs').select2('data');
            
            $.each(data, function(idx, val) {
                data[idx].selected = val.selected = false;
            });
                        
            $('.in_widget').empty().select2({
                data: data
            });
            
        }).trigger('change');
                
        $('input').change(function() {
            $(this).prop("checked", true);
        });
        
        $(".sendToKurs").on("click",function() {
        var keys = $('#w0').yiiGridView('getSelectedRows');
        console.log(keys);
        var ajax = new XMLHttpRequest();
        $.ajax({
            type: "POST",
            url: 'add-to-kurs', 
            data: {
                keylist: keys,
                _csrf: yii.getCsrfToken()
                },
            success: function(result){
              console.log(result);
              // window.location.reload();
            }
        });
        });
        
    });

JS;
$this->registerJs($script);
?>