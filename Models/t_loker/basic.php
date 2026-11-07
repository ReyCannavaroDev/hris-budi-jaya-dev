<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_loker extends Model
{   
    use ModelTrait;

    protected $table    = 't_loker';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_dept_id:bigint","tanggal:date","jenis_loker_id:bigint","prioritas_id:bigint","tgl_dibuka:date","tgl_akhir:date","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_loker.m_comp_id","m_dir.id=t_loker.m_dir_id","m_dept.id=t_loker.m_dept_id","m_general.id=t_loker.jenis_loker_id","m_general.id=t_loker.prioritas_id","default_users.id=t_loker.creator_id","default_users.id=t_loker.last_editor_id"];
    public $details     = [];
    public $heirs       = ["t_pelamar"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_dept_id","tanggal","jenis_loker_id","prioritas_id","tgl_dibuka","tgl_akhir","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
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
    public function m_dept() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_id', 'id');
    }
    public function jenis_loker() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jenis_loker_id', 'id');
    }
    public function prioritas() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'prioritas_id', 'id');
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
