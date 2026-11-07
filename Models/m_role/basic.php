<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_role extends Model
{   
    use ModelTrait;

    protected $table    = 'm_role';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","name","is_superadmin","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","name","is_superadmin","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","name:string:191","is_superadmin:boolean","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_role.m_comp_id","m_dir.id=m_role.m_dir_id","default_users.id=m_role.creator_id","default_users.id=m_role.last_editor_id"];
    public $details     = ["m_role_det"];
    public $heirs       = ["generate_approval_det","m_approval_det","m_role_access"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["name","is_superadmin","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","name","is_superadmin","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","name","is_superadmin","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","name","is_superadmin","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_role_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_role_det', 'm_role_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
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
