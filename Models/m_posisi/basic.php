<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_posisi extends Model
{   
    use ModelTrait;

    protected $table    = 'm_posisi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","kode","desc_kerja","desc_kerja_1","desc_kerja_2","min_pengalaman","min_pendidikan_id","min_gaji_pokok","max_gaji_pokok","biaya","tipe_bpjs_id","potongan_bpjs","desc","is_active","creator_id","last_editor_id","jatah_jam_lembur","percentage"];

    public $columns     = ["id","m_comp_id","m_dir_id","kode","desc_kerja","desc_kerja_1","desc_kerja_2","min_pengalaman","min_pendidikan_id","min_gaji_pokok","max_gaji_pokok","biaya","tipe_bpjs_id","potongan_bpjs","desc","is_active","creator_id","last_editor_id","created_at","updated_at","jatah_jam_lembur","percentage"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","kode:string:50","desc_kerja:text","desc_kerja_1:text","desc_kerja_2:text","min_pengalaman:string:191","min_pendidikan_id:bigint","min_gaji_pokok:decimal","max_gaji_pokok:decimal","biaya:decimal","tipe_bpjs_id:bigint","potongan_bpjs:decimal","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","jatah_jam_lembur:integer","percentage:integer"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_posisi.m_comp_id","m_dir.id=m_posisi.m_dir_id","m_general.id=m_posisi.min_pendidikan_id","m_general.id=m_posisi.tipe_bpjs_id","default_users.id=m_posisi.creator_id","default_users.id=m_posisi.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_kary","m_spd","m_standart_gaji","m_tunj_kemahalan","t_mutasi","t_mutasi","t_pelamar","t_spd"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["desc_kerja","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","kode","desc_kerja","desc_kerja_1","desc_kerja_2","min_pengalaman","min_pendidikan_id","min_gaji_pokok","max_gaji_pokok","biaya","tipe_bpjs_id","potongan_bpjs","desc","is_active","creator_id","last_editor_id","jatah_jam_lembur","percentage"];
    public $updateable  = ["m_comp_id","m_dir_id","kode","desc_kerja","desc_kerja_1","desc_kerja_2","min_pengalaman","min_pendidikan_id","min_gaji_pokok","max_gaji_pokok","biaya","tipe_bpjs_id","potongan_bpjs","desc","is_active","creator_id","last_editor_id","jatah_jam_lembur","percentage"];
    public $searchable  = ["id","m_comp_id","m_dir_id","kode","desc_kerja","desc_kerja_1","desc_kerja_2","min_pengalaman","min_pendidikan_id","min_gaji_pokok","max_gaji_pokok","biaya","tipe_bpjs_id","potongan_bpjs","desc","is_active","creator_id","last_editor_id","created_at","updated_at","jatah_jam_lembur","percentage"];
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
    public function min_pendidikan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'min_pendidikan_id', 'id');
    }
    public function tipe_bpjs() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_bpjs_id', 'id');
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
