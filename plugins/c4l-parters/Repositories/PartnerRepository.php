<?php

namespace C4lPartners\Repositories;

use C4lPartners\Models\Client;
use C4lPartners\Models\Pagination;
use C4lPartners\Models\Partner;

class PartnerRepository extends BaseRepository
{
    private string $_tableName;

    public function __construct()
    {
        $this->_tableName = Partner::tableName();
    }

    /**
     * Get item by id
     *
     * @param  mixed $id
     * @return Partner
     */
    public function GetItem($id): Partner
    {
        global $wpdb;

        $partnerTableName = Partner::tableName();
        $row = $wpdb->get_row("select * from {$partnerTableName} where id = {$id}");

        $partner = new Partner(get_object_vars($row));

        $clientTableName = Client::tableName();
        $rows = $wpdb->get_results("select * from {$clientTableName} where partner_id = {$id}");

        foreach ($rows as $key => $value) {
            $partner->clients[$key] = new Client(get_object_vars($value));
        }

        return $partner;
    }


    /**
     * Get Items by page number
     *
     * @param  mixed $pageSize - count of rows to display
     * @param  mixed $pageNumber
     * @return void
     */
    public function GetItemsByPageNumber(int $pageSize, int $pageNumber = 1)
    {
        global $wpdb;

        $sql = "";
        if ($pageNumber === 1) {
            $sql = "SELECT * FROM $this->_tableName LIMIT {$pageSize}";
        } else {
            $offset = ($pageNumber - 1) * $pageSize;
            $sql = "SELECT * FROM $this->_tableName LIMIT {$pageSize} OFFSET {$offset}";
        }

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function GetPagination(int $pageSize, int $pageNumber = 1): Pagination
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM `{$this->_tableName}`";

        $totalItemCount = $wpdb->get_var($sql);

        $pageCount = (int) ceil($totalItemCount / $pageSize);
        $hasNextPage = $pageNumber < $pageCount;
        $hasPreviousPage = $pageNumber > 1;
        $isFirstPage = $pageNumber == 1;
        $isLastPage = $pageNumber >= $pageCount;

        $firstItemOnPage = ($pageNumber - 1) * $pageSize + 1;
        $numberOfLastItemOnPage = $firstItemOnPage + $pageSize - 1;
        $lastItemOnPage = ($numberOfLastItemOnPage > $totalItemCount) ? $totalItemCount : $numberOfLastItemOnPage;

        $pagination = new Pagination();
        $pagination->pageCount = $pageCount;
        $pagination->hasNextPage = $hasNextPage;
        $pagination->hasPreviousPage = $hasPreviousPage;
        $pagination->isFirstPage = $isFirstPage;
        $pagination->isLastPage = $isLastPage;
        $pagination->totalItemCount = $totalItemCount;
        $pagination->firstItemOnPage = $firstItemOnPage;
        $pagination->numberOfLastItemOnPage = $numberOfLastItemOnPage;
        $pagination->lastItemOnPage = $lastItemOnPage;
        $pagination->pageSize = $pageSize;
        $pagination->pageNumber = $pageNumber;

        return $pagination;
    }

    /**
     * insert if not exists
     * 
     * used `INSERT IGNORE INTO` sql statment
     *
     * @param  mixed $partner
     * @return void
     */
    public function InsertIfNotExists(Partner $partner)
    {
        global $wpdb;

        $sql = "
        INSERT IGNORE INTO `{$this->_tableName}`
        SET `link` = '{$partner->link}',
            `name` = '{$partner->name}';
        ";

        $wpdb->query($sql);
    }

    public function Save(Partner $partner)
    {
        return parent::InsertOrUpdateItem($partner);
    }

    public function Update(Partner $partner)
    {
        return parent::InsertOrUpdateItem($partner);
    }

    public function Delete(Partner $partner): bool
    {
        return parent::DeleteItem($partner);
    }

    public function GetAll()
    {
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM $this->_tableName");

        return $results;
    }
}
