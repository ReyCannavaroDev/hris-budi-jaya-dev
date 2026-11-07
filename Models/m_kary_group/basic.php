<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_group extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_group';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama","is_active","keterengan","creator_id","last_editor_id"];

    public $columns     = ["id","nama","is_active","keterengan","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama:string:191","is_active:boolean","keterengan:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=m_kary_group.creator_id","default_users.id=m_kary_group.last_editor_id"];
    public $details     = ["m_kary_group_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active"];
    public $createable  = ["nama","is_active","keterengan","creator_id","last_editor_id"];
    public $updateable  = ["nama","is_active","keterengan","creator_id","last_editor_id"];
    public $searchable  = ["id","nama","is_active","keterengan","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_kary_group_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_kary_group_det', 'm_kary_group_id', 'id');
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
