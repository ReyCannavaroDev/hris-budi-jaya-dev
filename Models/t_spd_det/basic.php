<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spd_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_spd_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_spd_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","keterangan","catatan_realisasi","is_now","creator_id","last_editor_id"];

    public $columns     = ["id","t_spd_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","keterangan","catatan_realisasi","is_now","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_spd_id:bigint","tipe_spd_id:bigint","biaya:decimal","biaya_realisasi:decimal","detail_transport:json","m_knd_dinas_id:bigint","is_kendaraan_dinas:boolean","keterangan:text","catatan_realisasi:string:191","is_now:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_spd.id=t_spd_det.t_spd_id","m_general.id=t_spd_det.tipe_spd_id","m_knd_dinas.id=t_spd_det.m_knd_dinas_id","default_users.id=t_spd_det.creator_id","default_users.id=t_spd_det.last_editor_id"];
    public $details     = [];
    public $heirs       = ["t_rpd_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_spd_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","keterangan","catatan_realisasi","is_now","creator_id","last_editor_id"];
    public $updateable  = ["t_spd_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","keterangan","catatan_realisasi","is_now","creator_id","last_editor_id"];
    public $searchable  = ["id","t_spd_id","tipe_spd_id","biaya","biaya_realisasi","detail_transport","m_knd_dinas_id","is_kendaraan_dinas","keterangan","catatan_realisasi","is_now","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spd', 't_spd_id', 'id');
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
