1<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class defaultusers extends Migration
{
    protected $tableName = "default_users";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->string('telp')->nullable();
            // $table->bigInteger('m_kary_id')->comment('{"src":"m_kary.id"}')->nullable()->change();
            // $table->string('test');
            // $table->bigInteger('updated_id')->nullable();
        });
    }
}
