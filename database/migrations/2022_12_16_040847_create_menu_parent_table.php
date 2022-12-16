<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu_parent')) {
            Schema::create('menu_parent', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('menu_id');
                $table->foreign('menu_id')
                      ->references('id')
                      ->on('menu')
                      ->onDelete('cascade');
                $table->unsignedInteger('parent_id');
                $table->foreign('parent_id')
                      ->references('id')
                      ->on('menu')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('menu_parent')) {
            Schema::dropIfExists('menu_parent');
        }
    }
};
