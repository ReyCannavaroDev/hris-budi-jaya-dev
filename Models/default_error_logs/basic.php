<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class default_error_logs extends Model
{   
    use ModelTrait;

    protected $table    = 'default_error_logs';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["modul","username","user_ip","type","url","url_frontend","payload","error_log","exception_code","http_code","file","line","method","status","developer","developer_note","created_at","updated_at"];

    public $columns     = ["id","modul","username","user_ip","type","url","url_frontend","payload","error_log","exception_code","http_code","file","line","method","status","developer","developer_note","created_at","updated_at"];
    public $columnsFull = ["id:bigint","modul:string:191","username:string:100","user_ip:string:100","type:string:100","url:string:191","url_frontend:string:191","payload:text","error_log:text","exception_code:string:25","http_code:string:25","file:text","line:string:20","method:string:20","status:string:100","developer:string:191","developer_note:text","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["modul","username","user_ip","type","url","url_frontend","payload","error_log","exception_code","http_code","file","line","method","status","developer","developer_note","created_at","updated_at"];
    public $updateable  = ["modul","username","user_ip","type","url","url_frontend","payload","error_log","exception_code","http_code","file","line","method","status","developer","developer_note","created_at","updated_at"];
    public $searchable  = ["modul","username","user_ip","type","url","url_frontend","payload","error_log","exception_code","http_code","file","line","method","status","developer","developer_note","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
