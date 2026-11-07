<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_jadwal_kerja_det_hari extends Model
{   
    use ModelTrait;

    protected $table    = 't_jadwal_kerja_det_hari';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_jadwal_kerja_id","day","tipe_hari","tanggal","day_num","m_jam_kerja_id","waktu_mulai","waktu_akhir"];

    public $columns     = ["id","t_jadwal_kerja_id","day","tipe_hari","tanggal","day_num","m_jam_kerja_id","waktu_mulai","waktu_akhir","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_jadwal_kerja_id:bigint","day:string:191","tipe_hari:string:191","tanggal:date","day_num:integer","m_jam_kerja_id:bigint","waktu_mulai:time","waktu_akhir:time","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_jadwal_kerja.id=t_jadwal_kerja_det_hari.t_jadwal_kerja_id","m_jam_kerja.id=t_jadwal_kerja_det_hari.m_jam_kerja_id"];
    public $details     = ["t_jadwal_kerja_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["day","tipe_hari","day_num","waktu_mulai","waktu_akhir"];
    public $createable  = ["t_jadwal_kerja_id","day","tipe_hari","tanggal","day_num","m_jam_kerja_id","waktu_mulai","waktu_akhir"];
    public $updateable  = ["t_jadwal_kerja_id","day","tipe_hari","tanggal","day_num","m_jam_kerja_id","waktu_mulai","waktu_akhir"];
    public $searchable  = ["id","t_jadwal_kerja_id","day","tipe_hari","tanggal","day_num","m_jam_kerja_id","waktu_mulai","waktu_akhir","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_jadwal_kerja_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_jadwal_kerja_det', 't_jadwal_kerja_det_hari_id', 'id');
    }
    
    
    public function t_jadwal_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jadwal_kerja', 't_jadwal_kerja_id', 'id');
    }
    public function m_jam_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_jam_kerja', 'm_jam_kerja_id', 'id');
    }
}
