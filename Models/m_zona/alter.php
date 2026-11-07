<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mzona extends Migration
{
    protected $tableName = "m_zona";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            $table->dropColumn(['m_tunj_kemahalan_id']);
            // $table->bigInteger('m_tunj_kemahalan_id')->comment('{"src":"m_tunj_kemahalan.id"}')->nullable();
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();
            // $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();

        });
    }
}
