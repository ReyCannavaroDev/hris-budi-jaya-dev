<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspddet extends Migration
{
    protected $tableName = "t_spd_det";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            $table->decimal('biaya',12 ,2)->nullable()->change();
            $table->decimal('biaya_realisasi', 12 ,2)->nullable()->change();
        });
    }
}
