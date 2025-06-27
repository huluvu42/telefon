<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2024_01_01_000001_create_contacts_table.php
class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->string('title')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('building')->nullable();
            $table->string('department')->nullable();
            $table->string('source')->default('main'); // 'main', 'mobile', 'manual'
            $table->timestamps();
            
            $table->index(['name', 'first_name']);
            $table->index('department');
            $table->index('building');
            $table->index('source');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}