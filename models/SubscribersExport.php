<?php namespace Initbiz\Newsletter\Models;

use Backend\Models\ExportModel;
use ApplicationException;

/**
 * Post Export Model
 */
class SubscribersExport extends ExportModel
{
    public $table = 'initbiz_newsletter_subscribers';

    public $fillable = ['emial'];


    public function exportData($columns, $sessionKey = null)
    {
        $result = self::make()
            ->get()
            ->toArray()
        ;
        return $result;

    }

}