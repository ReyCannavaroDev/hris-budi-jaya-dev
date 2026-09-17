<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Defaults\User;

class DefaultUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username',60)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('m_comp_id')->default(1)->comment('{"src":"m_comp.id"}')->nullable();
            $table->bigInteger('m_dir_id')->default(1)->comment('{"src":"m_dir.id"}')->nullable();
            $table->boolean('is_active')->default(1);
            $table->bigInteger('creator_id')->nullable();
            $table->bigInteger('m_kary_id')->comment('{"src":"m_kary.id"}')->nullable();
            $table->bigInteger('last_editor_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        $hasher = app()->make('hash');
        User::create(
            [
                'name' => "trial",
                'email' => "trial@trial.trial",
                'username'=>"trial",
                'password' => $hasher->make("trial"),
                'm_comp_id' => 1
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_users');
    }
}
