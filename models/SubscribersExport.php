<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Models;

use Backend\Models\ExportModel;

/**
 * Post Export Model
 */
class SubscribersExport extends ExportModel
{
    public $table = 'initbiz_newsletter_subscribers';

    public $fillable = ['email', 'confirmed', 'token'];

    public function exportData($columns, $sessionKey = null)
    {
        $subscribers = Subscriber::all();
        $subscribers->each(function ($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }
}
