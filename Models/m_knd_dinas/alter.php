<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mknddinas extends Migration
{
    protected $tableName = "m_knd_dinas";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();

        });
    }
}
