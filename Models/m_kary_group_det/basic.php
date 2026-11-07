<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_group_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_group_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_group_id","m_kary_id","is_pj","creator_id","last_editor_id"];

    public $columns     = ["id","m_kary_group_id","m_kary_id","is_pj","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_kary_group_id:bigint","m_kary_id:bigint","is_pj:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_kary_group.id=m_kary_group_det.m_kary_group_id","m_kary.id=m_kary_group_det.m_kary_id","default_users.id=m_kary_group_det.creator_id","default_users.id=m_kary_group_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","is_pj"];
    public $createable  = ["m_kary_group_id","m_kary_id","is_pj","creator_id","last_editor_id"];
    public $updateable  = ["m_kary_group_id","m_kary_id","is_pj","creator_id","last_editor_id"];
    public $searchable  = ["id","m_kary_group_id","m_kary_id","is_pj","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_kary_group() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary_group', 'm_kary_group_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
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
