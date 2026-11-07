<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_dir extends Model
{   
    use ModelTrait;

    protected $table    = 'm_dir';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","nama","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","nama","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","nama:string:100","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_dir.m_comp_id","default_users.id=m_dir.creator_id","default_users.id=m_dir.last_editor_id"];
    public $details     = [];
    public $heirs       = ["generate_approval","generate_approval_log","m_approval","m_dept","m_divisi","m_file","m_general","m_jam_kerja","m_kary","m_kary_det_bhs","m_kary_det_kartu","m_kary_det_kel","m_kary_det_org","m_kary_det_pel","m_kary_det_pemb","m_kary_det_pend","m_kary_det_pk","m_kary_det_pres","m_knd_dinas","m_lembur","m_libur_nasional","m_lokasi","m_menu","m_pengguna","m_posisi","m_role","m_role_access","m_role_det","m_spd","m_spd_det_biaya","m_spd_det_transport","m_standart_gaji","m_tunj_kemahalan","m_zona","m_zona_det","t_cuti","t_cuti_adjustment","t_final_gaji_det","t_grup_kerja","t_hasil_tes","t_jadwal_kerja","t_jadwal_kerja_det","t_lembur","t_loker","t_mutasi","t_pelamar","t_perhitungan_gaji","t_potongan","t_riwayat_posisi","t_sgp","t_spd","default_users"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active"];
    public $createable  = ["m_comp_id","nama","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","nama","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","nama","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
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
