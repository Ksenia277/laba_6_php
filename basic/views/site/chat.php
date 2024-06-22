<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $bot app\models\Bot */
/* @var $messages app\models\Message[] */
/* @var $message app\models\Message */

?>

<h1>Чат с ботом <?= $bot->name?></h1>

<?php $form = ActiveForm::begin(['action' => ['chat', 'bot_id' => $bot->id]]);?>

<?= $form->field($model, 'text')->textarea(['rows' => 6])->label('Ваше сообщение')?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary'])?>
    </div>

<?php ActiveForm::end();?>

<div class="messages">
    <?php foreach ($messages as $item):?>
        <div class="message">
            <strong><?= $item->type === 'вопрос'? 'Вопрос' : 'Ответ'?> <?= $item->author?>:</strong>
            <?= $item->text?>
        </div>
    <?php endforeach;?>
</div>