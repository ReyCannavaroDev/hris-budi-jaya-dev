<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class trpd extends Migration
{
    protected $tableName = "t_rpd";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->decimal('total_biaya_spd',22,2)->nullable()->change();
            $table->decimal('total_biaya_spd',22,2)->nullable()->change();
            $table->decimal('total_biaya_selisih',22,2)->nullable()->change();
            $table->decimal('pengambilan_spd',22,2)->nullable()->change();
            $table->bigInteger('t_spd_id')->comment('{"src":"t_spd.id"}')->nullable()->change();
        });
    }
}
