<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspd extends Migration
{
    protected $tableName = "t_spd";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->string('nomor',50)->nullable();
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->default(1)->nullable();
            $table->bigInteger('m_spd_id')->comment('{"src":"m_spd.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->bigInteger('m_divisi_id')->comment('{"src":"m_divisi.id"}');
            $table->bigInteger('m_dept_id')->comment('{"src":"m_dept.id"}');
            $table->bigInteger('m_posisi_id')->comment('{"src":"m_posisi.id"}');
            $table->date('tanggal');
            $table->date('tgl_acara_awal');
            $table->date('tgl_acara_akhir');
            $table->bigInteger('jenis_spd_id')->comment('{"src":"m_general.id"}');
            $table->bigInteger('m_zona_asal_id')->comment('{"src":"m_zona.id"}');
            $table->bigInteger('m_zona_tujuan_id')->comment('{"src":"m_zona.id"}');
            $table->bigInteger('m_lokasi_tujuan_id')->comment('{"src":"m_lokasi.id"}');
            $table->bigInteger('m_kary_id')->comment('{"src":"m_kary.id"}')->nullable();
            $table->bigInteger('pic_id')->comment('{"src":"default_users.id"}');
            $table->decimal('total_biaya');
            $table->string('kegiatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status',50)->default('DRAFT')->nullable();
            $table->text('catatan_kend')->nullable();
            $table->bigInteger('creator_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->bigInteger('last_editor_id')->comment('{"src":"default_users.id"}')->nullable();
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