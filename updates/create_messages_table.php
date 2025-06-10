<?php

declare(strict_types=1);

namespace InITbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_messages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 100);
            $table->text('content');
            $table->string('sent', 1)->default(0);
            $table->string('sendto', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_messages');
    }
}
