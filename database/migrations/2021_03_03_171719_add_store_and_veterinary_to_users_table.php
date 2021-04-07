<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreAndVeterinaryToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger("veterinary_id")->nullable()->index();
            $table->unsignedBigInteger("store_id")->nullable()->index();

            $table->foreign("veterinary_id")->references("id")->on("veterinaries");
            $table->foreign("store_id")->references("id")->on("stores");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign("store_id");
            $table->dropForeign("veterinary_id");
            $table->dropColumn("veterinary_id");
            $table->dropColumn("store_id");
        });
    }
}
