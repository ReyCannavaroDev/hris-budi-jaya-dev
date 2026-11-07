<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tcuti extends Migration
{
    protected $tableName = "t_cuti";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->string('attachment')->nullable();
            // $table->integer('interval')->nullable()->change();
            $table->integer('interval_min')->nullable();
        });
    }
}
