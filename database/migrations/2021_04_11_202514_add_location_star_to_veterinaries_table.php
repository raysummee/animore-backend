<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationStarToVeterinariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('veterinaries', function (Blueprint $table) {
            $table->text("location");
            $table->integer("star");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veterinaries', function (Blueprint $table) {
            $table->dropColumn("location");
            $table->dropColumn("star");
        });
    }
}
