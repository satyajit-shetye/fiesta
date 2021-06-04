<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_categories', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->change();
            $table->boolean('is_deleted')->default(0)->change();
        });

        Schema::table('dashboard_playlists', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->change();
            $table->boolean('is_deleted')->default(0)->change();
        });

        Schema::table('dashboard_videos', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
