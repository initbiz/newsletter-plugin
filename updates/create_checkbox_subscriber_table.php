<?php

declare(strict_types=1);

namespace InITbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCheckbox_SubscriberTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkbox_subscriber', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('checkbox_id')->unsigned();
            $table->integer('subscriber_id')->unsigned();
            $table->primary(['checkbox_id', 'subscriber_id'], 'initbiz_subscriber_checkbox_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkbox_subscriber');
    }
}
