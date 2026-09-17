<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkarydetkartu extends Migration
{
    protected $tableName = "m_kary_det_kartu";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            // $table->string('ktp_no',25)->nullable()->change();
            // $table->string('ktp_foto')->nullable()->change();
            // $table->date('npwp_tgl_berlaku')->nullable()->change();

        });
    }
}
