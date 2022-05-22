<?php

use codewild\csubmboer\core\Application;

class m0007_addImages
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE images (
            id BINARY(16) PRIMARY KEY,
            path VARCHAR(128),
            name VARCHAR(64),
            type VARCHAR(16),
            size INT(10)
            );              
            CREATE TRIGGER uuid_images BEFORE INSERT ON images
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
            ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TABLE images; DROP TRIGGER uuid_images;";
        $db->pdo->exec($SQL);
    }

}
