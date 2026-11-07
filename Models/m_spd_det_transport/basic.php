<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_spd_det_transport extends Model
{   
    use ModelTrait;

    protected $table    = 'm_spd_det_transport';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","m_spd_det_biaya_id","jenis_transport_id","nama_transport","biaya_transport","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","m_spd_det_biaya_id","jenis_transport_id","nama_transport","biaya_transport","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","m_spd_det_biaya_id:bigint","jenis_transport_id:bigint","nama_transport:string:191","biaya_transport:decimal","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_spd_det_transport.m_comp_id","m_dir.id=m_spd_det_transport.m_dir_id","m_spd_det_biaya.id=m_spd_det_transport.m_spd_det_biaya_id","m_general.id=m_spd_det_transport.jenis_transport_id","default_users.id=m_spd_det_transport.creator_id","default_users.id=m_spd_det_transport.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_transport","biaya_transport"];
    public $createable  = ["m_comp_id","m_dir_id","m_spd_det_biaya_id","jenis_transport_id","nama_transport","biaya_transport","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","m_spd_det_biaya_id","jenis_transport_id","nama_transport","biaya_transport","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","m_spd_det_biaya_id","jenis_transport_id","nama_transport","biaya_transport","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_spd_det_biaya() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_spd_det_biaya', 'm_spd_det_biaya_id', 'id');
    }
    public function jenis_transport() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jenis_transport_id', 'id');
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
