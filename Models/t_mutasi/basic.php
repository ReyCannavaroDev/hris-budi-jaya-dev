<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_mutasi extends Model
{   
    use ModelTrait;

    protected $table    = 't_mutasi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_dept_lama_id","m_posisi_lama_id","m_standart_posisi_id","m_devisi_baru_id","m_dept_baru_id","m_posisi_baru_id","m_standart_baru_id","no_dokumen","file_dokumen","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_dept_lama_id","m_posisi_lama_id","m_standart_posisi_id","m_devisi_baru_id","m_dept_baru_id","m_posisi_baru_id","m_standart_baru_id","no_dokumen","file_dokumen","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","tgl:date","status_kary_id:bigint","tipe_mutasi:string:191","m_divisi_lama_id:bigint","m_dept_lama_id:bigint","m_posisi_lama_id:bigint","m_standart_posisi_id:bigint","m_devisi_baru_id:bigint","m_dept_baru_id:bigint","m_posisi_baru_id:bigint","m_standart_baru_id:bigint","no_dokumen:string:191","file_dokumen:string:191","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_mutasi.m_comp_id","m_dir.id=t_mutasi.m_dir_id","m_kary.id=t_mutasi.m_kary_id","m_general.id=t_mutasi.status_kary_id","m_divisi.id=t_mutasi.m_divisi_lama_id","m_dept.id=t_mutasi.m_dept_lama_id","m_posisi.id=t_mutasi.m_posisi_lama_id","m_standart_gaji.id=t_mutasi.m_standart_posisi_id","m_divisi.id=t_mutasi.m_devisi_baru_id","m_dept.id=t_mutasi.m_dept_baru_id","m_posisi.id=t_mutasi.m_posisi_baru_id","m_standart_gaji.id=t_mutasi.m_standart_baru_id","default_users.id=t_mutasi.creator_id","default_users.id=t_mutasi.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_posisi_lama_id","m_devisi_baru_id","m_posisi_baru_id","no_dokumen","file_dokumen"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_dept_lama_id","m_posisi_lama_id","m_standart_posisi_id","m_devisi_baru_id","m_dept_baru_id","m_posisi_baru_id","m_standart_baru_id","no_dokumen","file_dokumen","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_dept_lama_id","m_posisi_lama_id","m_standart_posisi_id","m_devisi_baru_id","m_dept_baru_id","m_posisi_baru_id","m_standart_baru_id","no_dokumen","file_dokumen","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","tgl","status_kary_id","tipe_mutasi","m_divisi_lama_id","m_dept_lama_id","m_posisi_lama_id","m_standart_posisi_id","m_devisi_baru_id","m_dept_baru_id","m_posisi_baru_id","m_standart_baru_id","no_dokumen","file_dokumen","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
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
    public function status_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'status_kary_id', 'id');
    }
    public function m_divisi_lama() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_divisi_lama_id', 'id');
    }
    public function m_dept_lama() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_lama_id', 'id');
    }
    public function m_posisi_lama() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_lama_id', 'id');
    }
    public function m_standart_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_standart_gaji', 'm_standart_posisi_id', 'id');
    }
    public function m_devisi_baru() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_devisi_baru_id', 'id');
    }
    public function m_dept_baru() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_baru_id', 'id');
    }
    public function m_posisi_baru() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_baru_id', 'id');
    }
    public function m_standart_baru() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_standart_gaji', 'm_standart_baru_id', 'id');
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
