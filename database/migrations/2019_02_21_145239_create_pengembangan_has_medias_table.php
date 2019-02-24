<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengembanganHasMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembangan_has_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pengembangan_id');
            $table->unsignedInteger('media_pengembangan_id');
            $table->timestamps();

            $table->foreign('pengembangan_id')->references('id')->on('pengembangans')->onDelete('cascade');
            $table->foreign('media_pengembangan_id')->references('id')->on('media_pengembangans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengembangan_has_medias');
    }
}
