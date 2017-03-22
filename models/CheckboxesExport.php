<?php namespace Initbiz\Newsletter\Models;

use Backend\Models\ExportModel;
use ApplicationException;

/**
 * Post Export Model
 */
class CheckboxesExport extends ExportModel
{
    public $table = 'initbiz_newsletter_checkbox';

    public $fillable = ['required', 'name', 'text'];


    public function exportData($columns, $sessionKey = null)
    {
        $result = self::make()
            ->get()
            ->toArray()
        ;
        return $result;

    }

}