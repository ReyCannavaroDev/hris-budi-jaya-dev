<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_grade_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_grade_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_grade_id","keterangan","type","value","factor","day","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active","creator_id","last_editor_id","deletor_id","deleted_at"];

    public $columns     = ["id","m_grade_id","keterangan","type","value","factor","day","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $columnsFull = ["id:bigint","m_grade_id:bigint","keterangan:string:191","type:string:191","value:decimal","factor:string:191","day:string:191","big_event:boolean","full_week:boolean","is_7_5:boolean","need_overtime:boolean","value_overtime:integer","not_checkin:boolean","is_month:boolean","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","deletor_id:bigint","deleted_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_general.id=m_grade_d.m_grade_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_grade_id","keterangan","type","value","factor","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active"];
    public $createable  = ["m_grade_id","keterangan","type","value","factor","day","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $updateable  = ["m_grade_id","keterangan","type","value","factor","day","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $searchable  = ["id","m_grade_id","keterangan","type","value","factor","day","big_event","full_week","is_7_5","need_overtime","value_overtime","not_checkin","is_month","is_active","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_grade() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'm_grade_id', 'id');
    }
}
