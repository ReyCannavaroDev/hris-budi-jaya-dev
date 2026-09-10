<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkary extends Migration
{
    protected $tableName = "m_kary";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->bigInteger('m_divisi_id')->comment('{"src":"m_divisi.id"}')->nullable();
            $table->bigInteger('m_dept_id')->comment('{"src":"m_dept.id"}')->nullable();
            $table->bigInteger('m_zona_id')->comment('{"src":"m_zona.id"}')->nullable();
            $table->bigInteger('grading_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('costcontre_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->string('kode', 100)->nullable();
            $table->bigInteger('m_posisi_id')->comment('{"src":"m_posisi.id"}')->nullable();
            $table->bigInteger('m_standart_gaji_id')->comment('{"src":"m_standart_gaji.id"}')->nullable();
            $table->bigInteger('m_jam_kerja_id')->comment('{"src":"m_jam_kerja.id"}')->nullable();
            $table->string('kode_presensi', 100)->nullable();
            $table->string('nik',20)->nullable();
            $table->string('nama_depan', 100)->nullable();
            $table->string('nama_belakang', 100)->nullable();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('nama_panggilan', 100)->nullable();
            $table->bigInteger('jk_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->bigInteger('provinsi_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('kota_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('kecamatan_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->string('kode_pos',10)->nullable();
            $table->text('alamat_asli')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('no_tlp',20)->nullable();
            $table->string('no_tlp_lainnya',20)->nullable();
            $table->string('no_darurat',20)->nullable();
            $table->string('nama_kontak_darurat', 100)->nullable();
            $table->bigInteger('agama_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('gol_darah_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('status_nikah_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('tanggungan_id')->nullable();
            $table->string('hub_dgn_karyawan', 100)->nullable();

            $table->integer('cuti_jatah_reguler')->nullable();
            $table->integer('cuti_sisa_reguler')->nullable();
            $table->integer('cuti_panjang')->nullable();
            $table->integer('cuti_sisa_panjang')->nullable();

            $table->bigInteger('status_kary_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->date('lama_kontrak_awal')->nullable();
            $table->date('lama_kontrak_akhir')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_berhenti')->nullable();
            $table->text('alasan_berhenti')->nullable();
            
            $table->string('uk_baju',50)->nullable();
            $table->string('uk_celana',50)->nullable();
            $table->string('uk_sepatu',50)->nullable();
            $table->text('desc')->nullable();
            
            $table->timestamps();

            $table->bigInteger('presensi_lokasi_default_id')->comment('{"src":"presensi_lokasi.id"}')->nullable();
            $table->date('exp_date_cuti')->nullable();
            $table->integer('limit_potong')->default(7)->nullable();
            $table->bigInteger('atasan_id')->comment('{"src":"m_kary.id"}')->nullable();

            $table->decimal('cuti_p24',10,0)->defaut(120)->nullable();
            $table->decimal('cuti_sisa_p24',10,0)->defaut(120)->nullable();

            $table->bigInteger('tipe_jam_kerja_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->bigInteger('t_jadwal_kerja_id')->comment('{"src":"m_general.id"}')->nullable();

            $table->string('ig',100)->nullable();
            $table->string('x',100)->nullable();
            $table->string('facebook',100)->nullable();
            $table->string('linkedin',100)->nullable();
            $table->string('email',100)->nullable();

            $table->boolean('is_active')->default(true)->nullable()->change();

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