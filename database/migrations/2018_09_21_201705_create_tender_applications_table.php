<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_no',15)->unique();
            $table->unsignedInteger('tender_id');
            $table->unsignedSmallInteger('applicant_type');
            $table->string('amount');
            $table->string('applicant_fullname',255)->nullable();
            $table->text('applicant_address')->nullable();
            $table->string('applicant_email',100)->nullable();
            $table->string('applicant_nationality',50)->nullable();
            $table->string('tenderer_fullname',255)->nullable();
            $table->string('tenderer_address')->nullable();
            $table->string('tenderer_nationality',50)->nullable();
            $table->string('registration_number',50)->nullable();
            $table->string('token',32)->nullable();
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
        Schema::dropIfExists('tender_applications');
    }
}
