<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_zona_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_zona_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_zona_id","m_dir_id","m_lokasi_id","desc"];

    public $columns     = ["id","m_zona_id","m_dir_id","m_lokasi_id","desc","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_zona_id:bigint","m_dir_id:bigint","m_lokasi_id:bigint","desc:text","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_zona.id=m_zona_det.m_zona_id","m_dir.id=m_zona_det.m_dir_id","m_lokasi.id=m_zona_det.m_lokasi_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_zona_id","m_dir_id","m_lokasi_id","desc"];
    public $updateable  = ["m_zona_id","m_dir_id","m_lokasi_id","desc"];
    public $searchable  = ["id","m_zona_id","m_dir_id","m_lokasi_id","desc","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_zona() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_zona', 'm_zona_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function m_lokasi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_lokasi', 'm_lokasi_id', 'id');
    }
}
