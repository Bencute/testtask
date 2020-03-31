<?php


namespace system\web;


use system\interfaces\FormFile;

/**
 * Class FormTypeFile
 * Класс для загрузки файлов из формы HTML
 *
 * @package system\web
 */
class FormTypeFile implements FormFile
{
    /**
     * @var array UploadFile
     */
    public array $files = [];

    /**
     * Парсит массив _FILES в $files
     *
     * @param $formName
     * @param $attributeName
     */
    public function load($formName, $attributeName): void
    {
        if (isset($_FILES[$formName])) {
            $aImages = $_FILES[$formName];
            if (is_array($aImages['name'][$attributeName])) {
                foreach ($aImages['name'][$attributeName] as $key => $imageName) {
                    if (!empty($imageName)) {
                        $this->files[] = new UploadFile(
                            $imageName,
                            $aImages['type'][$attributeName][$key],
                            $aImages['tmp_name'][$attributeName][$key],
                            $aImages['error'][$attributeName][$key],
                            $aImages['size'][$attributeName][$key],
                        );
                    }
                }
            } else {
                if (!empty($aImages['name'][$attributeName])) {
                    $this->files[] = new UploadFile(
                        $aImages['name'][$attributeName],
                        $aImages['type'][$attributeName],
                        $aImages['tmp_name'][$attributeName],
                        $aImages['error'][$attributeName],
                        $aImages['size'][$attributeName],
                    );
                }
            }
        }
    }
}