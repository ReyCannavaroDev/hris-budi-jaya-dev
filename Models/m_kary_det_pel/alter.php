<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkarydetpel extends Migration
{
    protected $tableName = "m_kary_det_pel";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['m_karyawan_id']);
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            // $table->bigInteger('m_kary_id')->comment('{"fk":"m_kary.id"}')->nullable()->change();

        });
    }
}
