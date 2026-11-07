<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_kel extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_kel';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","m_kary_id","keluarga_id","nama","pend_terakhir_id","jk_id","pekerjaan_id","usia","desc","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","m_kary_id","keluarga_id","nama","pend_terakhir_id","jk_id","pekerjaan_id","usia","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","keluarga_id:bigint","nama:string:100","pend_terakhir_id:bigint","jk_id:bigint","pekerjaan_id:bigint","usia:integer","desc:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_kary_det_kel.m_comp_id","m_dir.id=m_kary_det_kel.m_dir_id","m_kary.id=m_kary_det_kel.m_kary_id","m_general.id=m_kary_det_kel.keluarga_id","m_general.id=m_kary_det_kel.pend_terakhir_id","m_general.id=m_kary_det_kel.jk_id","m_general.id=m_kary_det_kel.pekerjaan_id","default_users.id=m_kary_det_kel.creator_id","default_users.id=m_kary_det_kel.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","pend_terakhir_id","jk_id","pekerjaan_id","usia"];
    public $createable  = ["m_comp_id","m_dir_id","m_kary_id","keluarga_id","nama","pend_terakhir_id","jk_id","pekerjaan_id","usia","desc","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","m_kary_id","keluarga_id","nama","pend_terakhir_id","jk_id","pekerjaan_id","usia","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","m_kary_id","keluarga_id","nama","pend_terakhir_id","jk_id","pekerjaan_id","usia","desc","creator_id","last_editor_id","created_at","updated_at"];
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
    public function keluarga() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'keluarga_id', 'id');
    }
    public function pend_terakhir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'pend_terakhir_id', 'id');
    }
    public function jk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jk_id', 'id');
    }
    public function pekerjaan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'pekerjaan_id', 'id');
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
