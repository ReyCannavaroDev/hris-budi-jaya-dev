<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_group extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_group';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama","is_active","keterangan","creator_id","last_editor_id"];

    public $columns     = ["id","nama","is_active","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama:string:191","is_active:boolean","keterangan:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["m_tarif","t_kary_salary_det_tarif","t_salary_borongan_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active"];
    public $createable  = ["nama","is_active","keterangan","creator_id","last_editor_id"];
    public $updateable  = ["nama","is_active","keterangan","creator_id","last_editor_id"];
    public $searchable  = ["id","nama","is_active","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
