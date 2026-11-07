<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultErrorLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_error_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('modul')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('user_ip', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('url_frontend')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('error_log')->nullable();
            
            $table->string('exception_code', 25)->nullable();
            $table->string('http_code', 25)->nullable();
            $table->longText('file')->nullable();
            $table->string('line', 20)->nullable();
            $table->string('method', 20)->nullable();

            $table->string('status', 100)->nullable()->default('STORED');

            $table->string('developer')->nullable();
            $table->longText('developer_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_error_logs');
    }
}
