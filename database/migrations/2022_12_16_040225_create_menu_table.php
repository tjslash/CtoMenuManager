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
        if (!Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug')->unique()->index();
                $table->string('url')->nullable();
                if (Schema::hasTable('pages')) {
                    $table->unsignedBigInteger('page_id')->nullable();
                    $table->foreign('page_id')
                        ->references('id')
                        ->on('pages')
                        ->onUpdate('CASCADE')
                        ->onDelete('RESTRICT');
                }
                $table->boolean('_blank')->default(false);
                $table->integer('priority')->default(0);
                $table->boolean('active')->default(false);
                $table->timestamps();
                $table->softDeletes();
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
        if (Schema::hasTable('menu')) {
            Schema::dropIfExists('menu');
        }
    }
};
