<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tfinalgaji extends Migration
{
    protected $tableName = "t_final_gaji";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('tipe_gaji')->comment('{"src":"m_general.id"}')->nullable();
            $table->string('type_perhitungan')->nullable();
        });
    }
}
