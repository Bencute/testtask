<?php


namespace system\web;


use ReflectionClass;
use ReflectionProperty;
use system\validator\ValidatorsTrait;

/**
 * Class Form
 * @package system\web
 */
abstract class Form
{
    use ValidatorsTrait;

    /**
     * @var string
     */
    public string $typeImageIterface = '\system\interfaces\FormFile';

    /**
     * @var array
     */
    public array $errors = [];

    /**
     * @var array
     */
    public array $validatedAttributes = [];

    /**
     * @throws \ReflectionException
     * @return string
     */
    public function getFormName(): string
    {
        return (new ReflectionClass(get_class($this)))->getShortName();
    }

    /**
     * @param string $attribute
     * @throws \ReflectionException
     * @return string
     */
    public function getAttributeFormName(string $attribute): string
    {
        return $this->getFormName() . '[' . $attribute . ']';
    }

    /**
     * @param array $values
     * @throws \ReflectionException
     */
    public function load(array $values): void
    {
        if (isset($values[$this->getFormName()])) {
            $values = $values[$this->getFormName()];
            $attributes = $this->getAttributes();
            foreach ($attributes as $attribute) {
                $prop = new ReflectionProperty($this, $attribute);
                $typeProp = $prop->getType();
                if ($typeProp) {
                    if ($typeProp->isBuiltin()) {
                        if (isset($values[$attribute])) {
                            switch ($typeProp->getName()) {
                                case 'bool':
                                    $this->$attribute = true;
                                    break;
                                default:
                                    $this->$attribute = $values[$attribute];
                                    break;
                            }
                        }
                    } else {
                        $nameClass = $typeProp->getName();
                        $rc = new ReflectionClass($nameClass);
                        if ($rc->implementsInterface($this->typeImageIterface)) {
                            $this->$attribute = new $nameClass();
                            $this->$attribute->load($this->getFormName(), $attribute);
                        }
                    }
                } elseif (isset($values[$attribute])) {
                    $this->$attribute = $values[$attribute];
                }
            }
        }
    }

    /**
     * Return format: [
     *      nameAttribute1, validatorMethod,
     *      nameAttribute2, validatorMethod,
     *      ...
     * ]
     *
     * @return array
     */
    abstract public function getRules(): array;

    /**
     * @return array
     */
    abstract public function getAttributes(): array;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->flushValidate())
            return false;

        $rules = $this->getRules();
        foreach ($rules as $rule) {
            $nameAttribute = $rule[0];
            $nameRuleMethod = $rule[1];
            $ruleParams = $rule['params'] ?? [];

            if ($this->$nameAttribute instanceof FormTypeFile) {
                foreach ($this->$nameAttribute->files as $file) {
                    $validateResponse = $this->$nameRuleMethod($nameAttribute, $file, $ruleParams);
                    if (!$validateResponse->isValid) {
                        $this->addError($nameAttribute, $validateResponse->message);
                    }
                }
                $this->addValidatedAttributes($nameAttribute);
            } else {
                $validateResponse = $this->$nameRuleMethod($nameAttribute, $this->$nameAttribute, $ruleParams);
                if (!$validateResponse->isValid) {
                    $this->addError($nameAttribute, $validateResponse->message);
                }
                $this->addValidatedAttributes($nameAttribute);
            }
        }

        return !$this->hasErrors();
    }

    /**
     * @param string $nameAttribute
     * @param string $message
     * @return bool
     */
    public function addError(string $nameAttribute, string $message): bool
    {
        $this->errors[$nameAttribute][] = $message;
        return true;
    }

    /**
     * @return bool
     */
    private function flushErrors(): bool
    {
        $this->errors = [];
        return true;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @param string $nameAttribute
     * @return bool|null
     */
    public function isAttributeError(string $nameAttribute): ?bool
    {
        // Проверка на участие атрибута в валидации
        if (array_search($nameAttribute, $this->getValidateAttributes()) === false)
            return null;

        // Проверка был ли провалидирован атрибут
        if (array_search($nameAttribute, $this->validatedAttributes) === false)
            return null;

        return isset($this->errors[$nameAttribute]);
    }

    /**
     * @param string $nameAttribute
     * @return array
     */
    public function getAttributeMessageErrors(string $nameAttribute): array
    {
        return $this->errors[$nameAttribute] ?? [];
    }

    /**
     * @return array
     */
    public function getValidateAttributes(): array
    {
        return array_map(fn($rule) => $rule[0], $this->getRules());
    }

    /**
     * @param string $nameAttribute
     * @return bool
     */
    public function addValidatedAttributes(string $nameAttribute): bool
    {
        $this->validatedAttributes[] = $nameAttribute;
        return true;
    }

    /**
     * @return bool
     */
    private function flushValidatedAttributes(): bool
    {
        $this->validatedAttributes = [];
        return true;
    }

    /**
     * @return bool
     */
    public function flushValidate(): bool
    {
        return $this->flushErrors() && $this->flushValidatedAttributes();
    }
}