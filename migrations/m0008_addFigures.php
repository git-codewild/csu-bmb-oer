<?php

use codewild\phpmvc\Application;

class m0008_addFigures
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE figures (
            id BINARY(16) PRIMARY KEY,
            title VARCHAR(64),
            caption VARCHAR(1024),
            imageId BINARY(16)
            );                                  
            CREATE TRIGGER uuid_figures BEFORE INSERT ON figures
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
            ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TABLE figures; DROP TRIGGER uuid_figures;";
        $db->pdo->exec($SQL);
    }

}
