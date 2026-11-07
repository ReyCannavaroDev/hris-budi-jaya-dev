<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_pk extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_pk';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","m_kary_id","instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id","surat_referensi","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","m_kary_id","instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id","surat_referensi","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","instansi:string:100","bidang_usaha:string:100","no_tlp:string:20","posisi:string:100","thn_masuk:integer","thn_keluar:integer","alamat_kantor:text","kota_id:bigint","surat_referensi:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_kary_det_pk.m_comp_id","m_dir.id=m_kary_det_pk.m_dir_id","m_kary.id=m_kary_det_pk.m_kary_id","m_general.id=m_kary_det_pk.kota_id","default_users.id=m_kary_det_pk.creator_id","default_users.id=m_kary_det_pk.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id"];
    public $createable  = ["m_comp_id","m_dir_id","m_kary_id","instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id","surat_referensi","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","m_kary_id","instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id","surat_referensi","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","m_kary_id","instansi","bidang_usaha","no_tlp","posisi","thn_masuk","thn_keluar","alamat_kantor","kota_id","surat_referensi","creator_id","last_editor_id","created_at","updated_at"];
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
    public function kota() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kota_id', 'id');
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
