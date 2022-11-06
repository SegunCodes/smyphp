<?php

namespace SmyPhp\Core;
use SmyPhp\Core\Application;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 
abstract class DatabaseModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function uniqueKey(): string;

    abstract public function getDisplayName(): string;
    
    public function save(){
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") 
        VALUES (".implode(',', $params).")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public function findOne($where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    public function delete($where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("DELETE FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
    }

    //UPDATE
    // public function update($where){
    //     $tableName = static::tableName();
    //     $attributes = array_keys($where);
    //     $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
    //     $stmt = self::prepare("UPDATE $tableName SET WHERE $sql");
    //     foreach ($where as $key => $item) {
    //         $stmt->bindValue(":$key", $item);
    //     }
    //     $stmt->execute();
    // }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}