<?php


namespace system\interfaces;


/**
 * Interface FormFile
 * Интерфейс для загрузки файлов из формы HTML
 *
 * @package system\interfaces
 */
interface FormFile
{
    /**
     * @param $formName
     * @param $attributeName
     */
    public function load($formName, $attributeName): void;
}