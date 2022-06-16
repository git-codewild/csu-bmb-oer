<?php

use codewild\phpmvc\Application;

class m0013_addContacts
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE contacts (
            id BINARY(16) PRIMARY KEY,
            priority INT(1) DEFAULT 0,
            subject VARCHAR(64) NOT NULL,
            email VARCHAR(64),
            body VARCHAR(512) NOT NULL,
            sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            sent_by BINARY(16)        
            );
            CREATE TRIGGER uuid_contacts BEFORE INSERT ON contacts
                FOR EACH ROW BEGIN
                    SET @last_insert_id = UUID();
                    SET NEW.id = @last_insert_id;
                END;
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_contacts; DROP TABLE contacts;";
        $db->pdo->exec($SQL);
    }

}
