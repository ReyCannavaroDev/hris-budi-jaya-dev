<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkarydetpemb extends Migration
{
    protected $tableName = "m_kary_det_pemb";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            // $table->renameColumn('m_karyawan_id', 'm_kary_id');
            // $table->bigInteger('bank_id')->comment('{"src":"m_general.id"}')->nullable();
            // $table->string('no_rek',50)->nullable();
            // $table->string('atas_nama_rek')->nullable();
        });
    }
}
