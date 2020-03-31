<?php

use frontend\helper\FormHtmlLanguage;

/**
 * @var View $this
 */
?>
<?php foreach (array_merge(
                   array_values(Sys::getApp()->getFlashesGroup('successes')),
                   array_values(Sys::getApp()->getWebUser()->getFlashesGroup('successes'))) as $msg) { ?>
    <div class="alert alert-success" role="alert">
        <?=$msg?>
    </div>
<?php } ?>

<?php foreach (array_merge(
                   array_values(Sys::getApp()->getFlashesGroup('errors')),
                   array_values(Sys::getApp()->getWebUser()->getFlashesGroup('errors'))) as $msg) { ?>
    <div class="alert alert-danger" role="alert">
        <?=$msg?>
    </div>
<?php } ?>
