<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspd extends Migration
{
    protected $tableName = "t_spd";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->boolean('is_kend_dinas')->default(false);
                        // $table->string('status',50)->default('DRAFT')->nullable()->change();
            // $table->integer('interval')->nullable();
            // $table->bigInteger('m_spd_id')->comment('{"src":"m_spd.id"}')->nullable()->change();
             $table->bigInteger('pic_id')->comment('{"src":"default_users.id"}')->nullable()->change();
        });
    }
}
