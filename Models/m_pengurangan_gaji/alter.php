<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mpengurangangaji extends Migration
{
    protected $tableName = "m_pengurangan_gaji";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->bigInteger('comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
        });
    }
}
