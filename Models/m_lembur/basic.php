<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_lembur extends Model
{   
    use ModelTrait;

    protected $table    = 'm_lembur';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_dir_id","kode","m_divisi_id","m_dept_id","grading_id","grading","besaran","desc","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_dir_id","kode","m_divisi_id","m_dept_id","grading_id","grading","besaran","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_dir_id:bigint","kode:string:50","m_divisi_id:bigint","m_dept_id:bigint","grading_id:bigint","grading:string:100","besaran:decimal","desc:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_dir.id=m_lembur.m_dir_id","m_divisi.id=m_lembur.m_divisi_id","m_dept.id=m_lembur.m_dept_id","m_general.id=m_lembur.grading_id","default_users.id=m_lembur.creator_id","default_users.id=m_lembur.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["besaran","is_active"];
    public $createable  = ["m_dir_id","kode","m_divisi_id","m_dept_id","grading_id","grading","besaran","desc","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_dir_id","kode","m_divisi_id","m_dept_id","grading_id","grading","besaran","desc","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_dir_id","kode","m_divisi_id","m_dept_id","grading_id","grading","besaran","desc","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
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
    public function grading() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'grading_id', 'id');
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
