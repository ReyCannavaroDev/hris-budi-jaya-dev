<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tperhitungangaji extends Migration
{
    protected $tableName = "t_perhitungan_gaji";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('type_perhitungan')->nullable();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
