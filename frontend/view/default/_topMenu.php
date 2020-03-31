<?php
/**
 * @var View $this
 */
?>
<div class="d-flex flex-column flex-sm-row align-items-center pt-0 pb-3 px-3 pt-sm-3 px-sm-4 mb-3 bg-white border-bottom shadow-sm">
    <a href="/" class="btn text-dark mr-sm-auto font-weight-normal">
        <h5 class="my-0">
            <?=Sys::mId('app', 'nameSite')?>
        </h5>
    </a>
    <?php if (!Sys::getApp()->getWebUser()->isGuest()) { ?>
        <nav class="mb-2 my-sm-0 mr-sm-3">
            <a class="p-sm-2 text-dark" href="/profile">
                <?=Sys::mId('app', 'profile')?>
            </a>
        </nav>
    <?php } ?>
    <?php if (Sys::getApp()->getWebUser()->isGuest()) { ?>
        <a class="btn btn-primary mr-sm-2 mb-2 mb-sm-0" href="/login">
            <?=Sys::mId('app', 'login')?>
        </a>
        <a class="btn btn-primary" href="/registration">
            <?=Sys::mId('app', 'registration')?>
        </a>
    <?php } ?>
    <?php if (!Sys::getApp()->getWebUser()->isGuest()) { ?>
        <a class="btn btn-primary" data-method="post" href="/logout">
            <?=Sys::mId('app', 'logout')?>
        </a>
    <?php } ?>
</div>
