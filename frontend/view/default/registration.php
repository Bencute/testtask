<?php

use frontend\form\FormRegistration;
use system\helper\FormHtml;

/**
 * @var View $this
 * @var FormRegistration $form
 * @var array $countries
 */
?>
<div>
    <h1 class="text-center mb-3">
        <?=Sys::mId('app', 'registration')?>
    </h1>

    <p class="lead text-center">
        <?=Sys::mId('app', 'descRegistrationPage')?>
    </p>

    <form action="/registration" method="post" enctype="multipart/form-data"
          class="js-needs-validation form-registration px-3" novalidate
          data-validate-rules='<?=FormHtml::generateJSONValidateParams($form)?>'>

        <?=FormHtml::csrfInput()?>

        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelName')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('name')?>" value="<?=$form->name?>" type="text"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('name'))?>"
                       id="inputName" placeholder="<?=Sys::mId('app', 'inputLabelName')?>" required>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('name'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelEmail')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('email')?>" value="<?=$form->email?>" type="email"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('email'))?>"
                       id="inputEmail" placeholder="<?=Sys::mId('app', 'placeholderEmail')?>" required>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('email'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputCountry" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelCountry')?>
            </label>
            <div class="col-sm-8 px-0">
                <select name="<?=$form->getAttributeFormName('country')?>"
                        class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('country'))?>"
                        id="inputCountry">
                    <?=FormHtml::generateSelectOptions($countries, $form->country)?>
                </select>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('country'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputDateBirth" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelDateBirthday')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('dateBirthday')?>" type="date" max="<?=date("Y-m-d")?>"
                       value="<?=date("Y-m-d")?>"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('dateBirthday'))?>"
                       id="inputDateBirth">
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('dateBirthday'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputDesc" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelDescription')?>
            </label>
            <div class="col-sm-8 px-0">
                <textarea name="<?=$form->getAttributeFormName('description')?>" rows="3"
                          class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('description'))?>"
                          id="inputDesc" placeholder="<?=Sys::mId('app', 'inputLabelDescription')?>">
                    <?=$form->description?>
                </textarea>
                <small id="emailHelp" class="form-text text-muted">
                    <?=Sys::mId('app', 'inputNoteDescription')?>
                </small>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('description'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputAvatar" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelAvatar')?>
            </label>
            <div class="col-sm-8 px-0">
                <input type="file" name="<?=$form->getAttributeFormName('avatar')?>" accept="image/*"
                       class="form-control-file" id="inputAvatar">
                <small id="emailHelp" class="form-text text-muted">
                    <?=Sys::mId('app', 'inputNoteAvatar')?>
                </small>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('avatar'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelPassword')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('password')?>" value="<?=$form->password?>" type="password"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('password'))?>"
                       id="inputPassword" placeholder="<?=Sys::mId('app', 'inputLabelPassword')?>" required>
                <small id="emailHelp" class="form-text text-muted">
                    <?=Sys::mId('app', 'inputNotePassword')?>
                </small>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('password'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputPasswordRepeat" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelRepeatPassword')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('repeatPassword')?>" value="<?=$form->repeatPassword?>"
                       type="password"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('repeatPassword'))?>"
                       id="inputPasswordRepeat" placeholder="<?=Sys::mId('app', 'inputLabelRepeatPassword')?>" required>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('repeatPassword'))?>
            </div>
        </div>

        <div class="form-group row  justify-content-center">
            <button type="submit" formnovalidate class="btn btn-primary btn-block col-sm-6">
                <?=Sys::mId('app', 'registration')?>
            </button>
        </div>
    </form>
</div>
