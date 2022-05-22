<?php

use codewild\csubmboer\core\Application;

class m0005_addModuleVersions {
    public function up(){
        $db = Application::$app->db;
        $SQL = "ALTER TABLE modules
            ADD COLUMN created_by BINARY(16),
            ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
        CREATE TABLE module_versions (
            id BINARY(16) PRIMARY KEY,
            moduleId BINARY(16) NOT NULL,
            status INT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_by BINARY(16),
            updated_at TIMESTAMP NULL,
            updated_by BINARY(16)
        );
        CREATE TRIGGER uuid_module_version BEFORE INSERT ON module_versions
            FOR EACH ROW BEGIN
                SET @last_insert_id = SUBSTR(UUID(), 1, 16);
                SET NEW.id = @last_insert_id;
            END;
        CREATE TRIGGER insert_module_version AFTER INSERT ON modules
            FOR EACH ROW 
                BEGIN
                    INSERT INTO module_versions (moduleId, created_by) VALUES (new.id, new.created_by);
                END;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "DROP TRIGGER uuid_module_version;
           DROP TRIGGER insert_module_version;
           DROP TABLE module_versions;";
        $db->pdo->exec($SQL);
    }
}
