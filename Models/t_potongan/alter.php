<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpotongan extends Migration
{
    protected $tableName = "t_potongan";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('jenis_potongan_id')->comment('{"src":"m_general.id"}')->nullable();
            // $table->date('date_from')->nullable();
            // $table->date('date_to')->nullable();
            // $table->boolean('is_all_kary')->default(0);
            // $table->decimal('percentage', 30 , 50 , 100)->nullable();
            // $table->boolean('is_lunas')->default(1);
            $table->string('status_bayar')->nullable();

        });
    }
}
