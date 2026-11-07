<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_kary_salary_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_kary_salary_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_kary_salary_id","ref_id","ref_type","nominal","keterangan","creator_id","last_editor_id"];

    public $columns     = ["id","t_kary_salary_id","ref_id","ref_type","nominal","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_kary_salary_id:bigint","ref_id:bigint","ref_type:string:191","nominal:decimal","keterangan:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_kary_salary.id=t_kary_salary_det.t_kary_salary_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_kary_salary_id","ref_id","ref_type","nominal","keterangan","creator_id","last_editor_id"];
    public $updateable  = ["t_kary_salary_id","ref_id","ref_type","nominal","keterangan","creator_id","last_editor_id"];
    public $searchable  = ["id","t_kary_salary_id","ref_id","ref_type","nominal","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_kary_salary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_kary_salary', 't_kary_salary_id', 'id');
    }
}
