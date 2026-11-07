<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_org extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_org';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","nama","tahun","jenis_org_id","kota_id","posisi","desc","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","nama","tahun","jenis_org_id","kota_id","posisi","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","nama:string:100","tahun:integer","jenis_org_id:bigint","kota_id:bigint","posisi:string:100","desc:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_org.t_pelamar_id","m_general.id=t_pelamar_det_org.jenis_org_id","m_general.id=t_pelamar_det_org.kota_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","tahun","kota_id","posisi"];
    public $createable  = ["t_pelamar_id","nama","tahun","jenis_org_id","kota_id","posisi","desc","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","nama","tahun","jenis_org_id","kota_id","posisi","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","nama","tahun","jenis_org_id","kota_id","posisi","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
    public function jenis_org() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'jenis_org_id', 'id');
    }
    public function kota() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kota_id', 'id');
    }
}
