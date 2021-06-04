<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecentVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recent_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dashboard_video_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('dashboard_video_id')->references('id')->on('dashboard_videos');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('recent_videos');
    }
}
