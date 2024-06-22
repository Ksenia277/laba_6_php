<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Answer */
/* @var $questions app\models\Question[] */

?>

<h1>Создать ответ на вопрос</h1>

<?php $form = ActiveForm::begin();?>

    <?= Html::dropDownList('question_id', null, \yii\helpers\ArrayHelper::map($questions, 'id', 'text'), ['prompt' => 'Выберите вопрос'])?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6])?>

<div class="form-group">
    <?= Html::submitButton('Создать ответ', ['class' => 'btn btn-success'])?>
</div>

<?php ActiveForm::end();?>