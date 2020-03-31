<?php


namespace system\validator;


/**
 * Class ValidatorResponse
 * Определяет ответ о состоянии валидности
 *
 * @package system\validator
 */
class ValidatorResponse
{
    /**
     * Валидный ли ответ
     *
     * @var bool
     */
    public bool $isValid;

    /**
     * @var string|null
     */
    public ?string $message;

    /**
     * ValidatorResponse constructor.
     * @param bool $isValid
     * @param string $message
     */
    public function __construct(bool $isValid, string $message = null)
    {
        $this->isValid = $isValid;
        $this->message = $message;
    }
}