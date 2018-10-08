<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderApplicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_application_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tender_application_id');
            $table->unsignedTinyInteger('status');
            $table->text('comments')->nullable();
            $table->unsignedSmallInteger('comment_type')->nullable();
            $table->unsignedInteger('created_by');
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
        Schema::dropIfExists('tender_application_status');
    }
}
