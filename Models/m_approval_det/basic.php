<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_approval_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_approval_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_approval_id","level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","creator_id","last_editor_id"];

    public $columns     = ["id","m_approval_id","level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_approval_id:bigint","level:integer","tipe:string:10","m_role_id:bigint","default_user_id:bigint","is_full_approve:boolean","is_skippable:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_approval.id=m_approval_det.m_approval_id","m_role.id=m_approval_det.m_role_id","default_users.id=m_approval_det.default_user_id","default_users.id=m_approval_det.creator_id","default_users.id=m_approval_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["level","tipe"];
    public $createable  = ["m_approval_id","level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","creator_id","last_editor_id"];
    public $updateable  = ["m_approval_id","level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","creator_id","last_editor_id"];
    public $searchable  = ["id","m_approval_id","level","tipe","m_role_id","default_user_id","is_full_approve","is_skippable","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_approval() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_approval', 'm_approval_id', 'id');
    }
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_role', 'm_role_id', 'id');
    }
    public function default_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_user_id', 'id');
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
