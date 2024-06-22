<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $bot app\models\Bot */

$this->title = 'Бот '. $bot->name;
?>

<h1><?= Html::encode($bot->name)?></h1>

<p>Описание бота: <?= $bot->description?></p>

<p>ID бота: <?= $bot->id?></p>

<p>
    <?= Html::a('Удалить бота', ['delete-bot', 'id' => $bot->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить бота?',
            'ethod' => 'post',
        ],
    ])?>
</p>