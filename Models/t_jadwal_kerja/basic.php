<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_jadwal_kerja extends Model
{   
    use ModelTrait;

    protected $table    = 't_jadwal_kerja';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","tipe_jam_kerja_id","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","tipe_jam_kerja_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_divisi_id:bigint","m_dept_id:bigint","tipe_jam_kerja_id:bigint","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_jadwal_kerja.m_comp_id","m_dir.id=t_jadwal_kerja.m_dir_id","m_divisi.id=t_jadwal_kerja.m_divisi_id","m_dept.id=t_jadwal_kerja.m_dept_id","m_general.id=t_jadwal_kerja.tipe_jam_kerja_id","default_users.id=t_jadwal_kerja.creator_id","default_users.id=t_jadwal_kerja.last_editor_id"];
    public $details     = ["t_jadwal_kerja_det_hari"];
    public $heirs       = ["m_kary","presensi_absensi","t_jadwal_kerja_det"];
    public $detailsChild= ["t_jadwal_kerja_det"];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","tipe_jam_kerja_id","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","tipe_jam_kerja_id","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","tipe_jam_kerja_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_jadwal_kerja_det_hari() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_jadwal_kerja_det_hari', 't_jadwal_kerja_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
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
    public function tipe_jam_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_jam_kerja_id', 'id');
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
