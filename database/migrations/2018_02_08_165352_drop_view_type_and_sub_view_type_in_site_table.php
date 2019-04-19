<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropViewTypeAndSubViewTypeInSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('ViewType');
            $table->dropColumn('SubviewType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('ViewType', 100)->nullable(false)->default('GridView')->after('ModifiedBy');
            $table->string('SubviewType', 100)->nullable(false)->default('GridView')->after('ViewType');
        });

    }
}
