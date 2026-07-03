<?php
// database/migrations/{timestamp}_create_household_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseholdDataTable extends Migration
{
    public function up()
    {
        Schema::create('household_data', function (Blueprint $table) {
            $table->id();
            $table->string('UnitNo')->nullable();
            $table->string('userId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('AdultOrMinor')->nullable();
            $table->string('Relation')->nullable();
            $table->string('Student')->nullable();
            $table->integer('Age')->nullable();
            $table->json('documents')->nullable();
            $table->integer('Sr_No')->unique();
            $table->integer('FamilySize');
            $table->string('CertificationDate');
            $table->string('Property')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('RecertificationDate');
            $table->string('adminEmail')->nullable();
            $table->integer('Code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('household_data');
    }
}