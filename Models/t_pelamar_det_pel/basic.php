<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_pel extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_pel';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","nama_pel","tahun","nama_lem","kota_id","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","nama_pel","tahun","nama_lem","kota_id","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","nama_pel:string:100","tahun:integer","nama_lem:string:100","kota_id:bigint","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_pel.t_pelamar_id","m_general.id=t_pelamar_det_pel.kota_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_pel","tahun","nama_lem"];
    public $createable  = ["t_pelamar_id","nama_pel","tahun","nama_lem","kota_id","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","nama_pel","tahun","nama_lem","kota_id","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","nama_pel","tahun","nama_lem","kota_id","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
    public function kota() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kota_id', 'id');
    }
}
