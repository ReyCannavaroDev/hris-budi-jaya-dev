<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_standart_gaji extends Model
{   
    use ModelTrait;

    protected $table    = 'm_standart_gaji';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","grading_id","gaji_pokok","gaji_pokok_periode","uang_saku","uang_saku_periode","tunjangan_posisi","tunjangan_posisi_periode","tunjangan_kemahalan_id","tunjangan_kemahalan_periode","uang_makan","uang_makan_periode","tunjangan_tetap","tunjangan_tetap_periode","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","grading_id","gaji_pokok","gaji_pokok_periode","uang_saku","uang_saku_periode","tunjangan_posisi","tunjangan_posisi_periode","tunjangan_kemahalan_id","tunjangan_kemahalan_periode","uang_makan","uang_makan_periode","tunjangan_tetap","tunjangan_tetap_periode","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","kode:string:50","m_divisi_id:bigint","m_dept_id:bigint","m_zona_id:bigint","m_posisi_id:bigint","grading_id:bigint","gaji_pokok:decimal","gaji_pokok_periode:string:50","uang_saku:decimal","uang_saku_periode:string:50","tunjangan_posisi:decimal","tunjangan_posisi_periode:string:50","tunjangan_kemahalan_id:bigint","tunjangan_kemahalan_periode:string:50","uang_makan:decimal","uang_makan_periode:string:50","tunjangan_tetap:decimal","tunjangan_tetap_periode:string:50","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_standart_gaji.m_comp_id","m_dir.id=m_standart_gaji.m_dir_id","m_divisi.id=m_standart_gaji.m_divisi_id","m_dept.id=m_standart_gaji.m_dept_id","m_zona.id=m_standart_gaji.m_zona_id","m_posisi.id=m_standart_gaji.m_posisi_id","m_general.id=m_standart_gaji.grading_id","m_tunj_kemahalan.id=m_standart_gaji.tunjangan_kemahalan_id","default_users.id=m_standart_gaji.creator_id","default_users.id=m_standart_gaji.last_editor_id"];
    public $details     = ["m_standart_gaji_det"];
    public $heirs       = ["m_kary","t_mutasi","t_mutasi"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["gaji_pokok","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","grading_id","gaji_pokok","gaji_pokok_periode","uang_saku","uang_saku_periode","tunjangan_posisi","tunjangan_posisi_periode","tunjangan_kemahalan_id","tunjangan_kemahalan_periode","uang_makan","uang_makan_periode","tunjangan_tetap","tunjangan_tetap_periode","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","grading_id","gaji_pokok","gaji_pokok_periode","uang_saku","uang_saku_periode","tunjangan_posisi","tunjangan_posisi_periode","tunjangan_kemahalan_id","tunjangan_kemahalan_periode","uang_makan","uang_makan_periode","tunjangan_tetap","tunjangan_tetap_periode","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","grading_id","gaji_pokok","gaji_pokok_periode","uang_saku","uang_saku_periode","tunjangan_posisi","tunjangan_posisi_periode","tunjangan_kemahalan_id","tunjangan_kemahalan_periode","uang_makan","uang_makan_periode","tunjangan_tetap","tunjangan_tetap_periode","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_standart_gaji_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_standart_gaji_det', 'm_standart_gaji_id', 'id');
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
    public function m_posisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_posisi', 'm_posisi_id', 'id');
    }
    public function grading() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'grading_id', 'id');
    }
    public function tunjangan_kemahalan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tunj_kemahalan', 'tunjangan_kemahalan_id', 'id');
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
