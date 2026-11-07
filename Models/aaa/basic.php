<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class aaa extends Model
{   
    use ModelTrait;

    protected $table    = 'aaa';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["seq","seq2"];

    public $columns     = ["id","seq","seq2"];
    public $columnsFull = ["id:bigint","seq:string:191","seq2:string:191"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["seq","seq2"];
    public $createable  = ["seq","seq2"];
    public $updateable  = ["seq","seq2"];
    public $searchable  = ["id","seq","seq2"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
