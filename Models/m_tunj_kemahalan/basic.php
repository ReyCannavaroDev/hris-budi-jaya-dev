<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tunj_kemahalan extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tunj_kemahalan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","besaran","desc","is_active","creator_id","last_editor_id","grading_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","besaran","desc","is_active","creator_id","last_editor_id","created_at","updated_at","grading_id"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","kode:string:50","m_divisi_id:bigint","m_dept_id:bigint","m_zona_id:bigint","m_posisi_id:bigint","besaran:decimal","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","grading_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_tunj_kemahalan.m_comp_id","m_dir.id=m_tunj_kemahalan.m_dir_id","m_divisi.id=m_tunj_kemahalan.m_divisi_id","m_dept.id=m_tunj_kemahalan.m_dept_id","m_zona.id=m_tunj_kemahalan.m_zona_id","m_posisi.id=m_tunj_kemahalan.m_posisi_id","default_users.id=m_tunj_kemahalan.creator_id","default_users.id=m_tunj_kemahalan.last_editor_id","m_general.id=m_tunj_kemahalan.grading_id"];
    public $details     = [];
    public $heirs       = ["m_standart_gaji"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["besaran","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","besaran","desc","is_active","creator_id","last_editor_id","grading_id"];
    public $updateable  = ["m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","besaran","desc","is_active","creator_id","last_editor_id","grading_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","kode","m_divisi_id","m_dept_id","m_zona_id","m_posisi_id","besaran","desc","is_active","creator_id","last_editor_id","created_at","updated_at","grading_id"];
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
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function grading() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'grading_id', 'id');
    }
}
