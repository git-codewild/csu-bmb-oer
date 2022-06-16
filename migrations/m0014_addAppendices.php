<?php

use codewild\phpmvc\Application;

class m0014_addAppendices
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE appendices (
            id BINARY(16) PRIMARY KEY,
            path VARCHAR(64) NOT NULL,
            title VARCHAR(64),
            html VARCHAR(65355),
            created_by BINARY(16) NOT NULL
            );
            CREATE TRIGGER uuid_appendices BEFORE INSERT ON appendices
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_appendices; DROP TABLE appendices;";
        $db->pdo->exec($SQL);
    }

}
