<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mstandartgaji extends Migration
{
    protected $tableName = "m_standart_gaji";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn(['tunjangan_profesi','tunjangan_profesi_periode']);
            // $table->bigInteger('m_dept_id')->comment('{"src":"m_dept.id"}')->nullable()->change();
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            $table->dropColumn(['tunjangan_masa_kerja','tunjangan_komunikasi']);
        });
    }
}
