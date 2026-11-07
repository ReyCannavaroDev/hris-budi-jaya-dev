<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_riwayat_posisi_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_riwayat_posisi_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_riwayat_posisi_id","status_kary_id","grading_id","date_awal","date_akhir","is_now","creator_id","last_editor_id"];

    public $columns     = ["id","t_riwayat_posisi_id","status_kary_id","grading_id","date_awal","date_akhir","is_now","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_riwayat_posisi_id:bigint","status_kary_id:bigint","grading_id:bigint","date_awal:date","date_akhir:date","is_now:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_riwayat_posisi.id=t_riwayat_posisi_det.t_riwayat_posisi_id","m_general.id=t_riwayat_posisi_det.status_kary_id","m_general.id=t_riwayat_posisi_det.grading_id","default_users.id=t_riwayat_posisi_det.creator_id","default_users.id=t_riwayat_posisi_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status_kary_id","grading_id","date_awal","date_akhir","is_now"];
    public $createable  = ["t_riwayat_posisi_id","status_kary_id","grading_id","date_awal","date_akhir","is_now","creator_id","last_editor_id"];
    public $updateable  = ["t_riwayat_posisi_id","status_kary_id","grading_id","date_awal","date_akhir","is_now","creator_id","last_editor_id"];
    public $searchable  = ["id","t_riwayat_posisi_id","status_kary_id","grading_id","date_awal","date_akhir","is_now","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_riwayat_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_riwayat_posisi', 't_riwayat_posisi_id', 'id');
    }
    public function status_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'status_kary_id', 'id');
    }
    public function grading() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'grading_id', 'id');
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
