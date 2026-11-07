<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tlembur extends Migration
{
    protected $tableName = "t_lembur";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->integer('interval_min')->nullable();
            // $table->bigInteger('pic_id')->comment('{"src":"default_users.id"}')->nullable()->change();
            // $table->string('ref_table')->nullable();
            // $table->bigInteger('ref_id')->nullable();
        });
    }
}
