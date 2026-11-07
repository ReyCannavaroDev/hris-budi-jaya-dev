<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tfinalgajidet extends Migration
{
    protected $tableName = "t_final_gaji_det";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('t_final_gaji_id')->comment('{"fk":"t_final_gaji.id"}')->nullable();
            $table->bigInteger('t_perhitungan_gaji_id')->comment('{"src":"t_perhitungan_gaji.id"}')->nullable();
            $table->bigInteger('m_kary_id')->comment('{"src":"m_kary.id"}');
            $table->bigInteger('m_kary_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->bigInteger('m_kary_divisi_id')->comment('{"src":"m_divisi.id"}')->nullable();
            $table->bigInteger('m_kary_dept_id')->comment('{"src":"m_dept.id"}')->nullable();
            $table->string('periode');
            $table->date('periode_in_date')->nullable();
            $table->decimal('total_gaji',22,2);
            $table->decimal('total_tax',22,2)->default(0);
            $table->decimal('netto',22,2);
            $table->bigInteger('periode_id')->comment('{"src":"m_general.id"}')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status',50)->default('DRAFT')->nullable();
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