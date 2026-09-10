<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkary extends Migration
{
    protected $tableName = "m_kary";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['ig','x','facebook','linkedin','email']);
            // $table->integer('limit_potong')->default(7)->nullable()->change();
            // $table->string('ig',100)->nullable();
            // $table->string('x',100)->nullable();
            // $table->string('facebook',100)->nullable();
            // $table->string('linkedin',100)->nullable();
            // $table->string('test_lagi');
            $table->boolean('is_active')->default(true)->nullable()->change();

        });
    }
}
