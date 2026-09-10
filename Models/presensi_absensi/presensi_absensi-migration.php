<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class presensiabsensi extends Migration
{
    protected $tableName = "presensi_absensi";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->default(1)->nullable();
            $table->bigInteger('default_user_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->date('tanggal');
            $table->string('status')->enum(['WORKING','ATTEND','ATTEND NO CHECKOUT','NOT ATTEND'])->default('WORKING');
            $table->time('checkin_time');
            $table->string('checkin_foto');
            $table->string('checkin_lat');
            $table->string('checkin_long');
            $table->string('checkin_address');
            $table->string('checkin_region');
            $table->boolean('checkin_on_scope');
            $table->text('catatan_in')->nullable();
            $table->time('checkout_time')->nullable();
            $table->string('checkout_foto')->nullable();
            $table->string('checkout_lat')->nullable();
            $table->string('checkout_long')->nullable();
            $table->string('checkout_address')->nullable();
            $table->string('checkout_region')->nullable();
            $table->boolean('checkout_on_scope')->nullable();
            $table->text('catatan_out')->nullable();
            $table->bigInteger('creator_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->bigInteger('last_editor_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->timestamps();
            $table->text('catatan')->nullable();

            $table->boolean('is_lembur')->nullable();
            $table->string('shift')->nullable();
            $table->bigInteger('jadwal_kerja_id')->comment('{"src":"jadwal_kerja.id"}')->nullable();
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