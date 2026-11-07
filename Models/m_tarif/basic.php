<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_tarif_group_id","nama","nominal","nominal_2","is_active","keterangan","creator_id","last_editor_id"];

    public $columns     = ["id","m_tarif_group_id","nama","nominal","nominal_2","is_active","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_tarif_group_id:bigint","nama:string:191","nominal:decimal","nominal_2:decimal","is_active:boolean","keterangan:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_tarif_group.id=m_tarif.m_tarif_group_id"];
    public $details     = [];
    public $heirs       = ["t_kary_salary_det_tarif","t_salary_borongan_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_tarif_group_id","nama","nominal","is_active"];
    public $createable  = ["m_tarif_group_id","nama","nominal","nominal_2","is_active","keterangan","creator_id","last_editor_id"];
    public $updateable  = ["m_tarif_group_id","nama","nominal","nominal_2","is_active","keterangan","creator_id","last_editor_id"];
    public $searchable  = ["id","m_tarif_group_id","nama","nominal","nominal_2","is_active","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_tarif_group() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif_group', 'm_tarif_group_id', 'id');
    }
}
