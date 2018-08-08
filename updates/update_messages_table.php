<?php namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class UpdateMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_messages', function ($table) {
            $table->renameColumn('sendto', 'send_to');
            $table->string('email_template')->nullable();
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_messages', function ($table) {
            $table->renameColumn('send_to', 'sendto');
            $table->dropColumn('email_template');
        });
    }
}
