<?php

namespace C4lPartners\Models;

class Partner implements IBaseModel
{
    public $id;
    /**
     * Название компании / партнера
     */
    public $name;
    /**
     * Ссылки для страницы входа
     */
    public $link;
    /**
     * Ссылка на логотип
     */
    public $logoLink;
    /**
     * Название логина для поля на форме
     */
    public $loginNameInput;
    /**
     * Название пароля для поля на форме
     */
    public $passwordNameInput;
    /**
     * Текст приветствия
     */
    public $description;

    /**
     * Type of input: text, number, date, password
     */
    public $inputType;

    public array $clients;

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
        $tableName = "{$wpdb->prefix}c4l_partner";

        return $tableName;
    }

    public function getPrimaryKey()
    {
        return $this->id;
    }

    public function getTableName(): string
    {
        return Partner::tableName();
    }
}
