<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderDocumentsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_documents_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tender_document_id');
            $table->unsignedSmallInteger('status');
            $table->text('comments');
            $table->enum('comment_type', ['internal', 'external'])->default('internal');
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
        Schema::dropIfExists('tender_documents_status');
    }
}
