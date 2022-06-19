<?php

namespace app\widgets\rate;

use yii\base\Widget;

class Rate extends Widget
{
    public $time;

    public function init()
    {
        parent::init();
        if ($this->time === null) {
            if(is_file('data/time.json')){
                $file = json_decode(file_get_contents('data/time.json'), true);
                $this->time = $file['time'] * 60000;
            }
            else
                $this->time = 30 * 60000;
        }else
            $this->time *= 60000;
    }

    public function run()
    {
        $time = $this->time;

         return $this->render('index',[
             'time' => $time,
             'id' => $this->getId()
         ]) ;
    }
}