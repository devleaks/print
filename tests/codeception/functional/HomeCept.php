<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Labo JJ Micheli');
$I->seeLink('About');
$I->click('About');
$I->see('This is the About page.');
