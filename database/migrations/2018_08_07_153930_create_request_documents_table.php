<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_group_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('fee', 8, 2)->default(0);
            $table->enum('is_required', ['yes', 'no']);
            $table->unsignedSmallInteger('specific_company_member_type')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedSmallInteger('status');
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
        Schema::dropIfExists('documents');
    }
}
