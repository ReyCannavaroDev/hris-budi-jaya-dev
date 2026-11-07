<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_berkas extends Model
{   
    use ModelTrait;

    protected $table    = 'm_berkas';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama","kategori","desc","url","creator_id","last_editor_id"];

    public $columns     = ["id","nama","kategori","desc","url","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama:string:191","kategori:string:191","desc:text","url:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","kategori","url"];
    public $createable  = ["nama","kategori","desc","url","creator_id","last_editor_id"];
    public $updateable  = ["nama","kategori","desc","url","creator_id","last_editor_id"];
    public $searchable  = ["id","nama","kategori","desc","url","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
