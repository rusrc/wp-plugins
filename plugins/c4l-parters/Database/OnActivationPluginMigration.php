<?php

namespace C4lPartners\Database;

class CreateMigration
{
    public function CreateTablesInDataBase()
    {
        $this->CreatePartnerTable();
        $this->CreateClientTable();
    }

    private function CreatePartnerTable()
    {
        global $wpdb;

        $tableName = "{$wpdb->prefix}c4l_partner";
        if ($wpdb->get_var("show tables like '$tableName'") != $tableName) {

            $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
                `id`                    INT NOT NULL AUTO_INCREMENT,
                `name`                  VARCHAR (255) NOT NULL,
                `link`                  VARCHAR (255) NULL,
                `logoLink`              VARCHAR (255) NULL,
                `description`           TEXT (255) NULL,
                `loginNameInput`        VARCHAR (255) NULL,
                `passwordNameInput`     VARCHAR (255) NULL,
                `inputType`             VARCHAR (100),
                PRIMARY KEY  (id)
			) 
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        };
    }

    private function CreateClientTable()
    {
        global $wpdb;

        $tableName = "{$wpdb->prefix}c4l_client";
        if ($wpdb->get_var("show tables like '$tableName'") != $tableName) {

            $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
                `id` 			INT NOT NULL AUTO_INCREMENT,
                `partner_id`    INT,
                `name`          VARCHAR (255) NOT NULL,
                `login`         VARCHAR (255) NOT NULL,
                `password`      VARCHAR (255) NOT NULL,
                PRIMARY KEY (id),
                INDEX par_ind (partner_id),
                FOREIGN KEY (partner_id)
                    REFERENCES `{$wpdb->prefix}c4l_partner` (id)
                    ON DELETE CASCADE,
                UNIQUE (`login`)
            ) 
            COLLATE='utf8_general_ci'
            ENGINE=INNODB;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        };
    }
}
