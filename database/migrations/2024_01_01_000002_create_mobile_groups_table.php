<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2024_01_01_000002_create_mobile_groups_table.php
class CreateMobileGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('mobile_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "ÄRZTLICHE LEITUNG"
            $table->string('sheet_name'); // e.g., "Mobiltelefone Ortho"
            $table->integer('column_position'); // 1, 2, or 3
            $table->integer('order_position')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_groups');
    }
}