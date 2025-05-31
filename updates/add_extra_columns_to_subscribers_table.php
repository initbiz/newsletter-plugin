<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddExtraColumnsToSubscribersTable extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_subscribers', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('company')->nullable();
            $table->string('sex', 15)->nullable();
            $table->integer('age')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->mediumText('additional_fields')->nullable();
            $table->mediumText('additional_data')->nullable();
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('address_line1');
            $table->dropColumn('address_line2');
            $table->dropColumn('company');
            $table->dropColumn('sex');
            $table->dropColumn('age');
            $table->dropColumn('phone');
            $table->dropColumn('city');
            $table->dropColumn('zip');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('additional_fields');
        });
    }
}
