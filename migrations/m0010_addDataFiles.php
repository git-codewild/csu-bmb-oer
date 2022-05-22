<?php

use codewild\csubmboer\core\Application;

class m0010_addDataFiles
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE data_files (
            id BINARY(16) PRIMARY KEY,
            path VARCHAR(128),
            name VARCHAR(64),
            type VARCHAR(16),
            size INT(10),
            title VARCHAR(64)
            );
            CREATE TRIGGER uuid_data_files BEFORE INSERT ON data_files
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_data_files; DROP TABLE data_files;";
        $db->pdo->exec($SQL);
    }

}
