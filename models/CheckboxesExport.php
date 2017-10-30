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
        $checkboxes = Checkbox::all();
        $checkboxes->each(function ($checkbox) use ($columns) {
            var_dump($checkbox);
            if(!$checkbox->required) {
                $checkbox->required = 0;
            } else {
                $checkbox->required = 1;
            }
            //TODO: check if necessary
            $checkbox->addVisible($columns);
        });
        return $checkboxes->toArray();

    }

}