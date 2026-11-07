<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class generate_approval extends Model
{   
    use ModelTrait;

    protected $table    = 'generate_approval';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","creator_id","last_editor_id","last_approve_id","last_approve_det_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","creator_id","last_editor_id","created_at","updated_at","last_approve_id","last_approve_det_id"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","nomor:string:191","m_approval_id:bigint","trx_id:bigint","trx_name:string:191","form_name:string:191","trx_table:string:191","trx_nomor:string:191","trx_date:date","trx_object:string:191","trx_creator_id:bigint","status:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","last_approve_id:bigint","last_approve_det_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=generate_approval.m_comp_id","m_dir.id=generate_approval.m_dir_id","m_approval.id=generate_approval.m_approval_id","default_users.id=generate_approval.creator_id","default_users.id=generate_approval.last_editor_id","default_users.id=generate_approval.last_approve_id","generate_approval_det.id=generate_approval.last_approve_det_id"];
    public $details     = [];
    public $heirs       = ["generate_approval_det","generate_approval_log"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["trx_id","trx_name","trx_table","trx_date"];
    public $createable  = ["m_comp_id","m_dir_id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","creator_id","last_editor_id","last_approve_id","last_approve_det_id"];
    public $updateable  = ["m_comp_id","m_dir_id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","creator_id","last_editor_id","last_approve_id","last_approve_det_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","creator_id","last_editor_id","created_at","updated_at","last_approve_id","last_approve_det_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_approval() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_approval', 'm_approval_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function last_approve() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_approve_id', 'id');
    }
    public function last_approve_det() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\generate_approval_det', 'last_approve_det_id', 'id');
    }
}
