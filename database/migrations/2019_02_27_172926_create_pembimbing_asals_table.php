<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembimbingAsalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembimbing_asals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('magang_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('pembimbing_asals');
    }
}
