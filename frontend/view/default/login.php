<?php

use frontend\form\FormRegistration;
use system\helper\FormHtml;

/**
 * @var View $this
 * @var FormRegistration $form
 */
?>
<div class="text-center">
    <h1>
        <?=Sys::mId('app', 'login')?>
    </h1>

    <form method="post" action="/login" class="form-login js-needs-validation" novalidate
          data-validate-rules='<?=FormHtml::generateJSONValidateParams($form)?>'>

        <?=FormHtml::csrfInput()?>

        <div class="form-group">
            <input name="<?=$form->getAttributeFormName('email')?>" type="email"
                   class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('email'))?>"
                   id="inputEmail" placeholder="<?=Sys::mId('app', 'placeholderEmail')?>" value="<?=$form->email?>"
                   required>
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('email'))?>
        </div>

        <div class="form-group">
            <input name="<?=$form->getAttributeFormName('password')?>" type="password"
                   class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('password'))?>"
                   id="inputPassword" placeholder="<?=Sys::mId('app', 'placeholderPassword')?>" required>
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('password'))?>
        </div>

        <div class="form-group custom-control custom-checkbox p-0">
            <input name="<?=$form->getAttributeFormName('remember')?>" <?=FormHtml::boolToChecked($form->remember)?>
                   type="checkbox"
                   class="custom-control-input <?=FormHtml::stateInValidClass($form->isAttributeError('remember'))?>"
                   id="inputRemember" value="0">
            <label class="custom-control-label" for="inputRemember">
                <?=Sys::mId('app', 'inputLabelRememberMe')?>
            </label>
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('remember'))?>
        </div>

        <button type="submit" formnovalidate class="btn btn-primary btn-block">
            <?=Sys::mId('app', 'login')?>
        </button>
    </form>

    <p>
        <a href="/registration">
            <?=Sys::mId('app', 'registration')?>
        </a>
    </p>
</div>
