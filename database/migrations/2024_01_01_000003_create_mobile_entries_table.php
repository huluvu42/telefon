<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
};

