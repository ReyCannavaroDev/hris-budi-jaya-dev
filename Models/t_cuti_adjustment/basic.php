<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_cuti_adjustment extends Model
{   
    use ModelTrait;

    protected $table    = 't_cuti_adjustment';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","date","m_comp_id","m_dir_id","m_kary_id","tipe_cuti_id","value","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","date","m_comp_id","m_dir_id","m_kary_id","tipe_cuti_id","value","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","date:date","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","tipe_cuti_id:bigint","value:decimal","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_cuti_adjustment.m_comp_id","m_dir.id=t_cuti_adjustment.m_dir_id","m_kary.id=t_cuti_adjustment.m_kary_id","m_general.id=t_cuti_adjustment.tipe_cuti_id","default_users.id=t_cuti_adjustment.creator_id","default_users.id=t_cuti_adjustment.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["date","value","keterangan"];
    public $createable  = ["nomor","date","m_comp_id","m_dir_id","m_kary_id","tipe_cuti_id","value","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","date","m_comp_id","m_dir_id","m_kary_id","tipe_cuti_id","value","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","date","m_comp_id","m_dir_id","m_kary_id","tipe_cuti_id","value","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
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
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function tipe_cuti() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_cuti_id', 'id');
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
