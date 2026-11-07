<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tfinalgajidetrincian extends Migration
{
    protected $tableName = "t_final_gaji_det_rincian";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('t_potongan_id')->comment('{"src":"t_potongan.id"}')->nullable();
            // $table->bigInteger('t_cuti_id')->comment('{"src":"t_cuti.id"}')->nullable();
            // $table->text('label')->change();


        });
    }
}
