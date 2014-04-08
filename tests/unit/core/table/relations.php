<?php

class FtestTableRelations extends F0FTableRelations
{
    public function normaliseParameters($pivot = false, &$itemName, &$tableClass, &$localKey, &$remoteKey, &$ourPivotKey, &$theirPivotKey, &$pivotTable)
    {
        parent::normaliseParameters($pivot, $itemName, $tableClass, $localKey, $remoteKey, $ourPivotKey, $theirPivotKey, $pivotTable);
    }
}