<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_riwayat_posisi extends Model
{   
    use ModelTrait;

    protected $table    = 't_riwayat_posisi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_kary_id","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_riwayat_posisi.m_comp_id","m_dir.id=t_riwayat_posisi.m_dir_id","m_kary.id=t_riwayat_posisi.m_kary_id","default_users.id=t_riwayat_posisi.creator_id","default_users.id=t_riwayat_posisi.last_editor_id"];
    public $details     = ["t_riwayat_posisi_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_riwayat_posisi_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_riwayat_posisi_det', 't_riwayat_posisi_id', 'id');
    }
    
    
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
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
