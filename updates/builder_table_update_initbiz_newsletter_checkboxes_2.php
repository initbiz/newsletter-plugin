<?php namespace Initbiz\Newsletter\Updates;

use Schema;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateInitbizNewsletterCheckboxes2 extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_checkboxes', function ($table) {
            $table->text('slug')->unique();
        });

        $oldRequired = Initbiz\Newsletter\Models\Settings::get('required_checkbox');
        if ($oldRequired !== null || $oldRequired !== "") {
            Checkbox::create([
                'name'     => 'Required',
                'slug'     => 'required',
                'text'     => (string)$oldRequired,
                'required' => true
            ]);
        }

        $oldOptional = Initbiz\Newsletter\Models\Settings::get('optional_checkbox');
        if ($oldRequired !== null || $oldRequired !== "") {
            Checkbox::create([
                'name'     => 'Optional',
                'slug'     => 'optional',
                'text'     => (string)$oldOptional,
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
