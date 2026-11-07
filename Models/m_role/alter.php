<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mrole extends Migration
{
    protected $tableName = "m_role";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            //$table->bigInteger('m_dir_id')->default(1)->comment('{"src":"m_dir.id"}')->change();
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
        });
    }
}
