<?php

namespace SmyPhp\Core\Database;
use SmyPhp\Core\Application;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 
class Database
{
    public \PDO $pdo;

    /**
     * Database constructor
     */
    public function __construct(array $config){
        $dsn = $config['connection'].":host=".$config['host'].";port=".$config['port'].";dbname=".$config['database'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
    }

    public function saveMigrations(){
        $this->createMigrations();
        $savedMigrations = $this->getSavedMigrations();
        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toSave = array_diff($files, $savedMigrations);
        foreach ($toSave as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration; 
            $filename = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $filename();
            $this->log("$migration migrating");
            $instance->up();
            $this->log("$migration migrated");
            $newMigrations[] = $migration;
        }
        if(!empty($newMigrations)){
            $this->storeMigrations($newMigrations);
        }else{
            $this->log("Migrations up to date");
        }
    }

    public function createMigrations(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function getSavedMigrations(){
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function storeMigrations(array $migrations){
        $str = implode(",", array_map(fn($n) => "('$n')", $migrations));
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $stmt->execute();
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    protected function log($message){
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }

}