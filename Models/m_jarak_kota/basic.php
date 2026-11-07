<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_jarak_kota extends Model
{   
    use ModelTrait;

    protected $table    = 'm_jarak_kota';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","from","to","jarak","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","from","to","jarak","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","from:string:100","to:string:100","jarak:string:100","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_jarak_kota.m_comp_id","default_users.id=m_jarak_kota.creator_id","default_users.id=m_jarak_kota.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["from","to","jarak","is_active"];
    public $createable  = ["m_comp_id","from","to","jarak","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","from","to","jarak","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","from","to","jarak","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
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
