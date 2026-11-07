<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tpelamardetkartu extends Migration
{
    protected $tableName = "t_pelamar_det_kartu";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('t_pelamar_id')->comment('{"fk":"t_pelamar.id"}')->nullable();
            $table->string('ktp_no',25)->nullable();
            $table->string('ktp_foto')->nullable();
            $table->string('pas_foto')->nullable();
            $table->string('kk_no',25)->nullable();
            $table->string('kk_foto')->nullable();
            $table->string('npwp_no',25)->nullable();
            $table->string('npwp_foto')->nullable();
            $table->date('npwp_tgl_berlaku')->nullable();
            $table->bigInteger('bpjs_tipe_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->string('bpjs_no',30)->nullable();
            $table->string('bpjs_foto')->nullable();
            $table->string('berkas_lain')->nullable();
            $table->text('desc_file')->nullable();

            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->timestamps();
        });

        table_config($this->tableName, [
            "guarded"       => ["id"],
            "required"      => [],
            "!createable"   => ["id","created_at","updated_at"],
            "!updateable"   => ["id","created_at","updated_at"],
            "searchable"    => "all",
            "deleteable"    => "true",
            "deleteOnUse"   => "false",
            "extendable"    => "false",
            "casts"     => [
                'created_at' => 'datetime:d/m/Y H:i',
                'updated_at' => 'datetime:d/m/Y H:i'
            ]
        ]);

        // if( $data = \Cache::pull($this->tableName) ){
        //     $fixedData = json_decode( json_encode( $data ), true );
        //     \DB::table($this->tableName)->insert( $fixedData );
        // }
    }
    public function down()
    {
        // if( Schema::hasTable($this->tableName) ){
        //     \Cache::put($this->tableName, \DB::table($this->tableName)->get(), 60*30 );
        // }
        Schema::dropIfExists($this->tableName);
    }
}