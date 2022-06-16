<?php

use codewild\phpmvc\Application;

class m0012_addScripts_JSmol
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE scripts_jsmol (
            id BINARY(16) PRIMARY KEY,
            title VARCHAR(64) NOT NULL,
            vars VARCHAR(65535),
            config VARCHAR(65535),
            display VARCHAR(65535),
            labels VARCHAR(65535),
            camera VARCHAR(65535),
            functions VARCHAR(65535)
            );
            CREATE TRIGGER uuid_scripts_jsmol BEFORE INSERT ON scripts_jsmol
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_scripts_jsmol; DROP TABLE scripts_jsmol;";
        $db->pdo->exec($SQL);
    }

}
