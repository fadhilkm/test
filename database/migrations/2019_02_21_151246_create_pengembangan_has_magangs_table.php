<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengembanganHasMagangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembangan_has_magangs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pengembangan_id');
            $table->unsignedInteger('magang_id');
            $table->timestamps();

            $table->foreign('pengembangan_id')->references('id')->on('pengembangans')->onDelete('cascade');
            $table->foreign('magang_id')->references('id')->on('magangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengembangan_has_magangs');
    }
}
