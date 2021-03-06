<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
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
               $table->string('telegramId',255);
               $table->string('apiToken',255);
               $table->string('apiRefreshToken',255);
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
                $table->dropColumn(['telegramCode', 'apiToken', 'apiRefreshToken']);
            });
        }
    }
}
