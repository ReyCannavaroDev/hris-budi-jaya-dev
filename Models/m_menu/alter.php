<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mmenu extends Migration
{
    protected $tableName = "m_menu";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
        });
    }
}
