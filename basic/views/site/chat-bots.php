<?php use yii\helpers\Url; ?>

<h1>Чат с ботами</h1>

<ul>
    <?php foreach ($bots as $bot):?>
        <li>
            <a href="<?= Url::to(['/site/chat', 'bot_id' => $bot->id])?>">
                <?= $bot->name?> (<?= $bot->description?>)
            </a>
        </li>
    <?php endforeach;?>
</ul>