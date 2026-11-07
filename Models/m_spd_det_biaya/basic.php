<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_spd_det_biaya extends Model
{   
    use ModelTrait;

    protected $table    = 'm_spd_det_biaya';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_spd_id","m_dir_id","total_biaya","tipe_id","creator_id","last_editor_id","keterangan"];

    public $columns     = ["id","m_spd_id","m_dir_id","total_biaya","tipe_id","creator_id","last_editor_id","created_at","updated_at","keterangan"];
    public $columnsFull = ["id:bigint","m_spd_id:bigint","m_dir_id:bigint","total_biaya:decimal","tipe_id:bigint","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","keterangan:text"];
    public $rules       = [];
    public $joins       = ["m_spd.id=m_spd_det_biaya.m_spd_id","m_dir.id=m_spd_det_biaya.m_dir_id","m_general.id=m_spd_det_biaya.tipe_id","default_users.id=m_spd_det_biaya.creator_id","default_users.id=m_spd_det_biaya.last_editor_id"];
    public $details     = ["m_spd_det_transport"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["total_biaya"];
    public $createable  = ["m_spd_id","m_dir_id","total_biaya","tipe_id","creator_id","last_editor_id","keterangan"];
    public $updateable  = ["m_spd_id","m_dir_id","total_biaya","tipe_id","creator_id","last_editor_id","keterangan"];
    public $searchable  = ["id","m_spd_id","m_dir_id","total_biaya","tipe_id","creator_id","last_editor_id","created_at","updated_at","keterangan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_spd_det_transport() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_spd_det_transport', 'm_spd_det_biaya_id', 'id');
    }
    
    
    public function m_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_spd', 'm_spd_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_id', 'id');
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
