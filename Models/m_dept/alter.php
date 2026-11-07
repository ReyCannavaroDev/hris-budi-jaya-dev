<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mdept extends Migration
{
    protected $tableName = "m_dept";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->renameColumn('comp_id', 'm_dir_id');
            // $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable()->change();
            $table->bigInteger('m_divisi_id')->comment('{"src":"m_divisi.id"}')->nullable()->change();
            //$table->dropColumn([ ]);
        });
    }
}
