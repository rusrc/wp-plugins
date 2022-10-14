<?php

namespace C4lPartners\Models;

interface IBaseModel
{
    public function getPrimaryKey();
    public function getTableName(): string;
}
