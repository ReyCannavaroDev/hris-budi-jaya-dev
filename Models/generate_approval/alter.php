<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class generateapproval extends Migration
{
    protected $tableName = "generate_approval";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            //$table->dropColumn([ ]);
            // $table->bigInteger('last_approve_id')->comment('{"src":"default_users.id"}')->nullable();
            $table->bigInteger('last_approve_det_id')->comment('{"src":"generate_approval_det.id"}')->nullable();
        });
    }
}
