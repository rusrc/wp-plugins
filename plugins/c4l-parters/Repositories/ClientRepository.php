<?php

namespace C4lPartners\Repositories;

use C4lPartners\Models\Client;
use C4lPartners\Models\Partner;

class ClientRepository extends BaseRepository
{
    private string $_tableName;

    public function __construct()
    {
        $this->_tableName = Client::tableName();
    }

    /**
     * Get item by id
     *
     * @param  mixed $id
     * @return Client
     */
    public function GetItem($id): Client
    {
        global $wpdb;

        $row = $wpdb->get_row("select * from {$this->_tableName} where id = {$id}");

        $partner = new Client(get_object_vars($row));

        return $partner;
    }

    /**
     * insert if not exists
     * 
     * used `INSERT IGNORE INTO` sql statment
     *
     * @param  mixed $partner
     * @return void
     */
    public function InsertIfNotExists(Client $client)
    {
        global $wpdb;

        $sql = "
        INSERT IGNORE INTO `{$this->_tableName}`
        SET `name` = '{$client->name}',
            `login` = '{$client->login}',
            `password` = '{$client->password}',
            `partner_id` = '{$client->partner_id}';
        ";

        // $sql = "
        // SET @name = '{$client->name}',
        //     @login = '{$client->login}',
        //     @password = '{$client->password}', 
        //     @partner_id = '{$client->partner_id}';
        // INSERT INTO `{$this->_tableName}` 
        //     (`name`, `login`, `password`, `partner_id`)
        // VALUES
        //     (@name, @login, @password, @partner_id)
        // ON DUPLICATE KEY UPDATE
        //     `name` = @name, 
        //     `login` = @login, 
        //     `password` = @password, 
        //     `partner_id` = @partner_id;
        // ";

        $wpdb->query($sql);
    }

    public function Save(Partner $client)
    {
        return parent::InsertOrUpdateItem($client);
    }

    public function Update(Partner $client)
    {
        return parent::InsertOrUpdateItem($client);
    }

    public function Delete(Partner $client): bool
    {
        return parent::DeleteItem($client);
    }

    public function GetAll()
    {
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM $this->_tableName");

        return $results;
    }
}
