<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mroleaccess extends Migration
{
    protected $tableName = "m_role_access";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('comp_id', 'm_dir_id');
            //$table->bigInteger('m_dir_id')->default(1)->comment('{"src":"m_dir.id"}');
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
        });
    }
}
