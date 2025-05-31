<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddIndexToTokenOnSubscribersTable extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_subscribers', function (Blueprint $table) {
            $table->unique('token', 'initbiz_newsletter_subscribers_token_unique');
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_subscribers', function (Blueprint $table) {
            $table->dropUnique('initbiz_newsletter_subscribers_token_unique');
        });
    }
}
