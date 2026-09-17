<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class default_users extends Model
{   
    use ModelTrait;

    protected $table    = 'default_users';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["name","email","username","email_verified_at","password","m_comp_id","m_dir_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at","profil_image","telp","m_kary_id","updated_id"];

    public $columns     = ["id","name","email","username","email_verified_at","password","m_comp_id","m_dir_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at","profil_image","telp","m_kary_id","updated_id"];
    public $columnsFull = ["id:bigint","name:string:191","email:string:191","username:string:60","email_verified_at:datetime","password:string:191","m_comp_id:bigint","m_dir_id:bigint","is_active:boolean","creator_id:bigint","last_editor_id:bigint","remember_token:string:100","created_at:datetime","updated_at:datetime","profil_image:string:191","telp:string:191","m_kary_id:bigint","updated_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=default_users.m_comp_id","m_dir.id=default_users.m_dir_id","m_kary.id=default_users.m_kary_id"];
    public $details     = ["default_users_socialite"];
    public $heirs       = ["generate_approval","generate_approval","generate_approval","generate_approval_det","generate_approval_det","generate_approval_det","generate_approval_det","generate_approval_log","generate_approval_log","generate_approval_log","generate_num","generate_num","generate_num_det","generate_num_det","generate_num_log","generate_num_log","generate_num_type","generate_num_type","m_approval","m_approval","m_approval_det","m_approval_det","m_approval_det","m_blacklist","m_blacklist","m_comp","m_comp","m_contract","m_contract","m_dept","m_dept","m_dir","m_dir","m_divisi","m_divisi","m_file","m_file","m_general","m_general","m_jam_kerja","m_jam_kerja","m_jarak_kota","m_jarak_kota","m_kary","m_kary","m_kary_det_bhs","m_kary_det_bhs","m_kary_det_kartu","m_kary_det_kartu","m_kary_det_kel","m_kary_det_kel","m_kary_det_org","m_kary_det_org","m_kary_det_pel","m_kary_det_pel","m_kary_det_pemb","m_kary_det_pemb","m_kary_det_pend","m_kary_det_pend","m_kary_det_pk","m_kary_det_pk","m_kary_det_pres","m_kary_det_pres","m_kary_group","m_kary_group","m_kary_group_det","m_kary_group_det","m_knd_dinas","m_knd_dinas","m_lembur","m_lembur","m_libur_nasional","m_libur_nasional","m_lokasi","m_lokasi","m_menu","m_menu","m_pengesahan_doc","m_pengesahan_doc","m_pengguna","m_pengguna","m_pengguna","m_pengurangan_gaji","m_pengurangan_gaji","m_periode","m_periode","m_posisi","m_posisi","m_pph","m_pph","m_role","m_role","m_role_access","m_role_access","m_role_access","m_role_det","m_role_det","m_spd","m_spd","m_spd_det_biaya","m_spd_det_biaya","m_spd_det_transport","m_spd_det_transport","m_standart_gaji","m_standart_gaji","m_standart_gaji_det","m_standart_gaji_det","m_tunj_kemahalan","m_tunj_kemahalan","m_zona","m_zona","presensi_absensi","presensi_absensi","presensi_absensi","presensi_app_version","presensi_app_version","presensi_lokasi","presensi_lokasi","presensi_lokasi","t_cuti","t_cuti","t_cuti_adjustment","t_cuti_adjustment","t_final_gaji","t_final_gaji","t_final_gaji_det","t_final_gaji_det","t_final_gaji_det_rincian","t_final_gaji_det_rincian","t_grup_kerja","t_grup_kerja","t_hasil_tes","t_hasil_tes","t_jadwal_kerja","t_jadwal_kerja","t_jadwal_kerja_det","t_jadwal_kerja_det","t_kary_salary_det_tarif","t_lembur","t_lembur","t_lembur","t_loker","t_loker","t_mutasi","t_mutasi","t_pelamar","t_pelamar","t_pelamar_det_pend","t_pelamar_det_pend","t_pelamar_det_peng","t_pelamar_det_peng","t_perhitungan_gaji","t_perhitungan_gaji","t_potongan","t_potongan","t_riwayat_posisi","t_riwayat_posisi","t_riwayat_posisi_det","t_riwayat_posisi_det","t_rpd","t_rpd","t_rpd_det","t_rpd_det","t_sgp","t_sgp","t_spd","t_spd","t_spd","t_spd_det","t_spd_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "email"=> "unique:default_users,email",
    "username"=> "unique:default_users,username"
	];
    public $required    = ["is_active"];
    public $createable  = ["name","email","username","email_verified_at","password","m_comp_id","m_dir_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at","profil_image","telp","m_kary_id","updated_id"];
    public $updateable  = ["name","email","username","email_verified_at","password","m_comp_id","m_dir_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at","profil_image","telp","m_kary_id","updated_id"];
    public $searchable  = ["name","email","username","email_verified_at","password","m_comp_id","m_dir_id","is_active","creator_id","last_editor_id","remember_token","created_at","updated_at","profil_image","telp","m_kary_id","updated_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function default_users_socialite() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\default_users_socialite', 'default_users_id', 'id');
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
}
