<?php

namespace App\Controllers\Backend\Interfaces;

interface DatatableInterface
{
    public function getRecords($start, $length, $orderColumn, $orderDirection): array;
    public function getTotalRecords(): int;
    public function getRecordSearch($search, $start, $length, $orderColumn, $orderDirection): array;
    public function getTotalRecordSearch($search): int;
}
