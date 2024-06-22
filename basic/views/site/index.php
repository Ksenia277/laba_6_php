<?php

/** @var yii\web\View $this */

use common\models\User; 
use yii\web\ForbiddenHttpException;

$this->title = 'Мое приложение Yii';

?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">

        <h1>Bots</h1>


            <?php if (isset($bots) &&!empty($bots)):?>
                    <?php foreach ($bots as $bot):?>
                        <p>
                            <?= \yii\helpers\Html::a($bot->name, ['view-bot', 'id' => $bot->id])?>
                        </p>
                    <?php endforeach;?>
            <?php else:?>
                <p>No bots found.</p>
            <?php endif;?>

            <p><a class="btn btn-lg btn-success" href="<?php echo \yii\helpers\Url::to(['create-bot'])?>">Создать бота &raquo;</a></p>

    </div>
</div>