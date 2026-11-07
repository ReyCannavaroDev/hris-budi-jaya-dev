<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_final_gaji_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_final_gaji_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_final_gaji_id","t_perhitungan_gaji_id","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","periode_id","deskripsi","status","creator_id","last_editor_id"];

    public $columns     = ["id","t_final_gaji_id","t_perhitungan_gaji_id","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","periode_id","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_final_gaji_id:bigint","t_perhitungan_gaji_id:bigint","m_kary_id:bigint","m_kary_dir_id:bigint","m_kary_divisi_id:bigint","m_kary_dept_id:bigint","periode:string:191","periode_in_date:date","total_gaji:decimal","total_tax:decimal","netto:decimal","periode_id:bigint","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_final_gaji.id=t_final_gaji_det.t_final_gaji_id","t_perhitungan_gaji.id=t_final_gaji_det.t_perhitungan_gaji_id","m_kary.id=t_final_gaji_det.m_kary_id","m_dir.id=t_final_gaji_det.m_kary_dir_id","m_divisi.id=t_final_gaji_det.m_kary_divisi_id","m_dept.id=t_final_gaji_det.m_kary_dept_id","m_general.id=t_final_gaji_det.periode_id","default_users.id=t_final_gaji_det.creator_id","default_users.id=t_final_gaji_det.last_editor_id"];
    public $details     = ["t_final_gaji_det_rincian"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","periode","total_gaji","total_tax","netto"];
    public $createable  = ["t_final_gaji_id","t_perhitungan_gaji_id","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","periode_id","deskripsi","status","creator_id","last_editor_id"];
    public $updateable  = ["t_final_gaji_id","t_perhitungan_gaji_id","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","periode_id","deskripsi","status","creator_id","last_editor_id"];
    public $searchable  = ["id","t_final_gaji_id","t_perhitungan_gaji_id","m_kary_id","m_kary_dir_id","m_kary_divisi_id","m_kary_dept_id","periode","periode_in_date","total_gaji","total_tax","netto","periode_id","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_final_gaji_det_rincian() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_final_gaji_det_rincian', 't_final_gaji_det_id', 'id');
    }
    
    
    public function t_final_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_final_gaji', 't_final_gaji_id', 'id');
    }
    public function t_perhitungan_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_perhitungan_gaji', 't_perhitungan_gaji_id', 'id');
    }
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
