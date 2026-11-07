<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class generateapprovallog extends Migration
{
    protected $tableName = "generate_approval_log";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('m_comp_id')->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->comment('{"src":"m_dir.id"}')->nullable();
            $table->string('nomor')->nullable();
            $table->bigInteger('generate_approval_id')->comment('{"src":"generate_approval.id"}')->nullable();
            $table->bigInteger('generate_approval_det_id')->comment('{"src":"generate_approval_det.id"}')->nullable();
            $table->string('trx_table');
            $table->bigInteger('trx_id');
            $table->string('trx_name');
            $table->string('trx_nomor')->nullable();
            $table->date('trx_date');
            $table->string('trx_object')->nullable();
            $table->bigInteger('trx_creator_id')->nullable();
            $table->string('action_type')->default('PROGRESS');
            $table->bigInteger('action_user_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->datetime('action_at')->nullable();
            $table->string('action_note')->nullable();
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