<?php
// database/migrations/{timestamp}_create_properties_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('Code')->unique();
            $table->string('Property');
            $table->string('Address');
            $table->string('City');
            $table->integer('Zip');
            $table->string('State');
            $table->string('units')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}