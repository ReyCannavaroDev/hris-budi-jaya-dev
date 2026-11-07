<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mspddetbiaya extends Migration
{
    protected $tableName = "m_spd_det_biaya";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn(['m_comp_id']);
            $table->text('keterangan')->nullable();
        });
    }
}
