<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mkarydetpend extends Migration
{
    protected $tableName = "m_kary_det_pend";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             $table->id()->from(1);
            $table->bigInteger('m_karyawan_id')->comment('{"fk":"m_kary.id"}')->nullable();
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->bigInteger('tingkat_id')->comment('{"src":"m_general.id"}');
            $table->string('nama_sekolah',100);
            $table->integer('thn_masuk');
            $table->integer('thn_lulus');
            $table->bigInteger('kota_id')->comment('{"src":"m_general.id"}');
            $table->decimal('nilai',10,2);
            $table->string('jurusan',50);
            $table->boolean('is_pend_terakhir');
            $table->string('ijazah_no')->nullable();
            $table->string('ijazah_foto')->nullable();
            $table->text('desc')->nullable();
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