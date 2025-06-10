<?php

declare(strict_types=1);

namespace Initbiz\Newsletter;

use Route;

Route::group(['prefix' => '/api/initbiz/newsletter'], function () {
    Route::post('/mailerlite', [\Initbiz\Newsletter\Api\Controllers\MailerLiteController::class, "handle"]);
});
