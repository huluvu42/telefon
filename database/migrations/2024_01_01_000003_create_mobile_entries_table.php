<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('mobile_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('mobile_groups')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('name');
            $table->integer('order_position')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_entries');
    }
}

// database/migrations/2024_01_01_000004_create_uploads_table.php
class CreateUploadsTable extends Migration
{
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('type'); // 'main' or 'mobile'
            $table->string('path');
            $table->integer('records_processed')->default(0);
            $table->json('processing_log')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}