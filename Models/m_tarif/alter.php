<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarif extends Migration
{
    protected $tableName = "m_tarif";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('keterangan')->nullable()->change();
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('m_tarif_group_id')->comment('{"src" : "m_tarif_group.id"}')->change();
        });
    }
}
