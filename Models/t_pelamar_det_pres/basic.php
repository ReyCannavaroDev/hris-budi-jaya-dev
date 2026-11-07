<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_pres extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_pres';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","nama_pres","tahun","tingkat_pres_id","desc","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","nama_pres","tahun","tingkat_pres_id","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","nama_pres:string:191","tahun:integer","tingkat_pres_id:bigint","desc:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_pres.t_pelamar_id","m_general.id=t_pelamar_det_pres.tingkat_pres_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_pres","tahun","tingkat_pres_id"];
    public $createable  = ["t_pelamar_id","nama_pres","tahun","tingkat_pres_id","desc","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","nama_pres","tahun","tingkat_pres_id","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","nama_pres","tahun","tingkat_pres_id","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
    public function tingkat_pres() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tingkat_pres_id', 'id');
    }
}
