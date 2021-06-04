<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackgroundUrlToDashboardPlaylists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_playlists', function (Blueprint $table) {
            $table->string('background_url')->after('name')->default('/storage/app/public/images/playlist-background/image-1.jpg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashboard_playlists', function (Blueprint $table) {
            $table->dropColumn('background_url');
        });
    }
}
