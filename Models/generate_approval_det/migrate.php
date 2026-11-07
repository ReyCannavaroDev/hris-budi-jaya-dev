<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class generateapprovaldet extends Migration
{
    protected $tableName = "generate_approval_det";

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id()->from(1);
            $table->bigInteger('generate_approval_id')->comment('{"src":"generate_approval.id"}')->nullable();
            $table->integer('level')->default(1);
            $table->integer('urutan_level')->default(1);
            $table->string('tipe');
            $table->bigInteger('m_role_id')->comment('{"src":"m_role.id"}')->nullable();
            $table->bigInteger('default_user_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->boolean('is_full_approve')->default(0)->nullable();
            $table->boolean('is_skippable')->default(0)->nullable();
            $table->datetime('assigned_at')->default('now()');
            $table->string('action_type')->nullable();
            $table->bigInteger('action_user_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->datetime('action_at')->nullable();
            $table->string('action_note')->nullable();
            $table->boolean('is_done')->default(false);
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