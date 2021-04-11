<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vet_books', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("pet_id");
            $table->foreignId("veterinary_id");
            $table->dateTime("onDate");
            $table->enum("status", ["booked", "cancel", "denied", "accepted", "completed"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vet_books');
    }
}
