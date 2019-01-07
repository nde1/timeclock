<?php

use app\widgets\confirm\Confirm;
use app\widgets\modal\Clock;
use app\widgets\note\Note;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $session \app\models\Clock */
/* @var $user \app\models\User */

$this->title = 'Timeclock';
?>
<h1><?= date('Y-m-d') ?></h1>

<div class="row">
    <div class="col-sm-4">
        <?php if ($user->isClockActive()): ?>
            <div class="form-group">
                <?= Yii::t('app', 'Session started at {time}', ['time' => Yii::$app->formatter->asTime($user->sessionStartedAt())]) ?>
            </div>
            <div class="form-group">
                <a href="<?= Url::to(['clock/stop']) ?>" class="btn btn-danger btn-lg btn-block clock" <?= Confirm::ask(Yii::t('app', 'Are you sure you want to end this session?')) ?>>
                    <i class="glyphicon glyphicon-stop"></i>
                    <?= Yii::t('app', 'End Session') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="form-group">
                <?= Clock::button() ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-sm-8">
        <div class="form-group">
            <?= Yii::t('app', 'Today Sessions') ?>:
        </div>
        <?php
        $todays = $user->todaysSessions();
        $now = time();
        if ($todays): ?>
            <div class="form-group">
                <ul class="list-group">
                    <?php foreach ($todays as $session): ?>
                        <li class="list-group-item">
                            <?= Note::widget(['model' => $session]) ?>
                            <?= Yii::$app->formatter->asTime($session->clock_in) ?>
                            <i class="glyphicon glyphicon-arrow-right"></i>
                            <?php if ($session->clock_out !== null): ?>
                                <?= Yii::$app->formatter->asTime($session->clock_out) ?>
                                <a href="<?= Url::to(['clock/edit', 'id' => $session->id]) ?>" class="btn btn-warning btn-xs">
                                    <i class="glyphicon glyphicon-time"></i> <?= Yii::t('app', 'edit') ?>
                                </a>
                                <span class="badge"><?= Yii::$app->formatter->asDuration($session->clock_out - $session->clock_in) ?></span>
                            <?php else: ?>
                                <?= Yii::t('app', 'on-going') ?>
                                <a href="<?= Url::to(['clock/edit', 'id' => $session->id]) ?>" class="btn btn-success btn-xs">
                                    <i class="glyphicon glyphicon-time"></i> <?= Yii::t('app', 'edit') ?>
                                </a>
                                <span class="badge"><?= Yii::$app->formatter->asDuration($now - $session->clock_in) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="form-group"><?= Yii::t('app', 'NONE') ?></div>
        <?php endif; ?>
        <?php $oldestOpened = $user->getOldOpenedSession(); if ($oldestOpened): ?>
            <div class="form-group">
                <a href="<?= Url::to([
                    'clock/history',
                    'm' => Yii::$app->formatter->asDate($oldestOpened->clock_in, 'M'),
                    'y' => Yii::$app->formatter->asDate($oldestOpened->clock_in, 'y'),
                ]) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?= Yii::t('app', 'Old sessions have not been ended') ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>
