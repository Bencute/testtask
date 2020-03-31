<?php

use frontend\form\FormProfile;
use frontend\model\User;
use system\view\View;

/**
 * @var View $this
 * @var User $user
 * @var FormProfile $form
 * @var array $countries
 */
?>

<div>
    <h1 class="text-center">
        <?=Sys::mId('app', 'profile')?>
    </h1>

    <?=$this->renderView('_formProfile', ['user' => $user, 'form' => $form, 'countries' => $countries])?>
</div>
