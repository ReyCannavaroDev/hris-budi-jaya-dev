<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_knd_dinas extends Model
{   
    use ModelTrait;

    protected $table    = 'm_knd_dinas';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","nama","nopol","m_lokasi_id","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","nama","nopol","m_lokasi_id","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","nama:string:191","nopol:string:191","m_lokasi_id:bigint","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_knd_dinas.m_comp_id","m_dir.id=m_knd_dinas.m_dir_id","m_lokasi.id=m_knd_dinas.m_lokasi_id","default_users.id=m_knd_dinas.creator_id","default_users.id=m_knd_dinas.last_editor_id"];
    public $details     = [];
    public $heirs       = ["t_rpd_det","t_spd_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","nopol","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","nama","nopol","m_lokasi_id","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","nama","nopol","m_lokasi_id","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","nama","nopol","m_lokasi_id","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
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
    public function m_lokasi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_lokasi', 'm_lokasi_id', 'id');
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
