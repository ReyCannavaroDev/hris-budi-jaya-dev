<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_perhitungan_gaji extends Model
{   
    use ModelTrait;

    protected $table    = 't_perhitungan_gaji';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","detail_gaji","periode_id","deskripsi","status","creator_id","last_editor_id","type_perhitungan"];

    public $columns     = ["id","nomor","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","detail_gaji","periode_id","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","type_perhitungan"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_kary_id:bigint","m_kary_dir_id:bigint","m_kary_divisi_id:bigint","m_kary_dept_id:bigint","periode:string:191","periode_in_date:date","total_gaji:decimal","total_tax:decimal","netto:decimal","detail_gaji:json","periode_id:bigint","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","type_perhitungan:string:191"];
    public $rules       = [];
    public $joins       = ["m_kary.id=t_perhitungan_gaji.m_kary_id","m_dir.id=t_perhitungan_gaji.m_kary_dir_id","m_divisi.id=t_perhitungan_gaji.m_kary_divisi_id","m_dept.id=t_perhitungan_gaji.m_kary_dept_id","m_general.id=t_perhitungan_gaji.periode_id","default_users.id=t_perhitungan_gaji.creator_id","default_users.id=t_perhitungan_gaji.last_editor_id"];
    public $details     = [];
    public $heirs       = ["t_final_gaji_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","periode","total_gaji","total_tax","netto","detail_gaji"];
    public $createable  = ["nomor","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","detail_gaji","periode_id","deskripsi","status","creator_id","last_editor_id","type_perhitungan"];
    public $updateable  = ["nomor","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","detail_gaji","periode_id","deskripsi","status","creator_id","last_editor_id","type_perhitungan"];
    public $searchable  = ["id","nomor","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","detail_gaji","periode_id","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","type_perhitungan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function m_kary_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_kary_dir_id', 'id');
    }
    public function m_kary_divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_kary_divisi_id', 'id');
    }
    public function m_kary_dept() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_kary_dept_id', 'id');
    }
    public function periode() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'periode_id', 'id');
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
