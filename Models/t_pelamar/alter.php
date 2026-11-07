<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpelamar extends Migration
{
    protected $tableName = "t_pelamar";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            // $table->bigInteger('t_loker_id')->comment('{"src":"t_loker.id"}')->nullable();
            // $table->bigInteger('m_dept_id')->comment('{"src":"m_dept.id"}')->nullable();
            // $table->bigInteger('m_posisi_id')->comment('{"src":"m_posisi.id"}')->nullable();
            // $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal')->nullable()->change();
        });
    }
}
