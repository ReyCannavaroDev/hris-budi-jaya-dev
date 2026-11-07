<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mstandartgaji extends Migration
{
    protected $tableName = "m_standart_gaji";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->string('kode',50)->nullable();
            $table->bigInteger('m_divisi_id')->comment('{"src":"m_divisi.id"}')->nullable();
            $table->bigInteger('m_dept_id')->comment('{"src":"m_dept.id"}')->nullable();
            $table->bigInteger('m_zona_id')->comment('{"src":"m_zona.id"}')->nullable();
            $table->bigInteger('m_posisi_id')->comment('{"src":"m_posisi.id"}')->nullable();
            $table->bigInteger('grading_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->decimal('gaji_pokok',12,2);
            $table->string('gaji_pokok_periode',50)->nullable();
            $table->decimal('uang_saku',12,2)->nullable();
            $table->string('uang_saku_periode',50)->nullable();
            $table->decimal('tunjangan_posisi',12,2)->nullable();
            $table->string('tunjangan_posisi_periode',50)->nullable();
            $table->bigInteger('tunjangan_kemahalan_id')->comment('{"src":"m_tunj_kemahalan.id"}')->nullable();
            $table->string('tunjangan_kemahalan_periode',50)->nullable();
            $table->decimal('uang_makan',12,2)->nullable();
            $table->string('uang_makan_periode',50)->nullable();
            $table->decimal('tunjangan_tetap',12,2)->nullable();
            $table->string('tunjangan_tetap_periode',50)->nullable();
           
            $table->text('desc')->nullable();
            $table->boolean('is_active')->default(1);
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