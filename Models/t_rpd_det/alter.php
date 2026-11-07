<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class trpddet extends Migration
{
    protected $tableName = "t_rpd_det";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->bigInteger('tipe_spd_id')->comment('{"src":"m_general.id"}')->nullable()->change();
            $table->decimal('biaya',22,2)->nullable()->change();
            $table->bigInteger('t_spd_det_id')->nullable()->change();
            $table->decimal('biaya',22,2)->nullable()->change();
        });
    }
}
