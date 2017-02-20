<?php
namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCheckboxesTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkboxes', function($table)
        {
            $table->increments('id');
            $table->boolean('required',false);
            $table->string('name')->unique();
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkboxes');
    }
}
