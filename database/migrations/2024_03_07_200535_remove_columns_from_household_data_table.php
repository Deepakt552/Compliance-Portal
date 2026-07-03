<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromHouseholdDataTable extends Migration
{
    public function up()
    {
        Schema::table('household_data', function (Blueprint $table) {
            $table->dropColumn('documents');
            $table->dropColumn('Sr_No');
            $table->dropColumn('Property');
            $table->dropColumn('email');
            $table->dropColumn('phoneNumber');
            $table->dropColumn('adminEmail');
        });
    }

    public function down()
    {
        Schema::table('household_data', function (Blueprint $table) {
            // Define the columns to add back if you need to rollback
            $table->string('documents')->nullable();
            $table->integer('Sr_No')->nullable();
            $table->string('Property')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('adminEmail')->nullable();
        });
    }
}