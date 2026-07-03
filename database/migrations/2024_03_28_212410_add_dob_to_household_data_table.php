<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDobToHouseholdDataTable extends Migration
{
    public function up()
    {
        Schema::table('household_data', function (Blueprint $table) {
            $table->date('dob')->nullable(); // Assuming date of birth is a date field and can be nullable
            $table->string('gender')->nullable(); // Assuming date of birth is a date field and can be nullable
        });
    }

    public function down()
    {
        Schema::table('household_data', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('gender');
        });
    }
}