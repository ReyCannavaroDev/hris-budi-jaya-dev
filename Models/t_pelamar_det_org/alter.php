<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpelamardetorg extends Migration
{
    protected $tableName = "t_pelamar_det_org";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->bigInteger('jenis_org_id')->comment('{"src":"m_general.id"}')->nullable()->change();
        });
    }
}
