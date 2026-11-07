<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","nama_pelamar","ktp_no","tanggal","ref","telp","jk_id","tgl_lahir","salary","deskripsi","status","creator_id","last_editor_id","m_divisi_id","m_dept_id","m_posisi_id","tempat_lahir","t_loker_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","nama_pelamar","ktp_no","tanggal","ref","telp","jk_id","tgl_lahir","salary","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","m_divisi_id","m_dept_id","m_posisi_id","tempat_lahir","t_loker_id"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","nama_pelamar:string:191","ktp_no:string:191","tanggal:date","ref:string:191","telp:string:191","jk_id:bigint","tgl_lahir:date","salary:decimal","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","m_divisi_id:bigint","m_dept_id:bigint","m_posisi_id:bigint","tempat_lahir:string:100","t_loker_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_pelamar.m_comp_id","m_dir.id=t_pelamar.m_dir_id","m_general.id=t_pelamar.jk_id","default_users.id=t_pelamar.creator_id","default_users.id=t_pelamar.last_editor_id","m_divisi.id=t_pelamar.m_divisi_id","m_dept.id=t_pelamar.m_dept_id","m_posisi.id=t_pelamar.m_posisi_id","t_loker.id=t_pelamar.t_loker_id"];
    public $details     = ["t_pelamar_det_bhs","t_pelamar_det_kartu","t_pelamar_det_org","t_pelamar_det_pel","t_pelamar_det_pend","t_pelamar_det_peng","t_pelamar_det_pk","t_pelamar_det_pres"];
    public $heirs       = ["m_kary","t_hasil_tes"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_pelamar","ktp_no","ref","telp","tgl_lahir","salary","deskripsi"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","nama_pelamar","ktp_no","tanggal","ref","telp","jk_id","tgl_lahir","salary","deskripsi","status","creator_id","last_editor_id","m_divisi_id","m_dept_id","m_posisi_id","tempat_lahir","t_loker_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","nama_pelamar","ktp_no","tanggal","ref","telp","jk_id","tgl_lahir","salary","deskripsi","status","creator_id","last_editor_id","m_divisi_id","m_dept_id","m_posisi_id","tempat_lahir","t_loker_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","nama_pelamar","ktp_no","tanggal","ref","telp","jk_id","tgl_lahir","salary","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","m_divisi_id","m_dept_id","m_posisi_id","tempat_lahir","t_loker_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_pelamar_det_bhs() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_bhs', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_kartu() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_kartu', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_org() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_org', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_pel() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_pel', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_pend() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_pend', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_peng() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_peng', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_pk() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_pk', 't_pelamar_id', 'id');
    }
    public function t_pelamar_det_pres() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pelamar_det_pres', 't_pelamar_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function jk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jk_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function m_divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_divisi_id', 'id');
    }
    public function m_dept() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_id', 'id');
    }
    public function m_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_id', 'id');
    }
    public function t_loker() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_loker', 't_loker_id', 'id');
    }
}
