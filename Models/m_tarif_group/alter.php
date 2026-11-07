<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarifgroup extends Migration
{
    protected $tableName = "m_tarif_group";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('keterangan')->nullable()->change();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
        });
    }
}
