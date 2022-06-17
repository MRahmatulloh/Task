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
            'id' => 'ID',
            'num_code' => 'Num Code',
            'rate' => 'Rate',
            'date' => 'Date',
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

        if (!is_string($date) || !is_integer($num_code))
        {
            return 'Occured error with given date or currency code';
        }

        return Kurs::find()->where(['<=', 'date', $date])->andWhere(['num_code' => $num_code])->orderBy(['date' => SORT_DESC])->one();
    }
}
