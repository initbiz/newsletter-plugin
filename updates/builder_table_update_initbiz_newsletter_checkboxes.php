<?php namespace Initbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateInitbizNewsletterCheckboxes extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_checkboxes', function($table)
        {
            $table->boolean('required')->nullable(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('initbiz_newsletter_checkboxes', function($table)
        {
            $table->boolean('required')->nullable()->change();
        });
    }
}
