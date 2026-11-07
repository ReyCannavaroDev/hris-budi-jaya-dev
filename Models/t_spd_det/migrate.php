<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tspddet extends Migration
{
    protected $tableName = "t_spd_det";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('t_spd_id')->comment('{"fk":"t_spd.id"}')->nullable();
            $table->bigInteger('tipe_spd_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->decimal('biaya',12 ,2)->nullable();
            $table->decimal('biaya_realisasi',12 ,2)->nullable();
            $table->jsonb('detail_transport')->nullable();
            $table->bigInteger('m_knd_dinas_id')->comment('{"src":"m_knd_dinas.id"}')->nullable();
            $table->boolean('is_kendaraan_dinas')->default(0)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('catatan_realisasi')->nullable();
            $table->boolean('is_now')->nullable();
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