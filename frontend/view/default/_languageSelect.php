<?php

use frontend\helper\FormHtmlLanguage;

/**
 * @var View $this
 */
?>
<div class="d-flex justify-content-end px-3">
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
            <?=FormHtmlLanguage::getCurrentNameLanguage()?>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <?php foreach (FormHtmlLanguage::getListSupportLanguages() as $language) { ?>
                <?php if (FormHtmlLanguage::getCurrentLanguage() === $language) { ?>
                    <a class="dropdown-item active" href="<?=FormHtmlLanguage::getLink($language)?>">
                        <?=FormHtmlLanguage::getNameLanguage($language)?>
                    </a>
                <?php } else { ?>
                    <a class="dropdown-item" href="<?=FormHtmlLanguage::getLink($language)?>">
                        <?=FormHtmlLanguage::getNameLanguage($language)?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
