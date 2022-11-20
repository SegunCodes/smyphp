<?php

/**
 * Migration
 */
class users_migration
{
    public function up(){
        $db = \SmyPhp\Core\Application::$app->db;
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL,
            is_verified TINYINT(1) NOT NULL DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB";
        $db->pdo->exec($sql);
    }

    public function down(){
        $db = \SmyPhp\Core\Application::$app->db;
        $sql = "DROP TABLE users";
        $db->pdo->exec($sql);
    }
    
}