<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagangHasSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magang_has_surats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('magang_id');
            $table->unsignedInteger('surat_id');
            $table->timestamps();

            $table->foreign('magang_id')->references('id')->on('magangs')->onDelete('cascade');
            $table->foreign('surat_id')->references('id')->on('surats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magang_has_surats');
    }
}
