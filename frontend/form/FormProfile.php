<?php


namespace frontend\form;


use Sys;
use system\general\LoadAttributesTrait;
use system\web\FormTypeFile;
use system\web\Form;

/**
 * Class FormProfile
 * @package frontend\form
 */
class FormProfile extends Form
{
    use LoadAttributesTrait;

    public ?string $name = '';
    public ?string $country = '';
    public ?FormTypeFile $avatar = null;
    public ?string $password = null;
    public ?string $dateBirthday = null;
    public ?string $description = null;

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            ['name', 'vRequire'],
            ['name', 'vLength', 'params' => ['min' => 3, 'max' => 255]],

            ['password', 'vRequire'],
            ['password', 'vLength', 'params' => ['min' => 1, 'max' => 20]],

            ['country', 'vIn', 'params' => [
                'values' => array_keys(Sys::getApp()->getCountries()),
            ]],

            ['dateBirthday', 'vDate', 'params' => ['maxDate' => date("Y-m-d")]],

            ['description', 'vLength', 'params' => ['max' => 1000]],

            ['avatar', 'vMimeType', 'params' => [
                'mimeTypes'  => ['image/*'],
                'extensions' => ['jpg', 'jpeg', 'png', 'bmp'],
            ]],

            ['avatar', 'vFileSize', 'params' => [
                'maxFileSize' => 2_097_152, // 2 Mb
            ]],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return [
            'name',
            'country',
            'avatar',
            'password',
            'dateBirthday',
            'description',
        ];
    }
}