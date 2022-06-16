<?php

use codewild\phpmvc\Application;

class m0004_addUserRoles {
    public function up(){
        $db = Application::$app->db;
        $SQL = "CREATE TABLE user_roles (
            userId BINARY(16) NOT NULL,
            roleId INT(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY(userId, roleId)
        );
        CREATE TRIGGER insert_user_role AFTER INSERT ON users
            FOR EACH ROW 
                BEGIN
                    INSERT INTO user_roles (userId) VALUES (new.id);
                END;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER insert_new_user_role;
            DROP TABLE user_roles;";
        $db->pdo->exec($SQL);
    }
}
