<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->string('nic',20)->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('profile_pic')->nullable();
            $table->string('signature_path')->nullable();
            $table->unsignedSmallInteger('designation')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admin_users');
    }
}
