<?php

use codewild\phpmvc\Application;

class m0011_addDataNavs
{
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE data_navs (
            dataFileId BINARY(16),
            scriptId BINARY(16),
            n INT (2),
            PRIMARY KEY (dataFileId, scriptId)        
            );
        ";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TABLE data_navs;";
        $db->pdo->exec($SQL);
    }

}
