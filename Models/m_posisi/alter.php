<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mposisi extends Migration
{
    protected $tableName = "m_posisi";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['ongkos_lembur' ]);
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            // $table->integer('jatah_jam_lembur')->nullable();
            // $table->integer('ongkos_lembur')->nullable();
            // $table->integer('percentage')->nullable();
        });
    }
}
