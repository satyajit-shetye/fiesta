<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('dashboard_category', 'dashboard_categories');

        Schema::create('dashboard_playlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dashboard_category_id');
            $table->foreign('dashboard_category_id')->references('id')->on('dashboard_categories');
            $table->string('name');
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
        Schema::dropIfExists('dashboard_playlists');
    }
}
