<?php

use frontend\form\FormProfile;
use frontend\model\User;
use system\helper\FormHtml;
use system\view\View;

/**
 * @var View $this
 * @var User $user
 * @var FormProfile $form
 * @var array $countries
 */

?>
<form action="/profile" method="post" enctype="multipart/form-data" class="js-needs-validation form-profile px-3"
      data-validate-rules='<?=FormHtml::generateJSONValidateParams($form)?>'>

    <?=FormHtml::csrfInput()?>

    <div class="form-row pt-3 mb-3">
        <?php if (!is_null($user->getAvatarImage())) { ?>
            <div class="form-group col-md-6">
                <img style="max-height: 200px;" class="img-fliud  mx-auto d-block mb-1 img-thumbnail"
                     src="<?=$user->getAvatarImage()->getUrlPath()?>">
                <div class="row justify-content-center">
                    <a data-method="post" data-confirm="<?=Sys::mId('app', 'confirmDeleteAvatar')?>"
                       href="/delete-avatar" class="btn btn-sm btn-secondary mx-3">
                        <?=Sys::mId('app', 'buttonDeleteAvatar')?>
                    </a>
                </div>
            </div>
        <?php } ?>

        <div class="form-group col-md-6 pl-md-3 mb-0 small mx-md-auto">
            <div class="form-group row mb-0">
                <label for="inputDateCreate" class="col col-form-label py-0">
                    <?=Sys::mId('app', 'inputLabelDateRegistration')?>
                </label>
                <div class="col-auto my-auto">
                    <input value="<?=$user->dateCreate?>" readonly class="form-control-plaintext py-0"
                           id="inputDateCreate">
                </div>
            </div>

            <div class="form-group row mb-0 pt-2">
                <label for="inputDateUpdate" class="col col-form-label py-0">
                    <?=Sys::mId('app', 'inputLabelDateUpdateProfile')?>
                </label>
                <div class="col-auto my-auto">
                    <input value="<?=$user->dateUpdate?>" readonly class="form-control-plaintext py-0"
                           id="inputDateUpdate">
                </div>
            </div>

            <div class="form-group row mb-0 pt-2">
                <label for="inputID" class="col col-form-label py-0">
                    <?=Sys::mId('app', 'inputLabelID')?>
                </label>
                <div class="col-auto my-auto">
                    <input value="<?=$user->getId()?>" readonly class="form-control-plaintext py-0" id="inputID">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">
            <?=Sys::mId('app', 'inputLabelName')?>
        </label>
        <div class="col-sm-8 px-0">
            <input name="<?=$form->getAttributeFormName('name')?>" value="<?=$form->name?>" type="text"
                   class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('name'))?>" id="inputName"
                   placeholder="<?=Sys::mId('app', 'inputLabelName')?>" required>
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('name'))?>
        </div>
    </div>

    <div class="form-group row">
        <label for="inputEmail" class="col-sm-4 col-form-label">
            <?=Sys::mId('app', 'inputLabelEmail')?>
        </label>
        <div class="col-sm-8 px-0">
            <input value="<?=$user->email?>" readonly type="email" class="form-control" id="inputEmail"
                   placeholder="<?=Sys::mId('app', 'inputLabelEmail')?>">
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
                   value="<?=$form->dateBirthday?>"
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
                   class="form-control-file <?=FormHtml::stateInValidClass($form->isAttributeError('avatar'))?>"
                   id="inputAvatar">
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
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('password'))?>
        </div>
    </div>

    <div class="form-group row  justify-content-center">
        <button type="submit" formnovalidate class="btn btn-primary btn-block col-sm-6 mb-3 mb-sm-0">
            <?=Sys::mId('app', 'nameButtonSaveFormProfile',)?>
        </button>

        <a href="/delete-profile" data-method="post" class="btn btn-danger ml-sm-3"
           data-confirm="<?=Sys::mId('app', 'confirmDeleteProfile')?>">
            <?=Sys::mId('app', 'nameButtonDeleteProfile',)?>
        </a>
    </div>
</form>