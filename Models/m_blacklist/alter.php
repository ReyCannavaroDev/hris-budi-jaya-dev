<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mblacklist extends Migration
{
    protected $tableName = "m_blacklist";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
             $table->bigInteger('m_dir_id')->default(1)->comment('{"src":"m_dir.id"}')->nullable()->change();
        });
    }
}
