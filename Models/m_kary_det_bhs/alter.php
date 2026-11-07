<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkarydetbhs extends Migration
{
    protected $tableName = "m_kary_det_bhs";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            // $table->renameColumn('m_karyawan_id', 'm_kary_id');
            // $table->integer('nilai_lisan')->nullable()->change();
            // $table->integer('nilai_tertulis')->nullable()->change();
            $table->string('level_lisan')->nullable();
            $table->string('level_tertulis')->nullable();
        });
    }
}
