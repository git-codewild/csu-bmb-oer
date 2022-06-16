<?php

use codewild\phpmvc\Application;

class m0001_initial {
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE table modules (
            id BINARY(16) PRIMARY KEY,
            title VARCHAR(64) NOT NULL,
            subtitle VARCHAR(64),
            path VARCHAR(64) UNIQUE,
            keywords VARCHAR(1024) 
        );
        CREATE TRIGGER uuid_module BEFORE INSERT ON modules
            FOR EACH ROW SET NEW.id = UUID();

        CREATE TABLE outlines (
            id BINARY(16) PRIMARY KEY,
            courseId VARCHAR(8),
            parentId BINARY(16),
            n INT(2) NOT NULL,
            title VARCHAR(64),
            moduleVersionId BINARY(16)
        );
        CREATE TRIGGER uuid_outline BEFORE INSERT ON outlines
            FOR EACH ROW SET NEW.id = UUID();";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER insert_new_outline;
            DROP TRIGGER insert_new_module;
            DROP TABLE modules;
            DROP TABLE outlines;";
        $db->pdo->exec($SQL);
    }
}
