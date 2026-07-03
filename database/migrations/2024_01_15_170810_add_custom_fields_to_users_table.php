<?php

// database/migrations/{timestamp}_add_custom_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('UserId')->nullable();
            $table->string('UnitNo')->nullable();
            $table->string('FirstName');
            $table->string('LastName');
            $table->integer('Age')->nullable();
            $table->integer('FamilySize')->nullable();
            $table->date('CertificationDate')->nullable();
            $table->date('RecertificationDate')->nullable();
            $table->boolean('ChangePwd')->default(false);
            $table->boolean('ContactDetails')->default(false);
            $table->string('PhoneNumber')->nullable();
            $table->integer('Code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'UserId',
                'UnitNo',
                'FirstName',
                'LastName',
                'Age',
                'FamilySize',
                'CertificationDate',
                'RecertificationDate',
                'ChangePwd',
                'ContactDetails',
                'PhoneNumber',
                'Code',
            ]);
        });
    }
}