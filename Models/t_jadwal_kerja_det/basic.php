<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_jadwal_kerja_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_jadwal_kerja_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_jadwal_kerja_det_hari_id","t_jadwal_kerja_id","m_dir_id","m_divisi_id","m_dept_id","m_kary_id","creator_id","last_editor_id"];

    public $columns     = ["id","t_jadwal_kerja_det_hari_id","t_jadwal_kerja_id","m_dir_id","m_divisi_id","m_dept_id","m_kary_id","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_jadwal_kerja_det_hari_id:bigint","t_jadwal_kerja_id:bigint","m_dir_id:bigint","m_divisi_id:bigint","m_dept_id:bigint","m_kary_id:bigint","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_jadwal_kerja_det_hari.id=t_jadwal_kerja_det.t_jadwal_kerja_det_hari_id","t_jadwal_kerja.id=t_jadwal_kerja_det.t_jadwal_kerja_id","m_dir.id=t_jadwal_kerja_det.m_dir_id","m_divisi.id=t_jadwal_kerja_det.m_divisi_id","m_dept.id=t_jadwal_kerja_det.m_dept_id","m_kary.id=t_jadwal_kerja_det.m_kary_id","default_users.id=t_jadwal_kerja_det.creator_id","default_users.id=t_jadwal_kerja_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id"];
    public $createable  = ["t_jadwal_kerja_det_hari_id","t_jadwal_kerja_id","m_dir_id","m_divisi_id","m_dept_id","m_kary_id","creator_id","last_editor_id"];
    public $updateable  = ["t_jadwal_kerja_det_hari_id","t_jadwal_kerja_id","m_dir_id","m_divisi_id","m_dept_id","m_kary_id","creator_id","last_editor_id"];
    public $searchable  = ["id","t_jadwal_kerja_det_hari_id","t_jadwal_kerja_id","m_dir_id","m_divisi_id","m_dept_id","m_kary_id","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_jadwal_kerja_det_hari() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jadwal_kerja_det_hari', 't_jadwal_kerja_det_hari_id', 'id');
    }
    public function t_jadwal_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jadwal_kerja', 't_jadwal_kerja_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_divisi_id', 'id');
    }
    public function m_dept() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
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
