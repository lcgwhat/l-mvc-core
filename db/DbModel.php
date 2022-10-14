<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\db;


use lcgwhat\phpmvc\Application;

abstract class DbModel extends \lcgwhat\phpmvc\Model
{

    abstract static function tableName():string;
    abstract function attributes():array;
    abstract static function primaryKey():string;
    public function rules(): array
    {
       return [];
    }

    public function save()
    {
        $tablename = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(function($attr){return ":$attr";}, $attributes);
        $statement = self::prepare("Insert into $tablename (".implode(',', $attributes).") 
values (".implode(',', $params).")");
        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();

        return true;
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND", array_map(function($attr){return "$attr = :$attr";}, $attributes));

        $statement = self::prepare("select * from $tableName where $sql");
        
        foreach ($where as $key=>$value) {
            $statement->bindValue(":$key", $value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }
    /**
     * @param $sql
     * @return bool|\PDOStatement
     */
    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}
