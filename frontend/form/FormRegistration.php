<?php


namespace frontend\form;


use Sys;
use system\general\LoadAttributesTrait;
use system\web\FormTypeFile;
use system\web\Form;

/**
 * Class FormRegistration
 * @package frontend\form
 */
class FormRegistration extends Form
{
    use LoadAttributesTrait;

    public ?string $name = '';
    public ?string $email = '';
    public ?string $country = '';
    public ?FormTypeFile $avatar = null;
    public ?string $password = null;
    public ?string $repeatPassword = null;
    public ?string $dateBirthday = null;
    public ?string $description = null;

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            ['email', 'vRequire'],
            ['email', 'vEmail'],
            ['email', 'vLength', 'params' => ['max' => 64]],
            ['email', 'vUnique', 'params' => [
                'message'   => 'errorValidationFormRegistrationEmailExist',
                'model'     => 'frontend\model\User',
                'attribute' => 'email',
            ]],

            ['password', 'vRequire'],
            ['password', 'vLength', 'params' => ['min' => 3, 'max' => 20]],
            ['repeatPassword', 'vRequire'],
            ['repeatPassword', 'vCompare', 'params' => [
                'message'          => 'errorValidationFormRegistrationRepeatPasswordNotCompare',
                'compareAttribute' => 'password',
            ]],

            ['name', 'vRequire'],
            ['name', 'vLength', 'params' => ['min' => 3, 'max' => 255]],
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
            'email',
            'country',
            'avatar',
            'password',
            'repeatPassword',
            'dateBirthday',
            'description',
        ];
    }
}