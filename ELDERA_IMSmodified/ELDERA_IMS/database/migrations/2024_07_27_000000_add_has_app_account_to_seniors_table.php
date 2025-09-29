<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasAppAccountToSeniorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->boolean('has_app_account')->default(false)->after('has_pension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropColumn('has_app_account');
        });
    }
}