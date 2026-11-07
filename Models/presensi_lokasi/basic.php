<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class presensi_lokasi extends Model
{   
    use ModelTrait;

    protected $table    = 'presensi_lokasi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["comp_id","default_user_id","nama","lat","long","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","comp_id","default_user_id","nama","lat","long","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","comp_id:bigint","default_user_id:bigint","nama:string:191","lat:float","long:float","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=presensi_lokasi.comp_id","default_users.id=presensi_lokasi.default_user_id","default_users.id=presensi_lokasi.creator_id","default_users.id=presensi_lokasi.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_kary"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","lat","long","is_active"];
    public $createable  = ["comp_id","default_user_id","nama","lat","long","is_active","creator_id","last_editor_id"];
    public $updateable  = ["comp_id","default_user_id","nama","lat","long","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","comp_id","default_user_id","nama","lat","long","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'comp_id', 'id');
    }
    public function default_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_user_id', 'id');
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
