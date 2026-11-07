<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class generate_approval_det extends Model
{   
    use ModelTrait;

    protected $table    = 'generate_approval_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["generate_approval_id","level","urutan_level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","assigned_at","action_type","action_user_id","action_at","action_note","is_done","creator_id","last_editor_id"];

    public $columns     = ["id","generate_approval_id","level","urutan_level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","assigned_at","action_type","action_user_id","action_at","action_note","is_done","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","generate_approval_id:bigint","level:integer","urutan_level:integer","tipe:string:191","m_role_id:bigint","default_user_id:bigint","is_full_approve:boolean","is_skippable:boolean","assigned_at:datetime","action_type:string:191","action_user_id:bigint","action_at:datetime","action_note:string:191","is_done:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["generate_approval.id=generate_approval_det.generate_approval_id","m_role.id=generate_approval_det.m_role_id","default_users.id=generate_approval_det.default_user_id","default_users.id=generate_approval_det.action_user_id","default_users.id=generate_approval_det.creator_id","default_users.id=generate_approval_det.last_editor_id"];
    public $details     = [];
    public $heirs       = ["generate_approval","generate_approval_log"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["level","urutan_level","tipe","assigned_at","is_done"];
    public $createable  = ["generate_approval_id","level","urutan_level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","assigned_at","action_type","action_user_id","action_at","action_note","is_done","creator_id","last_editor_id"];
    public $updateable  = ["generate_approval_id","level","urutan_level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","assigned_at","action_type","action_user_id","action_at","action_note","is_done","creator_id","last_editor_id"];
    public $searchable  = ["id","generate_approval_id","level","urutan_level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","assigned_at","action_type","action_user_id","action_at","action_note","is_done","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function generate_approval() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\generate_approval', 'generate_approval_id', 'id');
    }
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_role', 'm_role_id', 'id');
    }
    public function default_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_user_id', 'id');
    }
    public function action_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'action_user_id', 'id');
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
