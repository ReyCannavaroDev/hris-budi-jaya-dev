<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tsgp extends Migration
{
    protected $tableName = "t_sgp";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['deskripsi', 'catatan']);
            // $table->string('file_dokumen')->nullable()->change();

        });
    }
}
