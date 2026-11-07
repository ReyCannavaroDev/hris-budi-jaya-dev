<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class presensi_app_version extends Model
{   
    use ModelTrait;

    protected $table    = 'presensi_app_version';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["comp_id","version","note","link","type","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","comp_id","version","note","link","type","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","comp_id:bigint","version:string:191","note:text","link:string:191","type:string:191","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_company.id=presensi_app_version.comp_id","default_users.id=presensi_app_version.creator_id","default_users.id=presensi_app_version.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["version","note","link","type","is_active"];
    public $createable  = ["comp_id","version","note","link","type","is_active","creator_id","last_editor_id"];
    public $updateable  = ["comp_id","version","note","link","type","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","comp_id","version","note","link","type","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_company', 'comp_id', 'id');
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
