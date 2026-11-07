<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_kary_salary extends Model
{   
    use ModelTrait;

    protected $table    = 't_kary_salary';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_id","tipe","is_active","last_usage","keterangan","total","creator_id","last_editor_id"];

    public $columns     = ["id","m_kary_id","tipe","is_active","last_usage","keterangan","total","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_kary_id:bigint","tipe:string:191","is_active:boolean","last_usage:date","keterangan:string:191","total:decimal","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_kary.id=t_kary_salary.m_kary_id"];
    public $details     = ["t_kary_salary_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","is_active","total"];
    public $createable  = ["m_kary_id","tipe","is_active","last_usage","keterangan","total","creator_id","last_editor_id"];
    public $updateable  = ["m_kary_id","tipe","is_active","last_usage","keterangan","total","creator_id","last_editor_id"];
    public $searchable  = ["id","m_kary_id","tipe","is_active","last_usage","keterangan","total","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_kary_salary_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_kary_salary_det', 't_kary_salary_id', 'id');
    }
    
    
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
}
