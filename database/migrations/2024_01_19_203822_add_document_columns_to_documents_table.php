<?php

// In the generated migration file

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentColumnsToDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->enum("document_$i", ['pending', 'verified', 'rejected'])->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->dropColumn("document_$i");
            }
        });
    }
}