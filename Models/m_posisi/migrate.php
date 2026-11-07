<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mposisi extends Migration
{
    protected $tableName = "m_posisi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->string('kode', 50)->nullable();
            $table->text('desc_kerja');
            $table->text('desc_kerja_1')->nullable();
            $table->text('desc_kerja_2')->nullable();
            $table->string('min_pengalaman')->nullable();
            $table->bigInteger('min_pendidikan_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->decimal('min_gaji_pokok',12 ,2)->nullable();
            $table->decimal('max_gaji_pokok',12 ,2)->nullable();
            $table->decimal('biaya',12,2)->nullable();
            $table->bigInteger('tipe_bpjs_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->decimal('potongan_bpjs',12 ,2 )->nullable();
            $table->text('desc')->nullable();
            $table->boolean('is_active')->default(1);
            $table->bigInteger('creator_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->bigInteger('last_editor_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->timestamps();

            $table->integer('jatah_jam_lembur')->nullable();
            $table->integer('percentage')->nullable();
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