<?php


namespace system\db;


use Exception;
use PDOStatement;
use Sys;
use system\exception\DbException;

/**
 * Class MysqlQuery
 * @package system\db
 */
class MysqlQuery
{
    /**
     * @var array
     */
    public static array $defailtLimit = ['count' => 20];

    /**
     * $condition format: [
     *      'columns' => [
     *          nameColumn1 => value1,
     *          nameColumn2 => value2,
     *          ...
     *      ],
     *      'limit' => [
     *          'offset' => integer,
     *          'count' => integer,
     * ]
     *
     * @param string $tableName
     * @param array $condition
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function select(string $tableName, array $condition)
    {
        $params = self::generateParams($condition['columns']);
        $sqlParams = $params['paramsSql'];

        $sqlWhere = implode(', ', $params['strsSql']);

        $limit = $condition['limit'] ?? self::$defailtLimit;

        $sqlLimit = (int) $limit['count'];

        if (isset($limit['offset'])) {
            $sqlLimit = (int) $limit['offset'] . ', ' . $sqlLimit;
        }

        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $sqlWhere . ' LIMIT ' . $sqlLimit . ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ....
     * ]
     *
     * @param string $tableName
     * @param array $attributeValues
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function insert(string $tableName, array $attributeValues)
    {
        $params = self::generateParams($attributeValues);

        $sqlColumns = implode(',', array_keys($attributeValues));

        $sqlParamValues = implode(',', array_keys($params['paramsSql']));

        $sql = 'INSERT INTO ' . $tableName . ' (' . $sqlColumns . ') VALUES (' . $sqlParamValues . ');';

        $sqlParams = $params['paramsSql'];

        return self::execute($sql, $sqlParams);
    }

    /**
     * $attributeValues format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ...
     * ]
     *
     * @param string $tableName
     * @param string $namePrimaryKey
     * @param array $attributeValues
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function update(string $tableName, string $namePrimaryKey, array $attributeValues)
    {
        $params = self::generateParams($attributeValues);

        $strSqlParamsAttributes = implode(', ', $params['strsSql']);

        $sqlParams = $params['paramsSql'];

        $sqlParams[':id'] = $attributeValues[$namePrimaryKey];
        $sqlWhere = $namePrimaryKey . '=:id';

        $sql = 'UPDATE ' . $tableName . ' SET ' . $strSqlParamsAttributes . ' WHERE ' . $sqlWhere . ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * return format: [
     *          'values => [
     *          nameColumn1 => [
     *              'param' = nameParamValue1,
     *              'val' = value1,
     *              ...
     *          ],
     *          nameColumn2 => [
     *              'param' = nameParamValue2,
     *              'val' = value2,
     *              ...
     *          ],
     *          ......
     *      ],
     *      'strsSql' => [
     *          [0] => 'nameColumn1=nameParamValue1',
     *          [1] => 'nameColumn2=nameParamValue2',
     *          ...
     *      ]
     *      'paramsSql' => [
     *          nameParamValue1 => value1,
     *          nameParamValue2 => value2,
     *          ...
     *      ]
     * ]
     *
     * @param $values
     * @return array
     */
    private static function generateParams($values): array
    {
        $params = [];
        $i = 0;

        foreach ($values as $column => $value) {
            $nameParam = ':v' . $i;
            $params['values'][$column]['nameParam'] = $nameParam;
            $params['values'][$column]['value'] = $value;

            $params['strsSql'][] = $column . '=' . $nameParam;
            $params['paramsSql'][$nameParam] = $value;
            $i++;
        }

        return $params;
    }

    /**
     * $params format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * @param PDOStatement $query
     * @param array $params
     * @return bool
     */
    public static function bindParam(PDOStatement $query, array $params): bool
    {
        foreach ($params as $nameParam => $value) {
            if (!$query->bindValue($nameParam, $value))
                return false;
        }
        return true;
    }

    /**
     * Return false or \PDOStatement
     *
     * @param string $sql
     * @param array $params
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function execute(string $sql, array $params)
    {
        $db = Sys::getApp()->getDB();

        $query = $db->prepare($sql);

        if ($query === false)
            return false;

        if (!self::bindParam($query, $params))
            return false;

        $result = $query->execute();

        if (!$result)
            throw new DbException($query->errorInfo()[2]);

        //        $query->debugDumpParams();
        //        echo "\nPDOStatement::errorInfo():\n";
        //        $arr = $query->errorInfo();
        //        print_r($arr);

        return $result ? $query : $result;
    }

    /**
     * @param string $tableName
     * @param string $namePrimaryKey
     * @param $valuePrimaryKey
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function delete(string $tableName, string $namePrimaryKey, $valuePrimaryKey)
    {
        $sqlParams[':id'] = $valuePrimaryKey;
        $sqlWhere = $namePrimaryKey . '=:id';

        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $sqlWhere . ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * @param string $tableName
     * @param array $params
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function exist(string $tableName, array $params)
    {
        $params = self::generateParams($params);
        $sqlParams = $params['paramsSql'];

        $sqlWhere = implode(', ', $params['strsSql']);

        $sql = 'SELECT COUNT(*) FROM ' . $tableName . ' WHERE ' . $sqlWhere . ';';

        return self::execute($sql, $sqlParams);
    }
}