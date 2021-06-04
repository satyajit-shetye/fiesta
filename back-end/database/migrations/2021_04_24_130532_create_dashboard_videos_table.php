<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_playlists', function (Blueprint $table) {
            $table->boolean('is_paid')->default(0)->after('name');
        });

        Schema::create('dashboard_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dashboard_playlist_id');
            $table->foreign('dashboard_playlist_id')->references('id')->on('dashboard_playlists');
            $table->string('name');
            $table->string('thumbnail_url');
            $table->string('file_url');
            $table->string('source');
            $table->boolean('is_active');
            $table->boolean('is_deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_videos');
    }
}
