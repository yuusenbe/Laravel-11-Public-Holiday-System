<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('holidays', function (Blueprint $table) {
        $table->string('region')->after('type_id'); 
    });
}

public function down()
{
    Schema::table('holidays', function (Blueprint $table) {
        $table->dropColumn('region'); 
    });
}

};
