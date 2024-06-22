<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $bot app\models\Bot */

$this->title = 'Создать бота';
?>

<h1><?= Html::encode($this->title)?></h1>

<?php $form = ActiveForm::begin();?>

    <?= $form->field($bot, 'name')->label('Имя бота')?>

    <?= $form->field($bot, 'description')->textarea(['rows' => 6])->label('Описание бота')?>

<div class="form-group">
    <?= Html::submitButton('Создать бота', ['class' => 'btn btn-success'])?>
</div>

<?php ActiveForm::end();?>