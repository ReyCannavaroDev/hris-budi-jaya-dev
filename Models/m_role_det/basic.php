<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_role_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_role_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_role_id","m_comp_id","m_dir_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id"];

    public $columns     = ["id","m_role_id","m_comp_id","m_dir_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_role_id:bigint","m_comp_id:bigint","m_dir_id:bigint","m_menu_id:bigint","can_create:boolean","can_read:boolean","can_update:boolean","can_delete:boolean","can_verify:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_role.id=m_role_det.m_role_id","m_comp.id=m_role_det.m_comp_id","m_dir.id=m_role_det.m_dir_id","m_menu.id=m_role_det.m_menu_id","default_users.id=m_role_det.creator_id","default_users.id=m_role_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_role_id","m_comp_id","m_dir_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id"];
    public $updateable  = ["m_role_id","m_comp_id","m_dir_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id"];
    public $searchable  = ["id","m_role_id","m_comp_id","m_dir_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_role', 'm_role_id', 'id');
    }
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_menu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_menu', 'm_menu_id', 'id');
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
