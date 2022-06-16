<?php

use codewild\phpmvc\Application;

class m0002_addArticles {
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE articles (
            id BINARY(16) PRIMARY KEY,
            title VARCHAR(64),
            html VARCHAR(65535)
        );
        CREATE TRIGGER uuid_article BEFORE INSERT ON articles
            FOR EACH ROW BEGIN
                SET @last_insert_id = SUBSTR(UUID(), 1, 16);
                SET NEW.id = @last_insert_id;
            END;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_article;
            DROP table articles";
        $db->pdo->exec($SQL);
    }
}
