<?php

use codewild\csubmboer\core\Application;

class m0006_addArticleNavs {
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE article_navs (
            id BINARY(16) UNIQUE NOT NULL,
            parentId BINARY(16),
            n INT(2),
            articleId BINARY(16),
            versionId BINARY(16),
            PRIMARY KEY (articleId, versionId)    
            );
            CREATE TRIGGER uuid_article_navs BEFORE INSERT ON article_navs
                FOR EACH ROW BEGIN
                    SET @last_insert_id = SUBSTR(UUID(), 1, 16);
                    SET NEW.id = @last_insert_id;
                END;
            ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TABLE article_navs; DROP TRIGGER uuid_article_navs;";
        $db->pdo->exec($SQL);
    }
}
