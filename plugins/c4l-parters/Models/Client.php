<?php

namespace C4lPartners\Models;

class Client implements IBaseModel
{
    public $id;
    public $name;
    public $login;
    public $password;
    public $partner_id;

    public Partner $partner;

    /**
     * @param array $params - example $params['name'];
     */
    public function __construct($params = null)
    {
        if (isset($params) && is_array($params)) {
            foreach ($params  as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public static function tableName(): string
    {
        global $wpdb;
        $tableName = "{$wpdb->prefix}c4l_client";

        return $tableName;
    }

    public function getPrimaryKey()
    {
        return $this->id;
    }

    public function getTableName(): string
    {
       return Client::tableName();
    }
}
