<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","m_divisi_id","m_dept_id","m_zona_id","grading_id","costcontre_id","kode","m_posisi_id","m_jam_kerja_id","kode_presensi","nik","nama_depan","nama_belakang","nama_lengkap","nama_panggilan","jk_id","tempat_lahir","tgl_lahir","provinsi_id","kota_id","kecamatan_id","kode_pos","alamat_asli","alamat_domisili","no_tlp","no_tlp_lainnya","no_darurat","nama_kontak_darurat","agama_id","gol_darah_id","status_nikah_id","tanggungan_id","hub_dgn_karyawan","cuti_jatah_reguler","cuti_sisa_reguler","cuti_panjang","cuti_sisa_panjang","status_kary_id","lama_kontrak_awal","lama_kontrak_akhir","tgl_masuk","tgl_berhenti","alasan_berhenti","uk_baju","uk_celana","uk_sepatu","desc","is_active","creator_id","last_editor_id","m_standart_gaji_id","periode_gaji_id","ref_id","presensi_lokasi_default_id","exp_date_cuti","limit_potong","atasan_id","cuti_p24","cuti_sisa_p24","tipe_jam_kerja_id","t_jadwal_kerja_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","m_zona_id","grading_id","costcontre_id","kode","m_posisi_id","m_jam_kerja_id","kode_presensi","nik","nama_depan","nama_belakang","nama_lengkap","nama_panggilan","jk_id","tempat_lahir","tgl_lahir","provinsi_id","kota_id","kecamatan_id","kode_pos","alamat_asli","alamat_domisili","no_tlp","no_tlp_lainnya","no_darurat","nama_kontak_darurat","agama_id","gol_darah_id","status_nikah_id","tanggungan_id","hub_dgn_karyawan","cuti_jatah_reguler","cuti_sisa_reguler","cuti_panjang","cuti_sisa_panjang","status_kary_id","lama_kontrak_awal","lama_kontrak_akhir","tgl_masuk","tgl_berhenti","alasan_berhenti","uk_baju","uk_celana","uk_sepatu","desc","is_active","creator_id","last_editor_id","created_at","updated_at","m_standart_gaji_id","periode_gaji_id","ref_id","presensi_lokasi_default_id","exp_date_cuti","limit_potong","atasan_id","cuti_p24","cuti_sisa_p24","tipe_jam_kerja_id","t_jadwal_kerja_id"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","m_divisi_id:bigint","m_dept_id:bigint","m_zona_id:bigint","grading_id:bigint","costcontre_id:bigint","kode:string:100","m_posisi_id:bigint","m_jam_kerja_id:bigint","kode_presensi:string:100","nik:string:20","nama_depan:string:100","nama_belakang:string:100","nama_lengkap:string:100","nama_panggilan:string:100","jk_id:bigint","tempat_lahir:string:100","tgl_lahir:date","provinsi_id:bigint","kota_id:bigint","kecamatan_id:bigint","kode_pos:string:10","alamat_asli:text","alamat_domisili:text","no_tlp:string:20","no_tlp_lainnya:string:20","no_darurat:string:20","nama_kontak_darurat:string:100","agama_id:bigint","gol_darah_id:bigint","status_nikah_id:bigint","tanggungan_id:bigint","hub_dgn_karyawan:string:100","cuti_jatah_reguler:integer","cuti_sisa_reguler:integer","cuti_panjang:integer","cuti_sisa_panjang:integer","status_kary_id:bigint","lama_kontrak_awal:date","lama_kontrak_akhir:date","tgl_masuk:date","tgl_berhenti:date","alasan_berhenti:text","uk_baju:string:50","uk_celana:string:50","uk_sepatu:string:50","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","m_standart_gaji_id:bigint","periode_gaji_id:bigint","ref_id:bigint","presensi_lokasi_default_id:bigint","exp_date_cuti:date","limit_potong:integer","atasan_id:bigint","cuti_p24:decimal","cuti_sisa_p24:decimal","tipe_jam_kerja_id:bigint","t_jadwal_kerja_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_kary.m_comp_id","m_dir.id=m_kary.m_dir_id","m_divisi.id=m_kary.m_divisi_id","m_dept.id=m_kary.m_dept_id","m_zona.id=m_kary.m_zona_id","m_general.id=m_kary.grading_id","m_general.id=m_kary.costcontre_id","m_posisi.id=m_kary.m_posisi_id","m_jam_kerja.id=m_kary.m_jam_kerja_id","m_general.id=m_kary.jk_id","m_general.id=m_kary.provinsi_id","m_general.id=m_kary.kota_id","m_general.id=m_kary.kecamatan_id","m_general.id=m_kary.agama_id","m_general.id=m_kary.gol_darah_id","m_general.id=m_kary.status_nikah_id","m_general.id=m_kary.tanggungan_id","m_general.id=m_kary.status_kary_id","default_users.id=m_kary.creator_id","default_users.id=m_kary.last_editor_id","m_standart_gaji.id=m_kary.m_standart_gaji_id","m_general.id=m_kary.periode_gaji_id","t_pelamar.id=m_kary.ref_id","presensi_lokasi.id=m_kary.presensi_lokasi_default_id","m_kary.id=m_kary.atasan_id","m_general.id=m_kary.tipe_jam_kerja_id","t_jadwal_kerja.id=m_kary.t_jadwal_kerja_id"];
    public $details     = ["m_kary_det_bhs","m_kary_det_kartu","m_kary_det_kel","m_kary_det_org","m_kary_det_pel","m_kary_det_pemb","m_kary_det_pend","m_kary_det_pk","m_kary_det_pres"];
    public $heirs       = ["m_kary","m_kary_group_det","t_cuti","t_cuti_adjustment","t_final_gaji_det","t_jadwal_kerja_det","t_kary_salary","t_kary_salary_det_tarif","t_lembur","t_mutasi","t_perhitungan_gaji","t_potongan","t_riwayat_posisi","t_salary_borongan","t_salary_borongan_det_kary","t_sgp","t_spd","default_users"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_comp_id","m_dir_id","m_divisi_id","m_dept_id","m_zona_id","grading_id","costcontre_id","kode","m_posisi_id","m_jam_kerja_id","kode_presensi","nik","nama_depan","nama_belakang","nama_lengkap","nama_panggilan","jk_id","tempat_lahir","tgl_lahir","provinsi_id","kota_id","kecamatan_id","kode_pos","alamat_asli","alamat_domisili","no_tlp","no_tlp_lainnya","no_darurat","nama_kontak_darurat","agama_id","gol_darah_id","status_nikah_id","tanggungan_id","hub_dgn_karyawan","cuti_jatah_reguler","cuti_sisa_reguler","cuti_panjang","cuti_sisa_panjang","status_kary_id","lama_kontrak_awal","lama_kontrak_akhir","tgl_masuk","tgl_berhenti","alasan_berhenti","uk_baju","uk_celana","uk_sepatu","desc","is_active","creator_id","last_editor_id","m_standart_gaji_id","periode_gaji_id","ref_id","presensi_lokasi_default_id","exp_date_cuti","limit_potong","atasan_id","cuti_p24","cuti_sisa_p24","tipe_jam_kerja_id","t_jadwal_kerja_id"];
    public $updateable  = ["m_comp_id","m_dir_id","m_divisi_id","m_dept_id","m_zona_id","grading_id","costcontre_id","kode","m_posisi_id","m_jam_kerja_id","kode_presensi","nik","nama_depan","nama_belakang","nama_lengkap","nama_panggilan","jk_id","tempat_lahir","tgl_lahir","provinsi_id","kota_id","kecamatan_id","kode_pos","alamat_asli","alamat_domisili","no_tlp","no_tlp_lainnya","no_darurat","nama_kontak_darurat","agama_id","gol_darah_id","status_nikah_id","tanggungan_id","hub_dgn_karyawan","cuti_jatah_reguler","cuti_sisa_reguler","cuti_panjang","cuti_sisa_panjang","status_kary_id","lama_kontrak_awal","lama_kontrak_akhir","tgl_masuk","tgl_berhenti","alasan_berhenti","uk_baju","uk_celana","uk_sepatu","desc","is_active","creator_id","last_editor_id","m_standart_gaji_id","periode_gaji_id","ref_id","presensi_lokasi_default_id","exp_date_cuti","limit_potong","atasan_id","cuti_p24","cuti_sisa_p24","tipe_jam_kerja_id","t_jadwal_kerja_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","m_divisi_id","m_dept_id","m_zona_id","grading_id","costcontre_id","kode","m_posisi_id","m_jam_kerja_id","kode_presensi","nik","nama_depan","nama_belakang","nama_lengkap","nama_panggilan","jk_id","tempat_lahir","tgl_lahir","provinsi_id","kota_id","kecamatan_id","kode_pos","alamat_asli","alamat_domisili","no_tlp","no_tlp_lainnya","no_darurat","nama_kontak_darurat","agama_id","gol_darah_id","status_nikah_id","tanggungan_id","hub_dgn_karyawan","cuti_jatah_reguler","cuti_sisa_reguler","cuti_panjang","cuti_sisa_panjang","status_kary_id","lama_kontrak_awal","lama_kontrak_akhir","tgl_masuk","tgl_berhenti","alasan_berhenti","uk_baju","uk_celana","uk_sepatu","desc","is_active","creator_id","last_editor_id","created_at","updated_at","m_standart_gaji_id","periode_gaji_id","ref_id","presensi_lokasi_default_id","exp_date_cuti","limit_potong","atasan_id","cuti_p24","cuti_sisa_p24","tipe_jam_kerja_id","t_jadwal_kerja_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_kary_det_bhs() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_bhs', 'm_kary_id', 'id');
    }
    public function m_kary_det_kartu() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_kartu', 'm_kary_id', 'id');
    }
    public function m_kary_det_kel() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_kel', 'm_kary_id', 'id');
    }
    public function m_kary_det_org() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_org', 'm_kary_id', 'id');
    }
    public function m_kary_det_pel() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_pel', 'm_kary_id', 'id');
    }
    public function m_kary_det_pemb() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_pemb', 'm_kary_id', 'id');
    }
    public function m_kary_det_pend() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_pend', 'm_kary_id', 'id');
    }
    public function m_kary_det_pk() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_pk', 'm_kary_id', 'id');
    }
    public function m_kary_det_pres() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_det_pres', 'm_kary_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_divisi', 'm_divisi_id', 'id');
    }
    public function m_dept() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dept', 'm_dept_id', 'id');
    }
    public function m_zona() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_zona', 'm_zona_id', 'id');
    }
    public function grading() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'grading_id', 'id');
    }
    public function costcontre() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'costcontre_id', 'id');
    }
    public function m_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_id', 'id');
    }
    public function m_jam_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_jam_kerja', 'm_jam_kerja_id', 'id');
    }
    public function jk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jk_id', 'id');
    }
    public function provinsi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'provinsi_id', 'id');
    }
    public function kota() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kota_id', 'id');
    }
    public function kecamatan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kecamatan_id', 'id');
    }
    public function agama() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'agama_id', 'id');
    }
    public function gol_darah() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'gol_darah_id', 'id');
    }
    public function status_nikah() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'status_nikah_id', 'id');
    }
    public function tanggungan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tanggungan_id', 'id');
    }
    public function status_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'status_kary_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function m_standart_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_standart_gaji', 'm_standart_gaji_id', 'id');
    }
    public function periode_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'periode_gaji_id', 'id');
    }
    public function ref() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 'ref_id', 'id');
    }
    public function presensi_lokasi_default() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\presensi_lokasi', 'presensi_lokasi_default_id', 'id');
    }
    public function atasan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'atasan_id', 'id');
    }
    public function tipe_jam_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_jam_kerja_id', 'id');
    }
    public function t_jadwal_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jadwal_kerja', 't_jadwal_kerja_id', 'id');
    }
}
