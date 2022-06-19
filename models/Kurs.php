<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kurs".
 *
 * @property int $id
 * @property int|null $num_code
 * @property float|null $rate
 * @property string|null $date
 */
class Kurs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kurs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_code'], 'integer'],
            [['rate'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'num_code' => 'Код валюты',
            'rate' => 'Стоимость',
            'date' => 'Датa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoney()
    {
        return $this->hasOne(Money::className(), ['num_code' => 'num_code']);
    }

    public static function getLastRate($date,$num_code)
    {

        if (!$date || !$num_code)
        {
            return 'Произошла ошибка с заданной датой или кодом валюты';
        }

        return Kurs::find()
            ->where(['<=', 'date', $date])
            ->andWhere(['num_code' => $num_code])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    public static function getPrev($kurs_id, $num_code)
    {

        if (!$kurs_id || !$num_code)
        {
            return 'Произошла ошибка с указанным идентификатором ID или кодом валюты';
        }

        return Kurs::find()
            ->where(['<', 'id', $kurs_id])
            ->andWhere(['num_code' => $num_code])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->one();
    }

    public static function updateKurs(){

        if ($xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp'))
        {
            $xml = json_decode(json_encode((array) $xml), 1);
            $selected = Money::find()
                ->where('in_kurs = 1')
                ->asArray()
                ->all();

            foreach ($xml['Valute'] as $value){
                if (in_array($value['NumCode'], array_column($selected, 'num_code')))
                {
                    $today = date('Y-m-d');

                    if ($value['Nominal'] > 1)
                        $new  = (float)($value['Value'] / $value['Nominal']);
                    else
                        $new = (float)($value['Value']);

                    $base_rate = Kurs::find()
                        ->where(['date' => $today, 'num_code' => $value['NumCode']])
                        ->orderBy('id DESC')
                        ->one();

                    if (!$base_rate || $base_rate->rate != $new){

                        $model = new Kurs();
                        $model->date = $today;
                        $model->num_code = $value['NumCode'];
                        $model->rate = $new;
                        $model->save();
                    }
                }
            }
            return true;
        } else {
            exit('Не удалось получить данные. Может быть интернет отключен');
        }
    }
}
