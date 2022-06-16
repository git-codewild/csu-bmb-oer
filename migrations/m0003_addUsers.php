<?php

use codewild\phpmvc\Application;

class m0003_addUsers {
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE table users (
            id BINARY(16) PRIMARY KEY,
            status INT(1) DEFAULT 0 NOT NULL,
            username VARCHAR(32) UNIQUE NOT NULL,
            email VARCHAR(64) UNIQUE NOT NULL,
            firstname VARCHAR(64),
            lastname VARCHAR(64),
            password VARCHAR(255) NOT NULL, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TRIGGER uuid_user BEFORE INSERT ON users
            FOR EACH ROW BEGIN
                SET @last_insert_id = UUID();
                SET NEW.id = @last_insert_id;
            END;
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER insert_new_user;
            DROP TABLE users;";
        $db->pdo->exec($SQL);
    }
}
