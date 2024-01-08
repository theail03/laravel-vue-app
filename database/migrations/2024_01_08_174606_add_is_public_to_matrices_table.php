<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPublicToMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matrices', function (Blueprint $table) {
            // Add the is_public boolean column with a default value of false
            $table->boolean('is_public')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matrices', function (Blueprint $table) {
            // Drop the is_public column if the migration is rolled back
            $table->dropColumn('is_public');
        });
    }
}
