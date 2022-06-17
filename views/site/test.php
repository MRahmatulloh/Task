<?php

use yii\grid\ActionColumn;

?>
    public function actionTest(){

$date = date ("Y-m-d" , strtotime (date('Y-m-d').'-1 day'));

$lastDay = Kurs::getLastRate( $date, 840);


$today = Kurs::getLastRate('2022-06-04', 840)->rate;

echo $lastDay->rate.' '.$today;
}

public function actionTest1(){

$xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp');

if ($xml) {
$xml = json_decode(json_encode((array) $xml), 1);
echo "<pre>";
//            print_r($xml);
//            print_r(($xml['Valute']));
//            [NumCode] => 826
//            [CharCode] => GBP
//            [Nominal] => 1
//            [Name] => Фунт стерлингов Соединенного королевства

            foreach ($xml['Valute'] as $value){
                $model = new Kurs();

                $model->date = '2022-06-04';
                $model->name = $value['Name'];
                $model->num_code = $value['NumCode'];
                $model->char_code = $value['CharCode'];

                if ($value['Nominal'] > 1)
                    $model->rate = (float)($value['Value'] / $value['Nominal']);
                else
                    $model->rate = (float)($value['Value'])-3;

                $model->save();
                print_r($model->errors);
            }
            echo "<pre>";
        } else {
            exit('Failed to get data.');
        }
    }
  /////////////
            public static function getLastRate($date,$num_code)
    {

        if (!is_string($date) || !is_integer($num_code))
        {
            return 'Occured error with given date or currency code';
        }

        return Kurs::find()->where(['<=', 'date', $date])->andWhere(['num_code' => $num_code])->orderBy(['date' => SORT_DESC])->one();
    }

    public static function getMoneyList()
    {
        return Kurs::find()->select(['name', 'num_code'])->groupBy('num_code')->asArray()->all();
    }
        ///////////////////

    public function actionAddToKurs(){
        if (!$_POST['keylist'] && !is_array($_POST['keylist']))
            return json_encode(['status' => 'error']);

        $update = Yii::$app->getDb()->createCommand('update money set selected = null')->execute();

        $list = $_POST['keylist'];

        foreach ($list as $id){
            $model = Money::findOne($id);
            if ($model)
                $model->selected = 1;

            $model->save(false);
        }
        return json_encode(['status' => 'ok']);
    }
        /////////////////////////////////
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'char_code',
                    'num_code',
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $column) {
                            return ['checked' => (bool) $model->selected];
                        }

                    ],

                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Money $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>

</div>

<?php
$script = <<< JS

    $(document).ready(function() {

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