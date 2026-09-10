<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mliburnasional extends Migration
{
    protected $tableName = "m_libur_nasional";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->renameColumn('comp_id', 'm_dir_id');
            // $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable()->change();
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable()->change();

        });
    }
}
