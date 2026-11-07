<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_pend extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_pend';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_id","m_comp_id","m_dir_id","tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir","ijazah_no","ijazah_foto","desc","creator_id","last_editor_id"];

    public $columns     = ["id","m_kary_id","m_comp_id","m_dir_id","tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir","ijazah_no","ijazah_foto","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_kary_id:bigint","m_comp_id:bigint","m_dir_id:bigint","tingkat_id:bigint","nama_sekolah:string:100","thn_masuk:integer","thn_lulus:integer","kota_id:bigint","nilai:decimal","jurusan:string:50","is_pend_terakhir:boolean","ijazah_no:string:191","ijazah_foto:string:191","desc:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_kary.id=m_kary_det_pend.m_kary_id","m_comp.id=m_kary_det_pend.m_comp_id","m_dir.id=m_kary_det_pend.m_dir_id","m_general.id=m_kary_det_pend.tingkat_id","m_general.id=m_kary_det_pend.kota_id","default_users.id=m_kary_det_pend.creator_id","default_users.id=m_kary_det_pend.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir"];
    public $createable  = ["m_kary_id","m_comp_id","m_dir_id","tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir","ijazah_no","ijazah_foto","desc","creator_id","last_editor_id"];
    public $updateable  = ["m_kary_id","m_comp_id","m_dir_id","tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir","ijazah_no","ijazah_foto","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","m_kary_id","m_comp_id","m_dir_id","tingkat_id","nama_sekolah","thn_masuk","thn_lulus","kota_id","nilai","jurusan","is_pend_terakhir","ijazah_no","ijazah_foto","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function tingkat() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tingkat_id', 'id');
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
