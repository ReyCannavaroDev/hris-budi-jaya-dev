<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mgraded extends Migration
{
    protected $tableName = "m_grade_d";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);

            $table->bigInteger('m_grade_id')->comment('{"src":"m_general.id"}');
            $table->string('keterangan');
            $table->string('type');
            $table->decimal('value',12,2)->default(0);
            $table->string('factor');
            $table->string('day')->nullable();
            $table->boolean('big_event')->default(false);
            $table->boolean('full_week')->default(false);
            $table->boolean('is_7_5')->default(false);
            $table->boolean('need_overtime')->default(false);
            $table->integer('value_overtime')->default(0);
            $table->boolean('not_checkin')->default(false);
            $table->boolean('is_month')->default(false);
            $table->boolean('is_active')->default(true);

            //Penting
            $table->bigInteger('creator_id')->nullable();
            $table->bigInteger('last_editor_id')->nullable();
            $table->timestamps();
            $table->bigInteger('deletor_id')->nullable();
            $table->datetime('deleted_at')->nullable();
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