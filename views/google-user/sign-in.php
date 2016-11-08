<?php

use yii\helpers\Html;
use vip9008\materialgrid\assets\AdminAsset;

$this->title = 'Sign in';

$material = AdminAsset::register($this);
?>
<div class="top-logo"></div>
<div class="banner">
    <h1>One account. All the services.</h1>
    <h3 class="hidden-sm">Sign in to continue</h3>
</div>
<div class="sign-in">
    <div class="img"><img src="<?= $material->baseUrl."/img/google.png" ?>" /></div>
    <?= Html::a('Sign in with Google', ['sign-in'], ['class' => 'action-button']) ?>
    <div class="footer">
        <a href="https://github.com/vip9008/yii2-googleuser" target="_blank">Need help?</a>
    </div>
</div>
