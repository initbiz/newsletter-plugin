<?php namespace Initbiz\Newsletter\Updates;

use Schema;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateInitbizNewsletterCheckboxes2 extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_checkboxes', function ($table) {
            $table->text('slug')->unique();
        });

        $oldRequired = Settings::get('required_checkbox');
        if ($oldRequired !== null || $oldRequired !== "") {
            Checkbox::create([
                'name'     => 'Required',
                'slug'     => 'required',
                'text'     => $oldRequired,
                'required' => true
            ]);
        }

        $oldOptional = Settings::get('optional_checkbox');
        if ($oldRequired !== null || $oldRequired !== "") {
            Checkbox::create([
                'name'     => 'Optional',
                'slug'     => 'optional',
                'text'     => $oldOptional,
                'required' => false
            ]);
        }
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_checkboxes', function ($table) {
            $table->dropColumn('slug');
        });
    }
}
