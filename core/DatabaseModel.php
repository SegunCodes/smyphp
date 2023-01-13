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
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql"); 
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    public function findOneOrWhere($where, $orWhere){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $orpart = array_keys($orWhere); 
        $sql2 = implode(" AND ", array_map(fn($or) => "$or = :$or", $orpart));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql or $sql2"); 
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        foreach ($orWhere as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    public function findAll(){
        $tableName = static::tableName();
        $stmt = self::prepare("SELECT * FROM $tableName ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findAllWhere($where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql ORDER BY id DESC");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findAllOrWhere($where, $orWhere){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $orpart = array_keys($orWhere); 
        $sql2 = implode(" AND ", array_map(fn($or) => "$or = :$or", $orpart));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql or $sql2 ORDER BY id DESC");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        foreach ($orWhere as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function count(){
        $tableName = static::tableName();
        $stmt = self::prepare("SELECT count(*) FROM $tableName"); 
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countWhere($where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT count(*) FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countOrWhere($where, $orWhere){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $orpart = array_keys($orWhere); 
        $sql2 = implode(" AND ", array_map(fn($or) => "$or = :$or", $orpart));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT count(*) FROM $tableName WHERE $sql OR $sql2");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        foreach ($orWhere as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function delete($where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("DELETE FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        return $stmt->execute();
    }

    public function deleteOrWhere($where, $orWhere){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $orpart = array_keys($orWhere); 
        $sql2 = implode(" AND ", array_map(fn($or) => "$or = :$or", $orpart));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("DELETE FROM $tableName WHERE $sql OR $sql2");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        foreach ($orWhere as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        return $stmt->execute();
    }

    public function update($data, $where){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $set = array_keys($data);
        $setData = implode(", ", array_map(fn($d) => "$d = :$d", $set));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("UPDATE $tableName SET $setData WHERE $sql");
        foreach ($data as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        return $stmt->execute();
    }

    public function updateOrWhere($data, $where, $orWhere){
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $set = array_keys($data);
        $orpart = array_keys($orWhere); 
        $sql2 = implode(" AND ", array_map(fn($or) => "$or = :$or", $orpart));
        $setData = implode(", ", array_map(fn($d) => "$d = :$d", $set));
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("UPDATE $tableName SET $setData WHERE $sql or $sql2");
        foreach ($data as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        foreach ($orWhere as $keys => $items) {
            $stmt->bindValue(":$keys", $items);
        }
        return $stmt->execute();
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}