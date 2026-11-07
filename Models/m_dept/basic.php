<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_dept extends Model
{   
    use ModelTrait;

    protected $table    = 'm_dept';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_dir_id","m_divisi_id","nama","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_dir_id","m_divisi_id","nama","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_dir_id:bigint","m_divisi_id:bigint","nama:string:100","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_dir.id=m_dept.m_dir_id","m_divisi.id=m_dept.m_divisi_id","default_users.id=m_dept.creator_id","default_users.id=m_dept.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_kary","m_lembur","m_spd","m_standart_gaji","m_tunj_kemahalan","t_final_gaji_det","t_grup_kerja","t_jadwal_kerja","t_jadwal_kerja_det","t_loker","t_mutasi","t_mutasi","t_pelamar","t_perhitungan_gaji","t_spd"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active"];
    public $createable  = ["m_dir_id","m_divisi_id","nama","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_dir_id","m_divisi_id","nama","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_dir_id","m_divisi_id","nama","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_divisi_id', 'id');
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
