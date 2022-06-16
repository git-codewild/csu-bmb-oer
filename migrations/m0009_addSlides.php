<?php

use codewild\phpmvc\Application;

class m0009_addSlides
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE slides (
            resourceId BINARY(16),
            articleId BINARY(16),
            n INT(2),
            type VARCHAR(32),
            PRIMARY KEY (resourceId, articleId)
            );
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TABLE slides;";
        $db->pdo->exec($SQL);
    }

}
