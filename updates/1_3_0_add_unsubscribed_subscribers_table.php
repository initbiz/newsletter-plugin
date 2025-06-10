<?php

declare(strict_types=1);

namespace InITbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddUnsubscribedSubscribersTable extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_subscribers', function ($table) {
            $table->timestamp('unsubscribed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_subscribers', function ($table) {
            $table->dropColumn('unsubscribed_at');
        });
    }
}
