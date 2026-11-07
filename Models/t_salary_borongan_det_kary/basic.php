<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_salary_borongan_det_kary extends Model
{   
    use ModelTrait;

    protected $table    = 't_salary_borongan_det_kary';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_salary_borongan_id","m_kary_id","diterima","is_pic","is_done","creator_id","last_editor_id"];

    public $columns     = ["id","t_salary_borongan_id","m_kary_id","diterima","is_pic","is_done","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_salary_borongan_id:bigint","m_kary_id:bigint","diterima:decimal","is_pic:boolean","is_done:boolean","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_salary_borongan.id=t_salary_borongan_det_kary.t_salary_borongan_id","m_kary.id=t_salary_borongan_det_kary.m_kary_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id"];
    public $createable  = ["t_salary_borongan_id","m_kary_id","diterima","is_pic","is_done","creator_id","last_editor_id"];
    public $updateable  = ["t_salary_borongan_id","m_kary_id","diterima","is_pic","is_done","creator_id","last_editor_id"];
    public $searchable  = ["id","t_salary_borongan_id","m_kary_id","diterima","is_pic","is_done","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_salary_borongan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_salary_borongan', 't_salary_borongan_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
}
