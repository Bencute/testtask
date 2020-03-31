<?php


namespace system\helper;


use ReflectionException;
use Sys;
use system\web\Form;

/**
 * Class FormHtml
 * @package system\helper
 */
class FormHtml
{
    /**
     * @param bool|null $state
     * @return string
     */
    public static function stateInValidClass(?bool $state): string
    {
        return is_null($state) ? '' : ($state ? 'is-invalid' : 'is-valid');
    }

    /**
     * @param array $errorsMessage
     * @param string $glue
     * @return string
     */
    public static function getMessageErrors(array $errorsMessage, string $glue = '<br>'): string
    {
        $formatMessages = [];
        foreach ($errorsMessage as $msg)
            $formatMessages[] = Sys::mId('app', $msg);

        return '<div class="invalid-feedback">' . implode($glue, $formatMessages) . '</div>';
    }

    /**
     * @param bool $state
     * @return string
     */
    public static function boolToChecked(bool $state): string
    {
        return $state ? 'CHECKED' : '';
    }

    /**
     * @param bool $state
     * @return string
     */
    public static function boolToSelected(bool $state): string
    {
        return $state ? 'SELECTED' : '';
    }

    /**
     * @param array $values
     * @param string $current
     * @return string
     */
    public static function generateSelectOptions(array $values, string $current): string
    {
        $output = '';

        foreach ($values as $key => $name) {
            $selected = self::boolToSelected($key == $current);
            $output .= "<option value=\"$key\" $selected>$name</option>";
        }
        return $output;
    }

    /**
     * Генерирует настройки валидации формы для пременния на странице в браузере js скриптом
     *
     * @param Form $form
     * @throws ReflectionException
     * @return string
     */
    public static function generateJSONValidateParams(Form $form): string
    {
        $rules = $form->getRules();
        $attrib = [];
        foreach ($rules as $rule) {
            $attribRule = $rule;
            $message = null;
            if (isset($rule['params']['compareAttribute'])) {
                $attribRule['compareAttribute'] = $form->getAttributeFormName($rule['params']['compareAttribute']);
            }

            if (isset($rule['params']['message'])) {
                $attribRule['message'] = Sys::mId('app', $rule['params']['message'], $rule['params'] ?? []);
            }
            if (isset($form->validatorsErrorMessages[$rule[1]])){
                if (is_array($form->validatorsErrorMessages[$rule[1]])) {
                    foreach ($form->validatorsErrorMessages[$rule[1]] as $nameMsg => $msg) {
                        $message[$nameMsg] = Sys::mId('app', $msg, $rule['params'] ?? []);
                    }
                    $attribRule['message'] = $message;
                } else {
                    $nameMsg = $form->validatorsErrorMessages[$rule[1]];
                    $attribRule['message'] = Sys::mId('app', $nameMsg, $rule['params'] ?? []);
                }
            }
            $attrib[$form->getAttributeFormName($rule[0])][] = $attribRule;
        }

        return json_encode($attrib, JSON_HEX_QUOT);
    }

    /**
     * @return string
     */
    public static function csrfInput(): string
    {
        return '<input type="hidden" name="' . Sys::getApp()->getWebUser()->getCsrfKey() . '" value="' . Sys::getApp()->getWebUser()->getCsrfToken() . '">';
    }
}