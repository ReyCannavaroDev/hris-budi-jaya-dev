<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tmutasi extends Migration
{
    protected $tableName = "t_mutasi";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['deskripsi', 'catatan']);
            // $table->bigInteger('m_standart_posisi_id')->comment('{"src":"m_standart_gaji.id"}')->nullable()->change();
            // $table->bigInteger('m_standart_baru_id')->comment('{"src":"m_standart_gaji.id"}')->nullable()->change();
        });
    }
}
