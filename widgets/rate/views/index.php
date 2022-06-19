<?php

/** @var yii\web\View $this */
/** @var $dataProvider */
/** @var $time */
/** @var $id */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);

?>

<html lang="en">
<head>
    <script src="<?= Yii::getAlias('@web').'/js/rate.js'?>"></script>
    <script>
        (function($)
        {
            $(document).ready(function()
            {
                $.ajaxSetup(
                    {
                        cache: false,
                        beforeSend: function() {
                            $(".content<?=$id?>").hide();
                            $(".loading<?=$id?>").show();
                        },
                        complete: function() {
                            $(".loading<?=$id?>").hide();
                            $(".content<?=$id?>").show();
                        },
                        success: function() {
                            $(".loading<?=$id?>").hide();
                            $(".content<?=$id?>").show();
                        }
                    });

                var $container = $(".content<?=$id?>");

                function helper(){
                    $container.load("<?= Yii::$app->getUrlManager()->createUrl(['kurs/rate-widget']);?>");
                    const d = new Date();
                    let time = d.getTime();
                    d.setTime(time + <?= $time?>);
                    $(".time<?=$id?>").text(
                        'Следующее обновление в ' +
                        ("0" + d.getHours()).slice(-2) + ":" +
                        ("0" + d.getMinutes()).slice(-2) + ":" +
                        ("0" + d.getSeconds()).slice(-2));
                }

                let a = helper();

                var refreshId = setInterval(function()
                {
                    let a = helper();
                }, <?= $time?>);
            });
        })(jQuery);
    </script>
    <title></title>
</head>
<body>

<div id="wrapper">
    <p class="time<?=$id?> text-right"></p>
    <div class="content<?=$id?>"></div>
    <p class="w-100 text-center small text-muted">
        <?=Html::img(Yii::getAlias('@web').'/img/loading.gif',['class'=>"loading{$id}"])?>
    </p>

</div>

</body>
</html>


