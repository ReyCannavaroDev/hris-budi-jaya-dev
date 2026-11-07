<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mjamkerja extends Migration
{
    protected $tableName = "m_jam_kerja";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->renameColumn('comp_id', 'm_dir_id');
            // $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable()->change();
            // $table->bigInteger('tipe_jam_kerja_id')->comment('{"src":"m_general.id"}')->nullable()->change();
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();

            //$table->dropColumn([ ]);
        });
    }
}
