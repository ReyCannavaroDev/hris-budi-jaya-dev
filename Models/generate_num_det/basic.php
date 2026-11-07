<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class generate_num_det extends Model
{   
    use ModelTrait;

    protected $table    = 'generate_num_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["generate_num_id","generate_num_type_id","generate_num_type","ref_compare","seq","creator_id","last_editor_id"];

    public $columns     = ["id","generate_num_id","generate_num_type_id","generate_num_type","ref_compare","seq","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","generate_num_id:bigint","generate_num_type_id:bigint","generate_num_type:string:191","ref_compare:string:191","seq:integer","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["generate_num.id=generate_num_det.generate_num_id","generate_num_type.id=generate_num_det.generate_num_type_id","default_users.id=generate_num_det.creator_id","default_users.id=generate_num_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["generate_num_type_id","seq"];
    public $createable  = ["generate_num_id","generate_num_type_id","generate_num_type","ref_compare","seq","creator_id","last_editor_id"];
    public $updateable  = ["generate_num_id","generate_num_type_id","generate_num_type","ref_compare","seq","creator_id","last_editor_id"];
    public $searchable  = ["id","generate_num_id","generate_num_type_id","generate_num_type","ref_compare","seq","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function generate_num() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\generate_num', 'generate_num_id', 'id');
    }
    public function generate_num_type() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\generate_num_type', 'generate_num_type_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
