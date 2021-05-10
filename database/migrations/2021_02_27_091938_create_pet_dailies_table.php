<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pet_dailies', function (Blueprint $table) {
            $table->id();
            $table->string("task_name");
            $table->time("time");
            $table->foreignId("pet_id");
            $table->enum("week", ["mon", "tue", "wed", "thu", "fri", "sat", "sun"]);
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
        Schema::dropIfExists('pet_dailies');
    }
}
