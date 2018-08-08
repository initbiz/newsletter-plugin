<?php
namespace InITbiz\Newsletter\Updates;

use Schema;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Database\Updates\Migration;

class CreateCheckboxesTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkboxes', function ($table) {
            $table->increments('id');
            $table->boolean('required')->default(null)->nullable();
            $table->string('name')->unique();
            $table->text('text');
            $table->timestamps();
        });

        Checkbox::create([
            'name'     => 'Required',
            'slug'     => 'required',
            'text'     => Settings::get('required_checkbox'),
            'required' => true
        ]);

        Checkbox::create([
            'name'     => 'Optional',
            'slug'     => 'optional',
            'text'     => Settings::get('optional_checkbox'),
            'required' => false
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkboxes');
    }
}
