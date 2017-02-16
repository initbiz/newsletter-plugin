<?php
namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCheckbox_SubscriberTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkbox_subscriber', function ($table) {
            $table->integer('checkbox_id')->unsigned();
            $table->integer('subscriber_id')->unsigned();
            $table->primary(['checkbox_id', 'subscriber_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkbox_subscriber');
    }
}