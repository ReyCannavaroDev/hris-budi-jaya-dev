<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_potongan extends Model
{   
    use ModelTrait;

    protected $table    = 't_potongan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_kary_id","no_doc","doc","nilai","keterangan","status","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas","creator_id","last_editor_id","status_bayar"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","no_doc","doc","nilai","keterangan","status","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas","creator_id","last_editor_id","created_at","updated_at","status_bayar"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","no_doc:string:191","doc:string:191","nilai:decimal","keterangan:text","status:string:50","jenis_potongan_id:bigint","date_from:date","date_to:date","is_all_kary:boolean","percentage:decimal","is_lunas:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","status_bayar:string:191"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_potongan.m_comp_id","m_dir.id=t_potongan.m_dir_id","m_kary.id=t_potongan.m_kary_id","m_general.id=t_potongan.jenis_potongan_id","default_users.id=t_potongan.creator_id","default_users.id=t_potongan.last_editor_id"];
    public $details     = ["t_potongan_det_bayar"];
    public $heirs       = ["t_final_gaji_det_rincian"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","nilai","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","no_doc","doc","nilai","keterangan","status","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas","creator_id","last_editor_id","status_bayar"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","no_doc","doc","nilai","keterangan","status","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas","creator_id","last_editor_id","status_bayar"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","no_doc","doc","nilai","keterangan","status","jenis_potongan_id","date_from","date_to","is_all_kary","percentage","is_lunas","creator_id","last_editor_id","created_at","updated_at","status_bayar"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_potongan_det_bayar() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_potongan_det_bayar', 't_potongan_id', 'id');
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
    public function jenis_potongan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jenis_potongan_id', 'id');
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
