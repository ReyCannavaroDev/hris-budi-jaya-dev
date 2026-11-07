<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_salary_borongan extends Model
{   
    use ModelTrait;

    protected $table    = 't_salary_borongan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["label","date","jml_kary","total_pendapatan","pic_id","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","label","date","jml_kary","total_pendapatan","pic_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","label:string:191","date:date","jml_kary:integer","total_pendapatan:decimal","pic_id:bigint","keterangan:text","status:string:191","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_kary.id=t_salary_borongan.pic_id"];
    public $details     = ["t_salary_borongan_det","t_salary_borongan_det_kary"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["label","date","total_pendapatan","pic_id"];
    public $createable  = ["label","date","jml_kary","total_pendapatan","pic_id","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["label","date","jml_kary","total_pendapatan","pic_id","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","label","date","jml_kary","total_pendapatan","pic_id","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_salary_borongan_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_salary_borongan_det', 't_salary_borongan_id', 'id');
    }
    public function t_salary_borongan_det_kary() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_salary_borongan_det_kary', 't_salary_borongan_id', 'id');
    }
    
    
    public function pic() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'pic_id', 'id');
    }
}
