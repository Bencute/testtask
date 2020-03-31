<?php


namespace system\db;


use Exception;
use PDO;
use Sys;
use system\general\LoadAttributesTrait;
use system\helper\TextCase;

/**
 * Class ActiveRecord
 * Поля таблицы определяются как свойства класса
 *
 * @package system\db
 */
abstract class ActiveRecord
{
    use LoadAttributesTrait;

    /**
     * Должно быть написано как в базе данных
     *
     * @return string
     */
    abstract public static function tableName(): string;

    /**
     * Первычный ключ
     * Должно быть написано как в базе данных
     *
     * @var string
     */
    public string $namePrimaryKey;

    /**
     * Флаг новый или уже записанный в базе экземпляр
     *
     * @var bool
     */
    public bool $isNew = true;

    /**
     * ActiveRecord constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $name => $value)
            if (property_exists($this, $name))
                $this->$name = $value;
    }

    /**
     * Сохраняет в базе данные из объекта
     *
     * @param bool $validate
     * @throws Exception
     * @return bool
     */
    public function save(bool $validate = true): bool
    {
        if ($validate && !$this->validate())
            return false;

        if ($this->isNew) {
            $result = $this->insert();
            if ($result)
                $this->isNew = false;
        } else {
            $result = $this->update();
        }

        return $result;
    }

    /**
     * Перечисление какие свойства сохранять в базе
     * Имена свойств преобразуются в snake_case стиль
     * Формат возврата: ['nameAttribute1', 'nameAttribute2', 'nameAttribute3', ....]
     *
     * @return array
     */
    abstract public function getAttributesSave(): array;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        // TODO do implementation
        return true;
    }

    /**
     * Возвращает массив перобразованных имен свойст и значений для записи в БД
     *
     * @return array
     */
    public function getAttributeValues(): array
    {
        $buf = [];
        foreach ($this->getAttributesSave() as $attribute) {
            $buf[TextCase::toSnakeCase($attribute)] = $this->$attribute;
        }
        return $buf;
    }

    /**
     * Вставляе в базу данных строку
     *
     * @throws Exception
     * @return bool
     */
    public function insert(): bool
    {
        $result = MysqlQuery::insert(static::tableName(), $this->getAttributeValues());
        if ($result)
            $this->{$this->namePrimaryKey} = Sys::getApp()->getDB()->lastInsertId();

        return (bool) $result;
    }

    /**
     * Обновляет в базе данных строку
     *
     * @throws Exception
     * @return bool
     */
    public function update(): bool
    {
        $saveAttributes = $this->getAttributeValues();

        if (!isset($saveAttributes[$this->namePrimaryKey]))
            $saveAttributes[$this->namePrimaryKey] = $this->{$this->namePrimaryKey};

        return (bool) MysqlQuery::update(static::tableName(), $this->namePrimaryKey, $saveAttributes);
    }

    /**
     * Ищет в базе данных по первичному ключу строку и возвращает объект заполненный значениями этой строки
     * или null если ничего не найдено
     *
     * @param array $condition
     * @throws Exception
     * @return static|null
     */
    public static function find(array $condition): ?self
    {
        $condition['columns'] = $condition;
        $condition['limit']['count'] = 1;

        $sqlResult = MysqlQuery::select(static::tableName(), $condition);

        if ($sqlResult === false)
            return null;

        $result = $sqlResult->fetch(PDO::FETCH_ASSOC);

        if (empty($result))
            return null;

        $params = [];
        foreach ($result as $nameAttr => $value) {
            $params[TextCase::toCamelCase($nameAttr)] = $value;
        }

        $user = new static($params);
        $user->isNew = false;

        return $user;
    }

    /**
     * Удаляет строку из базы данных по первичному ключу
     *
     * @return bool
     */
    public function delete(): bool
    {
        if ($this->isNew)
            return true;

        return (bool) MysqlQuery::delete(static::tableName(), $this->namePrimaryKey, $this->{$this->namePrimaryKey});
    }

    /**
     * Проверяет существует ли в базе данных строка с переданными параметрами
     *
     * @param array $params
     * @return bool
     */
    public static function exist(array $params): bool
    {
        $result = MysqlQuery::exist(static::tableName(), $params);
        if ($result === false)
            return false;

        $count = (int) $result->fetchColumn();

        return $count > 0;
    }
}