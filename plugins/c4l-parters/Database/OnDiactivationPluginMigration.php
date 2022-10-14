<?php

namespace C4lPartners\Database;

class DropMigration
{
    function DropTablesInDataBase()
    {
        global $wpdb;

        $this->DropTable("{$wpdb->prefix}c4l_client");
        $this->DropTable("{$wpdb->prefix}c4l_partner");
    }

    private function DropTable($tableName)
    {
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS $tableName";
        $wpdb->query($sql);
    }
}
