<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_jam_kerja extends Model
{   
    use ModelTrait;

    protected $table    = 'm_jam_kerja';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","kode","tipe_jam_kerja_id","waktu_mulai","waktu_akhir","is_hari_berikutnya","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","kode","tipe_jam_kerja_id","waktu_mulai","waktu_akhir","is_hari_berikutnya","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","kode:string:191","tipe_jam_kerja_id:bigint","waktu_mulai:time","waktu_akhir:time","is_hari_berikutnya:boolean","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_jam_kerja.m_comp_id","m_dir.id=m_jam_kerja.m_dir_id","m_general.id=m_jam_kerja.tipe_jam_kerja_id","default_users.id=m_jam_kerja.creator_id","default_users.id=m_jam_kerja.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_kary","t_jadwal_kerja_det_hari"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["waktu_mulai","waktu_akhir","is_hari_berikutnya","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","kode","tipe_jam_kerja_id","waktu_mulai","waktu_akhir","is_hari_berikutnya","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","kode","tipe_jam_kerja_id","waktu_mulai","waktu_akhir","is_hari_berikutnya","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","kode","tipe_jam_kerja_id","waktu_mulai","waktu_akhir","is_hari_berikutnya","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
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
    public function tipe_jam_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_jam_kerja_id', 'id');
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
