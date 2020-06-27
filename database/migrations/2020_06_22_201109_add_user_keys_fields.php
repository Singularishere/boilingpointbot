<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserKeysFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')){
            Schema::table('users',function (Blueprint $table){
                $table->integer('client_id');
                $table->integer('client_secret');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('users')){
            Schema::table('users',function (Blueprint $table){
                $table->dropColumn(['client_id', 'client_secret']);
            });
        }
    }
}
