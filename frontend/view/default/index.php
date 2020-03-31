<?php

use \system\helper\Html;

/**
 * @var View $this
 * @var bool $isGuest
 * @var string $username
 */
?>
<div class="text-center">
    <?php if ($isGuest) { ?>
        <h1>
            <?=Sys::mId('app', 'hellowGuest')?>
        </h1>
        <p class="lead">
            <?=Sys::mId('app', 'selectDo')?>
        </p>
        <p>
            <a href="/login">
                <?=Sys::mId('app', 'login')?>
            </a> | <a href="/registration">
                <?=Sys::mId('app', 'registration')?>
            </a>
        </p>
    <?php } else { ?>
        <h1>
            <?=Sys::mId('app', 'hellowUsername', ['username' => Html::encode($username)])?>
        </h1>
        <p class="lead">
            <?=Sys::mId('app', 'selectDo')?>
        </p>
        <p>
            <a href="/profile">
                <?=Sys::mId('app', 'editProfile')?>
            </a>
        </p>
    <?php } ?>
</div>
