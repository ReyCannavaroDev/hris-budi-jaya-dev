<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_rpd_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_rpd_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_rpd_id","t_spd_det_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","catatan_realisasi","creator_id","last_editor_id"];

    public $columns     = ["id","t_rpd_id","t_spd_det_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","catatan_realisasi","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_rpd_id:bigint","t_spd_det_id:bigint","tipe_spd_id:bigint","biaya:decimal","biaya_realisasi:decimal","detail_transport:string:191","m_knd_dinas_id:bigint","is_kendaraan_dinas:boolean","catatan_realisasi:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_rpd.id=t_rpd_det.t_rpd_id","t_spd_det.id=t_rpd_det.t_spd_det_id","m_general.id=t_rpd_det.tipe_spd_id","m_knd_dinas.id=t_rpd_det.m_knd_dinas_id","default_users.id=t_rpd_det.creator_id","default_users.id=t_rpd_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["is_kendaraan_dinas"];
    public $createable  = ["t_rpd_id","t_spd_det_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","catatan_realisasi","creator_id","last_editor_id"];
    public $updateable  = ["t_rpd_id","t_spd_det_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","catatan_realisasi","creator_id","last_editor_id"];
    public $searchable  = ["id","t_rpd_id","t_spd_det_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","catatan_realisasi","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_rpd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_rpd', 't_rpd_id', 'id');
    }
    public function t_spd_det() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spd_det', 't_spd_det_id', 'id');
    }
    public function tipe_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_spd_id', 'id');
    }
    public function m_knd_dinas() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_knd_dinas', 'm_knd_dinas_id', 'id');
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
