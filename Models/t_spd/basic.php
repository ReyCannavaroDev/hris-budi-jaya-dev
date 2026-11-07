<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spd extends Model
{   
    use ModelTrait;

    protected $table    = 't_spd';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_spd_id","m_dir_id","m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","m_kary_id","pic_id","total_biaya","kegiatan","keterangan","status","creator_id","last_editor_id","is_kend_dinas","interval","catatan_kend"];

    public $columns     = ["id","nomor","m_comp_id","m_spd_id","m_dir_id","m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","m_kary_id","pic_id","total_biaya","kegiatan","keterangan","status","creator_id","last_editor_id","created_at","updated_at","is_kend_dinas","interval","catatan_kend"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_spd_id:bigint","m_dir_id:bigint","m_divisi_id:bigint","m_dept_id:bigint","m_posisi_id:bigint","tanggal:date","tgl_acara_awal:date","tgl_acara_akhir:date","jenis_spd_id:bigint","m_zona_asal_id:bigint","m_zona_tujuan_id:bigint","m_lokasi_tujuan_id:bigint","m_kary_id:bigint","pic_id:bigint","total_biaya:decimal","kegiatan:string:191","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","is_kend_dinas:boolean","interval:integer","catatan_kend:text"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_spd.m_comp_id","m_spd.id=t_spd.m_spd_id","m_dir.id=t_spd.m_dir_id","m_divisi.id=t_spd.m_divisi_id","m_dept.id=t_spd.m_dept_id","m_posisi.id=t_spd.m_posisi_id","m_general.id=t_spd.jenis_spd_id","m_zona.id=t_spd.m_zona_asal_id","m_zona.id=t_spd.m_zona_tujuan_id","m_lokasi.id=t_spd.m_lokasi_tujuan_id","m_kary.id=t_spd.m_kary_id","default_users.id=t_spd.pic_id","default_users.id=t_spd.creator_id","default_users.id=t_spd.last_editor_id"];
    public $details     = ["t_spd_det"];
    public $heirs       = ["t_rpd"];
    public $detailsChild= [];
    public $detailsHeirs= ["t_rpd_det"];
    public $unique      = [];
    public $required    = ["m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","total_biaya","is_kend_dinas"];
    public $createable  = ["nomor","m_comp_id","m_spd_id","m_dir_id","m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","m_kary_id","pic_id","total_biaya","kegiatan","keterangan","status","creator_id","last_editor_id","is_kend_dinas","interval","catatan_kend"];
    public $updateable  = ["nomor","m_comp_id","m_spd_id","m_dir_id","m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","m_kary_id","pic_id","total_biaya","kegiatan","keterangan","status","creator_id","last_editor_id","is_kend_dinas","interval","catatan_kend"];
    public $searchable  = ["id","nomor","m_comp_id","m_spd_id","m_dir_id","m_divisi_id","m_dept_id","m_posisi_id","tanggal","tgl_acara_awal","tgl_acara_akhir","jenis_spd_id","m_zona_asal_id","m_zona_tujuan_id","m_lokasi_tujuan_id","m_kary_id","pic_id","total_biaya","kegiatan","keterangan","status","creator_id","last_editor_id","created_at","updated_at","is_kend_dinas","interval","catatan_kend"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_spd_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_spd_det', 't_spd_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_spd', 'm_spd_id', 'id');
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
    public function m_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_id', 'id');
    }
    public function jenis_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jenis_spd_id', 'id');
    }
    public function m_zona_asal() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_zona', 'm_zona_asal_id', 'id');
    }
    public function m_zona_tujuan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_zona', 'm_zona_tujuan_id', 'id');
    }
    public function m_lokasi_tujuan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_lokasi', 'm_lokasi_tujuan_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function pic() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'pic_id', 'id');
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
