<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "money".
 *
 * @property int $id
 * @property string $name
 * @property string|null $char_code
 * @property int|null $num_code
 * @property int|null $in_kurs
 * @property int|null $in_widget
 */
class Money extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'money';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['num_code', 'in_kurs', 'in_widget'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['char_code'], 'string', 'max' => 6],
            [['num_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'char_code' => 'Char Code',
            'num_code' => 'Num Code',
            'in_kurs' => 'In Kurs',
            'in_widget' => 'In Widget',
        ];
    }

    public static function selectList(){
        $list = self::find()->select(['num_code', 'name'])->asArray()->all();
        return ArrayHelper::map($list, 'num_code', 'name');
    }
}
