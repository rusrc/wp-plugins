<?php

namespace C4lPartners\Models;

const MIN_COUNT_OF_PAGES = 2;
const MAX_COUNT_OF_PAGES = 5;

class Pagination
{
    public int $pageCount;
    public int $totalItemCount;
    public int $pageSize;
    public int $pageNumber;
    public bool $hasNextPage;
    public bool $hasPreviousPage;
    public bool $isFirstPage;
    public bool $isLastPage;
    public int $firstItemOnPage;
    public int $lastItemOnPage;

    public function firstPageNumber()
    {
        $pageNumber =
            $this->isCenter() ? $this->pageNumber - MIN_COUNT_OF_PAGES : $this->isLast() ?  $this->pageCount - MAX_COUNT_OF_PAGES : 1;

        return $this->pageCount <= MAX_COUNT_OF_PAGES ? 1 :  $pageNumber;
    }

    public function lastPageNumber()
    {
        $pageNumber =
            $this->isCenter() ? $this->pageNumber + 4 : $this->isLast() ? $this->pageCount : MAX_COUNT_OF_PAGES;

        return $this->pageCount <= MAX_COUNT_OF_PAGES ? $this->pageCount : $pageNumber;
    }

    private function isCenter(): bool
    {
        return $this->pageCount - $this->pageNumber > MIN_COUNT_OF_PAGES && $this->pageNumber > MAX_COUNT_OF_PAGES;
    }

    private function  isLast(): bool
    {
        return $this->pageCount - $this->pageNumber  <= MIN_COUNT_OF_PAGES;
    }
}
