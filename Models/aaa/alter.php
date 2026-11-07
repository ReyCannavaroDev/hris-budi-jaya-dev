<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class aaa extends Migration
{
    protected $tableName = "aaa";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            $table->string('hey')->change();
            //$table->dropColumn([ ]);
        });
    }
}
