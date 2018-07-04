<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_public');
            $table->unsignedInteger('status');
            $table->string('title');
            $table->longText('description');
            $table->string('version');
            $table->string('vue_component');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('id');
        });

        Schema::create('app_user', function (Blueprint $table) {
            $table->unsignedInteger('app_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('app_id')->references('id')->on('apps');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_user');
        Schema::dropIfExists('apps');
    }
}
