<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
?>

<h1><?= Html::encode($this->title)?></h1>

<?php $form = ActiveForm::begin();?>

<?= $form->field($model, 'name')->label('Имя')?>
<?= $form->field($model, 'username')->label('Логин')?>
<?= $form->field($model, 'email')->label('Электронная почта')?>
<?= $form->field($model, 'password')->passwordInput()->label('Пароль')?>


<div class="form-group">
    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary'])?>
</div>

<?php ActiveForm::end();?>
