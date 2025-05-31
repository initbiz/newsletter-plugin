<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateResponsibilityTagsCategoriesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateSubscriberTagTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('initbiz_newsletter_subscriber_tag', function (Blueprint $table) {
            $table->integer('subscriber_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->primary(['subscriber_id', 'tag_id'], 'initbiz_newsletter_subscriber_tag_subscriber_id_tag_id_primary');

            $table->foreign('subscriber_id', 'initbiz_newsletter_subscriber_tag_subscriber_id')
                ->references('id')->on('initbiz_newsletter_subscribers');
            $table->foreign('tag_id', 'initbiz_newsletter_subscriber_tag_tag_id')
                ->references('id')->on('initbiz_newsletter_tags');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_subscriber_tag');
    }
};
