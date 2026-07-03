<?php

// database/migrations/{timestamp}_make_household_data_nullable.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeHouseholdDataNullable extends Migration
{
    public function up()
    {
        Schema::table('household_data', function (Blueprint $table) {
            $table->string('UnitNo')->nullable()->change();
            $table->string('AdultOrMinor')->nullable()->change();
            $table->string('Relation')->nullable()->change();
            $table->string('Property')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phoneNumber')->nullable()->change();
            $table->string('adminEmail')->nullable()->change();
            $table->integer('Code')->nullable()->change();
            $table->string('CertificationDate')->nullable()->change();
            $table->string('RecertificationDate')->nullable()->change();
            $table->string('Student')->nullable()->change();
            $table->integer('Age')->nullable()->change();
            $table->json('documents')->nullable()->change();
            $table->json('FamilySize')->nullable()->change();
            // ... repeat for other columns as needed
        });
    }

    public function down()
    {
        // Reversing the changes is optional and depends on your needs.
        // This down method is provided in case you want to rollback the migration.
        // You can customize it based on your requirements.
    }
}