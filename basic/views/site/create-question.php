<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $question app\models\Question */
/* @var $bot app\models\Bot */
/* @var $answer app\models\Answer */
?>

<h1>Создать вопрос для бота</h1>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($question, 'bot_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($bots, 'id', 'name'),
        ['prompt' => 'Выберите бота']
    ) ?>

    <?= $form->field($question, 'text')->textarea(['rows' => 6])->label('Текст') ?>


<div class="form-group">
    <?= Html::submitButton('Создать вопрос', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>