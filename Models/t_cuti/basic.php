<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_cuti extends Model
{   
    use ModelTrait;

    protected $table    = 't_cuti';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_kary_id","alasan_id","tipe_cuti_id","date_from","date_to","time_from","time_to","keterangan","status","creator_id","last_editor_id","attachment","interval","interval_min"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","alasan_id","tipe_cuti_id","date_from","date_to","time_from","time_to","keterangan","status","creator_id","last_editor_id","created_at","updated_at","attachment","interval","interval_min"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","alasan_id:bigint","tipe_cuti_id:bigint","date_from:date","date_to:date","time_from:time","time_to:time","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","attachment:string:191","interval:integer","interval_min:integer"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_cuti.m_comp_id","m_dir.id=t_cuti.m_dir_id","m_kary.id=t_cuti.m_kary_id","m_general.id=t_cuti.alasan_id","m_general.id=t_cuti.tipe_cuti_id","default_users.id=t_cuti.creator_id","default_users.id=t_cuti.last_editor_id"];
    public $details     = [];
    public $heirs       = ["t_final_gaji_det_rincian"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["date_from","date_to"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","alasan_id","tipe_cuti_id","date_from","date_to","time_from","time_to","keterangan","status","creator_id","last_editor_id","attachment","interval","interval_min"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","alasan_id","tipe_cuti_id","date_from","date_to","time_from","time_to","keterangan","status","creator_id","last_editor_id","attachment","interval","interval_min"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","alasan_id","tipe_cuti_id","date_from","date_to","time_from","time_to","keterangan","status","creator_id","last_editor_id","created_at","updated_at","attachment","interval","interval_min"];
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
    public function alasan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'alasan_id', 'id');
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
