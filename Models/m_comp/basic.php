<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_comp extends Model
{   
    use ModelTrait;

    protected $table    = 'm_comp';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama","is_active","desc","creator_id","last_editor_id"];

    public $columns     = ["id","nama","is_active","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama:string:50","is_active:boolean","desc:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=m_comp.creator_id","default_users.id=m_comp.last_editor_id"];
    public $details     = [];
    public $heirs       = ["generate_approval","generate_approval_log","m_approval","m_contract","m_dir","m_divisi","m_general","m_jam_kerja","m_jarak_kota","m_kary","m_kary_det_bhs","m_kary_det_kartu","m_kary_det_kel","m_kary_det_org","m_kary_det_pel","m_kary_det_pemb","m_kary_det_pend","m_kary_det_pk","m_kary_det_pres","m_knd_dinas","m_libur_nasional","m_lokasi","m_menu","m_pengesahan_doc","m_pengurangan_gaji","m_periode","m_posisi","m_role","m_role_access","m_role_det","m_spd","m_spd_det_transport","m_standart_gaji","m_tunj_kemahalan","m_zona","presensi_absensi","presensi_lokasi","t_cuti","t_cuti_adjustment","t_final_gaji","t_grup_kerja","t_hasil_tes","t_jadwal_kerja","t_lembur","t_loker","t_mutasi","t_pelamar","t_potongan","t_potongan_det_bayar","t_riwayat_posisi","t_rpd","t_sgp","t_spd","default_users"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["is_active"];
    public $createable  = ["nama","is_active","desc","creator_id","last_editor_id"];
    public $updateable  = ["nama","is_active","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","nama","is_active","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
