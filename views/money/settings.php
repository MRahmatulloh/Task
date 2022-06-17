<?php

use app\models\Money;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MoneySearch */
/* @var $model app\models\Money */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $time ArrayObject */

$this->title = 'Widget Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="money-index">
    <br>
    <h3><?= Html::encode('New settings') ?></h3>

    <div class="setting-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'in_kurs')->dropdownList(
                            Money::selectList(),
                            [
                                'class' => 'select2 form-control in_kurs',
                                'multiple' => true
                            ])->label('Select currencies for giving') ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'in_widget')->dropdownList(
                            [],
                            [
                                'class' => 'select2 form-control in_widget',
                                'multiple' => true
                            ])->label('Select currencies for Widget') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'num_code')->textInput()->label('Widget update time(s)') ?>
                </div>
                <div class="col-md-2 text-right">
                    <h5> </h5>
                        <?= Html::submitButton('Apply Settings', ['class' => 'btn btn-success sendToKurs']) ?>
                </div>

            </div>

        <?php ActiveForm::end(); ?>

    </div>

        <br>
        <h3><?= Html::encode('Current settings') ?></h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'num_code',
                    'label' => 'Money',
                    'value' => 'name',
                    'filter' => Money::selectList(),
                    'filterInputOptions' => ['class' => 'form-control select2', 'prompt' => 'All'],
                    'contentOptions' => ['style' => 'width: 45%;'],
                ],

                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['checked' => (bool)$model->in_kurs];
                    }

                ],

                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        $cond = (bool)$model->in_widget;
                        return ['checked' => $cond, 'disabled' => !$cond];
                    }

                ],

                [
                    'label' => 'Refreshing time',
                    'value' => function() use ($time){
                        return $time['time'];
                    }
                ]
            ],
        ]); ?>


    </div>


<?php
$script = <<< JS

    $(document).ready(function() {
        
        $('.select2').select2();

        $('.table thead th:nth-child(3)').text('Selected for giving a kurs').addClass('text-primary')
        $('.table thead th:nth-child(4)').text('Selected for widget').addClass('text-primary')
        $('.table thead th:nth-child(5)').addClass('text-primary')
                
        $('.in_kurs').select2().on('change', function() {
            // $('.in_widget').select2({data:data[$(this).val()]});
            let data = $('.in_kurs').select2('data');
            
            $.each(data, function(idx, val) {
                data[idx].selected = val.selected = false;
            });
            
            // console.log(data)
                        
            $('.in_widget').empty().select2({
                // placeholder: "Select currencies",
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